@use('App\Enums\Person\AuthStep')

<div>
    <legend class="legend">
        {{ __('patients.add_auth_method_documents') }}
    </legend>

    @foreach($this->uploadedDocuments as $key => $document)
        <div class="pb-4 flex" wire:key="{{ $key }}">
            <div class="flex-grow">
                <label class="block mb-3 text-sm font-medium text-gray-900 dark:text-white" for="fileInput-{{ $key }}">
                    {{ __('patients.documents.' . Str::afterLast($document['type'], '.')) }}
                </label>
                <div class="flex items-center gap-4">
                    <input type="file"
                           class="xl:w-1/2 block text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                           id="fileInput-{{ $key }}"
                           wire:model.live="form.uploadedDocuments.{{ $key }}"
                           accept=".jpeg,.jpg"
                    >
                </div>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                    {{ __('forms.max_file_size_and_format') }}
                </p>

                @error("form.uploadedDocuments.$key")
                <p class="text-error">
                    {{ $message }}
                </p>
                @enderror
            </div>
        </div>
    @endforeach

    <div class="mt-12 flex gap-3">
        <button type="button" @click="localStep = {{ AuthStep::INITIAL }}" class="button-minor">
            {{ __('forms.back') }}
        </button>

        <button type="button" wire:click="approveCreatingOffline" class="button-primary">
            {{ __('forms.confirm') }}
        </button>
    </div>
</div>
