@php
    $hasLicenseTypeError = $errors->has('legalEntityForm.license.type');
    $hasLicenseIssuedByError = $errors->has('legalEntityForm.license.issuedBy');
    $hasLicenseIssuedDateError = $errors->has('legalEntityForm.license.issuedDate');
    $hasLicenseActiveFromDateError = $errors->has('legalEntityForm.license.activeFromDate');
    $hasLicenseExpirationDateError = $errors->has('legalEntityForm.license.expiryDate');
    $hasLicenseOrderNumberError = $errors->has('legalEntityForm.license.orderNo');
    $hasLicenseNumberError = $errors->has('legalEntityForm.license.licenseNumber');
@endphp

<fieldset
    class="p-4 sm:p-8 sm:pb-10 mb-16 mt-6 border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 max-w-[1280px]"
    xmlns="http://www.w3.org/1999/html"
    x-data="{
        title: '{{ __('forms.licenses') }}',
        index: 6,
        isDisabled: false // let it be here... for now
    }"
    x-init="typeof addHeader !== 'undefined' && addHeader(title, index)"
    x-show="activeStep === index || isEdit"
    x-cloak
    :key="`step-${index}`"
>
    <template x-if="isEdit">
        <legend x-text="title" class="legend"></legend>
    </template>

    <div class='form-row-3'>
        <div class="form-group group">
            <select
                required
                id="licenseType"
                wire:model.defer="legalEntityForm.license.type"
                aria-describedby="{{ $hasLicenseTypeError ? 'licenseTypeErrorHelp' : '' }}"
                class="input-select  {{ $hasLicenseTypeError ? 'input-error border-red-500 focus:border-red-500' : ''}} peer"
                :class="isDisabled ? 'text-gray-400 border-gray-200 dark:text-gray-500' : 'text-gray-900 border-gray-300'"
                :disabled="{{ $isEdit ? 'true' : 'false' }}"
            >
                <option value="_placeholder_" selected hidden>-- {{ __('forms.select') }} --</option>

                @foreach($dictionaries['LICENSE_TYPE'] as $k => $license_type)
                    <option value="{{ $k }}" @selected($k == $this->getLicenseTypesByLegalEntityType($legalEntityForm->type))>
                        {{ $license_type }}
                    </option>
                @endforeach
            </select>

            @if($hasLicenseTypeError)
                <p id="licenseTypeErrorHelp" class="text-error">
                    {{ $errors->first('legalEntityForm.license.type') }}
                </p>
            @endif

            <label for="licenseType" class="label z-10">
                {{ __('licenses.type.label') }}
            </label>
        </div>

        <div class="form-group group">
            <input
                type="text"
                placeholder=" "
                id="licenseNumber"
                wire:model="legalEntityForm.license.licenseNumber"
                class="input peer"
                aria-describedby="{{ $hasLicenseNumberError ? 'licenseNumberErrorHelp' : '' }}"
                :class="isDisabled ? 'text-gray-400 border-gray-200 dark:text-gray-500' : 'text-gray-900 border-gray-300'"
                :disabled="isDisabled"
            />

            @if($hasLicenseNumberError)
                <p id="licenseNumberErrorHelp" class="text-error">
                    {{ $errors->first('legalEntityForm.license.licenseNumber') }}
                </p>
            @endif

            <label for="licenseNumber" class="label z-10">
                {{ __('licenses.number') }}
            </label>
        </div>

        <div class="form-group group">
            <input
                required
                type="text"
                placeholder=" "
                id="licenseIssuedBy"
                wire:model="legalEntityForm.license.issuedBy"
                aria-describedby="{{ $hasLicenseIssuedByError ? 'licenseIssuedByErrorHelp' : '' }}"
                class="input {{ $hasLicenseIssuedByError ? 'input-error border-red-500 focus:border-red-500' : ''}} peer"
                :class="isDisabled ? 'text-gray-400 border-gray-200 dark:text-gray-500' : 'text-gray-900 border-gray-300'"
                :disabled="isDisabled"
            />

            @if($hasLicenseIssuedByError)
                <p id="licenseIssuedByErrorHelp" class="text-error">
                    {{ $errors->first('legalEntityForm.license.issuedBy') }}
                </p>
            @endif

            <label for="licenseIssuedBy" class="label z-10">
                {{ __('forms.document_issued_by') }}
            </label>
        </div>

        <div class="form-group group">
            <svg class="svg-input" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
            </svg>

            <input
                required
                type="text"
                placeholder=" "
                datepicker-format="{{ frontendDateFormat() }}"
                id="licenseIssuedDate"
                wire:model="legalEntityForm.license.issuedDate"
                aria-describedby="{{ $hasLicenseIssuedDateError ? 'licenseIssuedDateErrorHelp' : '' }}"
                class="input datepicker-input {{ $hasLicenseIssuedDateError ? 'input-error border-red-500 focus:border-red-500' : ''}} peer"
                :class="isDisabled ? 'text-gray-400 border-gray-200 dark:text-gray-500' : 'text-gray-900 border-gray-300'"
                :disabled="isDisabled"
            />

            @if($hasLicenseIssuedDateError)
                <p id="licenseIssuedDateErrorHelp" class="text-error">
                    {{ $errors->first('legalEntityForm.license.issuedDate') }}
                </p>
            @endif

            <label for="licenseIssuedDate" class="label z-10">
                {{ __('forms.document_issued_at') }}
            </label>
        </div>

        <div class="form-group group">
            <svg class="svg-input" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
            </svg>

            <input
                required
                type="text"
                placeholder=" "
                datepicker-format="{{ frontendDateFormat() }}"
                id="licenseActiveFromDate"
                wire:model="legalEntityForm.license.activeFromDate"
                aria-describedby="{{ $hasLicenseActiveFromDateError ? 'licenseActiveFromDateErrorHelp' : '' }}"
                class="input datepicker-input {{ $hasLicenseActiveFromDateError ? 'input-error border-red-500 focus:border-red-500' : ''}} peer"
                :class="isDisabled ? 'text-gray-400 border-gray-200 dark:text-gray-500' : 'text-gray-900 border-gray-300'"
                :disabled="isDisabled"
            />

            @if($hasLicenseActiveFromDateError)
                <p id="licenseActiveFromDateErrorHelp" class="text-error">
                    {{ $errors->first('legalEntityForm.license.activeFromDate') }}
                </p>
            @endif

            <label for="licenseActiveFromDate" class="label z-10">
                {{ __('licenses.active_from_date') }}
            </label>
        </div>

        <div class="form-group group">
            <svg class="svg-input" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
            </svg>

            <input
                type="text"
                placeholder=" "
                datepicker-format="{{ frontendDateFormat() }}"
                id="licenseExpiryDate"
                wire:model="legalEntityForm.license.expiryDate"
                class="input datepicker-input peer"
                aria-describedby="{{ $hasLicenseExpirationDateError ? 'licenseExpirationDateErrorHelp' : '' }}"
                :class="isDisabled ? 'text-gray-400 border-gray-200 dark:text-gray-500' : 'text-gray-900 border-gray-300'"
                :disabled="isDisabled"
            />

            @if($hasLicenseExpirationDateError)
                <p id="licenseExpirationDateErrorHelp" class="text-error">
                    {{ $errors->first('legalEntityForm.license.expiryDate') }}
                </p>
            @endif

            <label for="licenseExpiryDate" class="label z-10">
                {{ __('forms.end_date') }}
            </label>
        </div>

        <div class="form-group group">
            <input
                type="text"
                placeholder=" "
                id="licenseWhatLicensed"
                wire:model="legalEntityForm.license.whatLicensed"
                class="input peer"
                :class="isDisabled ? 'text-gray-400 border-gray-200 dark:text-gray-500' : 'text-gray-900 border-gray-300'"
                :disabled="isDisabled"
            />

            <label for="licenseWhatLicensed" class="label z-10">
                {{ __('licenses.what_licensed') }}
            </label>
        </div>

        <div class="form-group group">
            <input
                required
                type="text"
                placeholder=" "
                id="licenseOrderNumber"
                wire:model="legalEntityForm.license.orderNo"
                aria-describedby="{{ $hasLicenseOrderNumberError ? 'licenseOrderNumberErrorHelp' : '' }}"
                class="input {{ $hasLicenseOrderNumberError ? 'input-error border-red-500 focus:border-red-500' : ''}} peer"
                :class="isDisabled ? 'text-gray-400 border-gray-200 dark:text-gray-500' : 'text-gray-900 border-gray-300'"
                :disabled="isDisabled"
            />

            @if($hasLicenseOrderNumberError)
                <p id="licenseOrderNumberErrorHelp" class="text-error">
                    {{ $errors->first('legalEntityForm.license.orderNo') }}
                </p>
            @endif

            <label for="licenseOrderNumber" class="label z-10">
                {{ __('licenses.order_no') }}
            </label>
        </div>
    </div>
</fieldset>
