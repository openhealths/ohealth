@use('App\Enums\Person\AuthenticationMethod')

<fieldset class="fieldset"
          x-data="{
              authenticationMethods: $wire.entangle('form.person.authenticationMethods'),
              isIncapacitated: $wire.entangle('isIncapacitated'),
              showAuthDocDrawer: false,
              smsCode: '',
              infoConfirmed: false,

              init() {
                  // Watch for changes to isIncapacitated and reset auth method if needed
                  this.$watch('isIncapacitated', (newValue) => {
                      const currentAuthType = this.authenticationMethods[0]?.type;
                      const availableTypes = this.availableAuthMethods.map(method => method.value);

                      // If current auth method is not available in the new state, reset it
                      if (currentAuthType && !availableTypes.includes(currentAuthType)) {
                          this.authenticationMethods[0].type = '';
                          // Clear phoneNumber if switching away from OTP
                          if (this.authenticationMethods[0].phoneNumber) {
                              this.authenticationMethods[0].phoneNumber = '';
                          }
                      }
                  });
              },

              get availableAuthMethods() {
                  const allMethods = [
                      {
                          value: '{{ AuthenticationMethod::OTP->value }}',
                          label: '{{ __('forms.authentication') }} {{ AuthenticationMethod::OTP->label() }}'
                      },
                      {
                          value: '{{ AuthenticationMethod::OFFLINE->value }}',
                          label: '{{ __('forms.authentication') }} {{ AuthenticationMethod::OFFLINE->label() }}'
                      },
                      {
                          value: '{{ AuthenticationMethod::THIRD_PERSON->value }}',
                          label: '{{ __('forms.authentication') }} {{ AuthenticationMethod::THIRD_PERSON->label() }}'
                      }
                  ];

                  if (this.isIncapacitated) {
                      // If patient is incapacitated, only show THIRD_PERSON authentication
                      return allMethods.filter(method => method.value === '{{ AuthenticationMethod::THIRD_PERSON->value }}');
                  } else {
                      // If patient is not incapacitated, only show OTP and OFFLINE authentication
                      return allMethods.filter(method =>
                          method.value === '{{ AuthenticationMethod::OTP->value }}' ||
                          method.value === '{{ AuthenticationMethod::OFFLINE->value }}'
                      );
                  }
              }
          }"
>
    <legend class="legend">{{ __('forms.authentication') }}</legend>

    <div class="form-row-3">
        <div class="form-group group">
            <label for="relationType" class="sr-only">
                {{ __('forms.authentication') }}
            </label>
            <select x-model="authenticationMethods[0].type"
                    x-init="$nextTick(() => authenticationMethods = JSON.parse(JSON.stringify(authenticationMethods)))"
                    id="relationType"
                    class="input-select peer @error('form.person.authenticationMethods.*.type') input-error @enderror"
                    required
            >
                <option selected value="">
                    {{ __('forms.select') }} {{ mb_strtolower(__('forms.authentication')) }} *
                </option>
                <template x-for="method in availableAuthMethods" :key="method.value">
                    <option :value="method.value" x-text="method.label"></option>
                </template>
            </select>

            @error('form.person.authenticationMethods.*.type') <p class="text-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <template x-if="authenticationMethods[0]?.type === '{{ AuthenticationMethod::OTP->value }}'">
        <div class="form-row-3">
            <div class="form-group group">
                <input x-model="authenticationMethods[0].phoneNumber"
                       type="text"
                       x-mask="+380999999999"
                       name="phoneNumber"
                       id="phoneNumber"
                       class="input peer @error('form.person.authenticationMethods.*.phoneNumber') input-error @enderror"
                       placeholder=" "
                       required
                       autocomplete="off"
                />
                <label for="phoneNumber" class="label">
                    {{ __('forms.phone_number') }}
                </label>

                @error('form.person.authenticationMethods.*.phoneNumber')
                <p class="text-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </template>

    <template x-if="authenticationMethods[0]?.type === '{{ AuthenticationMethod::THIRD_PERSON->value }}'">
        <div class="form-row-3">
            <div class="form-group group">
                <input x-model="authenticationMethods[0].alias"
                       type="text"
                       name="alias"
                       id="alias"
                       class="input peer @error('form.person.authenticationMethods.*.alias') input-error @enderror"
                       placeholder=" "
                       required
                       autocomplete="off"
                />
                <label for="alias" class="label">
                    {{ __('patients.alias') }}
                </label>

                @error('form.person.authenticationMethods.*.alias') <p class="text-error">{{ $message }}</p> @enderror
            </div>
        </div>
    </template>
</fieldset>
