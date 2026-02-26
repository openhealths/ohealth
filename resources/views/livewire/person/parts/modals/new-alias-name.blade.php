@use('App\Enums\Person\AuthStep')

<div>
    <legend class="legend mb-8 text-2xl font-bold">{{ __('patients.new_alias_method') }}</legend>

    <div class="form-row-3">
        <div class="form-group">
            <input type="text"
                   placeholder=" "
                   class="peer input @error('alias') input-error @enderror"
                   wire:model="alias"
            />
            <label class="label">{{ __('forms.name') }}</label>

            @error('alias') <p class="text-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="mt-8 flex gap-3">
        <button type="button" @click="localStep = {{ AuthStep::INITIAL }}" class="button-minor">
            {{ __('forms.back') }}
        </button>

        <button type="button" wire:click="updateAliasName" class="button-primary">
            {{ __('forms.confirm') }}
        </button>
    </div>
</div>
