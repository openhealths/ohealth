<fieldset
    class="p-4 sm:p-8 sm:pb-10 mb-16 mt-6 border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 max-w-[1280px]"
    xmlns="http://www.w3.org/1999/html"
    x-data="{ title: '{{ __('forms.address') }}', index: 4 }"
    x-init="typeof addHeader !== 'undefined' && addHeader(title, index)"
    x-show="activeStep === index  || isEdit"
    x-cloak
    :key="`step-${index}`"
>
    <template x-if="isEdit">
        <legend x-text="title" class="legend"></legend>
    </template>

    <div>
        <x-forms.addresses-search
            :address="$address"
            :districts="$districts"
            :settlements="$settlements"
            :streets="$streets"
            class="mb-4 form-row-3"
        />
    </div>
</fieldset>
