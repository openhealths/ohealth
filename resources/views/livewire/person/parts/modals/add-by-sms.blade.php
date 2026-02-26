@use('App\Enums\Person\AuthStep')

<div wire:key="auth-step-add-sms">
    <legend class="legend">
        {{ __('patients.adding_authentication_method_SMS') }}
    </legend>

    <div class="space-y-10">
        <div class="form-row-3">
            <div class="form-group group">
                <input type="tel"
                       placeholder=" "
                       class="peer input !py-2"
                       wire:model="newPhoneNumber"
                       id="phoneNumber"
                       x-mask="+380999999999"
                />
                <label class="label" for="phoneNumber">{{ __('+380') }}</label>
            </div>
        </div>

        <div class="form-row-3">
            <div class="form-group group">
                <input type="text"
                       placeholder=" "
                       class="peer input !py-2"
                       wire:model="alias"
                       id="alias"
                />
                <label class="label" for="alias">{{ __('patients.authentication_method_name') }}</label>
            </div>
        </div>
    </div>

    <div class="mt-12 flex gap-4">
        <button type="button" @click="localStep = {{ AuthStep::INITIAL }}" class="button-minor">
            {{ __('forms.back') }}
        </button>

        <button type="button" wire:click="createOtpAuthMethod" class="button-primary">
            {{ __('forms.confirm') }}
        </button>
    </div>
</div>
