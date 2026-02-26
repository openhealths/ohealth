@use('App\Livewire\Contract\CapitationContractCreate')

<fieldset class="fieldset">
    <legend class="legend">
        <h2> {{ __('forms.legal_entity_info') }}</h2>
    </legend>

    <div class="form-row-2">
        <div class="form-group">
            <input value="{{ $legalEntityName }}"
                   type="text"
                   name="legalEntityName"
                   id="legalEntityName"
                   class="peer input"
                   placeholder=" "
                   required
                   disabled
            />
            <label for="legalEntityName" class="label">{{ __('contracts.legal_entity') }}</label>

            @error('form.') <p class="text-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <input value="{{ $contractorFullName }}"
                   type="text"
                   name="legalEntityOwner"
                   id="legalEntityOwner"
                   class="peer input"
                   placeholder=" "
                   required
                   disabled
            />
            <label for="legalEntityOwner" class="label">{{ __('contracts.contractor_owner')}}</label>

            @error('form.contractorOwnerId')
            <p class="text-error">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <input wire:model="form.contractorBase"
                   type="text"
                   name="contractorBase"
                   id="contractorBase"
                   class="peer input"
                   placeholder=" "
                   required
            />
            <label for="contractorBase" class="label">{{ __('contracts.contractor_base') }}</label>

            @error('form.contractorBase') <p class="text-error">{{ $message }}</p> @enderror
        </div>
    </div>

    @if($this instanceof CapitationContractCreate)
        <div class="form-row-2">
            <div class="form-group">
                <input wire:model="form.contractorRmspAmount"
                       type="number"
                       name="contractorRmspAmount"
                       id="contractorRmspAmount"
                       class="peer input"
                       placeholder=" "
                       required
                />
                <label for="contractorRmspAmount" class="label">
                    {{ __('contracts.contractor_rmsp_amount') }}
                </label>

                @error('form.contractorRmspAmount') <p class="text-error">{{ $message }}</p> @enderror
            </div>
        </div>
    @endif
</fieldset>
