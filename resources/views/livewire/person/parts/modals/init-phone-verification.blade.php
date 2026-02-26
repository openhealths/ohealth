@use('App\Enums\Person\AuthStep')

{{-- Check is phone number verified --}}
<div>
    <legend class="legend mb-8 text-2xl font-bold">{{ __('patients.changing_sms_method') }}</legend>

    <div class="bg-gray-100 dark:bg-slate-800 rounded-lg p-4 mb-8 flex items-start">
        @icon('alert-circle', 'w-5 h-5 text-gray-700 dark:text-gray-300 mr-3 mt-0.5')
        <p class="text-sm text-gray-800 dark:text-gray-200">
            {{ __('patients.please_clarify_phone_number') }}
            <span class="font-bold">{{ $phoneNumber }}</span>
        </p>
    </div>

    <div class="form-row-3">
        <div class="form-group">
            <input type="tel"
                   placeholder=" "
                   class="peer input @error('form.phoneNumber') input-error @enderror"
                   wire:model="form.phoneNumber"
                   x-mask="+380999999999"
                   id="phoneNumber"
                   name="phoneNumber"
            />
            <label for="phoneNumber" class="label">{{ __('patients.enter_a_new_phone_number') }}</label>

            @error('form.phoneNumber') <p class="text-error">{{ $message }} </p>@enderror
        </div>
    </div>

    <div class="mt-8 flex gap-3">
        <button type="button" @click="localStep = {{ AuthStep::INITIAL }}" class="button-minor">
            {{ __('patients.back_authentication_methods') }}
        </button>

        <button type="button" @click="localStep = {{ AuthStep::NO_PHONE_ACCESS }}" class="button-primary-outline-red">
            {{ __('patients.no_access') }}
        </button>

        <button type="button" wire:click="verifyOwnership" class="button-primary">
            {{ __('patients.available_access') }}
        </button>
    </div>
</div>
