@use('App\Enums\Person\AuthStep')

<div>
    <legend class="legend mb-8 text-2xl font-bold">{{ __('patients.changing_sms_method') }}</legend>

    <div class="bg-red-100 dark:bg-slate-800 rounded-lg p-4 mb-8 flex items-start">
        @icon('alert-circle', 'w-5 h-5 text-red-700 dark:text-red-300 mr-3 mt-0.5')
        <p class="text-sm text-red-800 dark:text-red-200">
            {{ __('patients.if_patient_not_phone_authentication', ['phoneNumber' => $phoneNumber]) }}
        </p>
    </div>

    <div class="mt-8 flex gap-3">
        <button type="button" @click="localStep = {{ AuthStep::CHANGE_PHONE_INITIAL }}" class="button-minor">
            {{ __('forms.back') }}
        </button>

        <button type="button" @click="localStep = {{ AuthStep::INITIAL }}" class="button-primary-outline">
            {{ __('patients.to_authentication_methods') }}
        </button>
    </div>
</div>
