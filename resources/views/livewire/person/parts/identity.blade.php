@use('App\Livewire\Person\PersonUpdate')

<fieldset class="fieldset"
          x-data="{
              noTaxId: $wire.form.person.noTaxId || false,
              init() {
                  $wire.form.person.noTaxId = this.noTaxId;
              },
              handleNoTaxIdChange() {
                  if (this.noTaxId) {
                      $wire.form.person.noTaxId = true;
                      delete $wire.form.person.taxId;
                  } else {
                      $wire.form.person.noTaxId = false;
                  }
              }
          }"
>

    <legend class="legend">
        {{ __('forms.rnokpp') }}/{{ __('forms.ipn') }}
    </legend>

    <div class="flex items-center gap-2 mb-4">
        <label for="noTaxId" class="default-label">
            {{ __('patients.rnokpp_not_found') }}
        </label>
        <input x-model="noTaxId"
               @change="handleNoTaxIdChange()"
               type="checkbox"
               name="noTaxId"
               id="noTaxId"
               class="default-checkbox mb-2"
               @disabled($this instanceof PersonUpdate && $form->person['taxId'])
        />
    </div>

    <div x-show="!noTaxId" class="form-row-3" x-transition x-cloak>
        <div class="form-group group">
            <input wire:model="form.person.taxId"
                   type="text"
                   name="taxId"
                   id="taxId"
                   class="input peer @error('form.person.taxId') input-error @enderror"
                   placeholder=" "
                   required
                   maxlength="10"
                   autocomplete="off"
                   @disabled($this instanceof PersonUpdate && $form->person['taxId'])
            />
            <label for="taxId" class="label">
                {{ __('forms.tax_id') }}
            </label>

            @error('form.person.taxId') <p class="text-error">{{ $message }}</p> @enderror
        </div>
    </div>
</fieldset>
