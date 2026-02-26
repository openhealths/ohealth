@php
    use App\Livewire\Contract\CapitationContractCreate;

    $dictionary = $this instanceof CapitationContractCreate ? $this->dictionaries['CAPITATION_CONTRACT_CONSENT_TEXT'] : $this->dictionaries['REIMBURSEMENT_CONTRACT_CONSENT_TEXT'];
@endphp

<fieldset class="fieldset">
    <legend class="legend">
        <h2>{{ __('contracts.consent_text') }}</h2>
    </legend>

    <div class='flex flex-col gap-9'>
        <div class='dark:bg-boxdark'>
            <div class='border-stroke px-6.5 py-1 dark:border-strokedark'>
                <h3 class='font-medium text-black dark:text-white'>
                </h3>
            </div>

            <div class='flex flex-col gap-5.5 p-6.5'>
                <p class='ms-2 text-sm font-regular text-justify text-gray-900 dark:text-gray-300'>
                    {{ $dictionary['APPROVED'] }}
                </p>

                <x-forms.form-group class='mt-4 pl-2'>
                    <x-slot name='input'>
                        <div class="flex items-center">
                            <x-forms.checkbox wire:model="form.consentText"
                                              id="consent_text"
                                              type='checkbox'
                                              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                            />

                            <label for='consent_text'
                                   class='ml-2 text-sm font-medium text-gray-900 dark:text-gray-300 cursor-pointer'>
                                {{ __('contracts.read_and_agreed') }}
                            </label>
                        </div>
                    </x-slot>

                    @error('form.consent_text')
                    <x-slot name='error'>
                        <x-forms.error>
                            {{ $message }}
                        </x-forms.error>
                    </x-slot>
                    @enderror
                </x-forms.form-group>
            </div>
        </div>
    </div>
</fieldset>
