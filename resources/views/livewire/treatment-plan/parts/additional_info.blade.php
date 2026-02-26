<fieldset class="fieldset">
    <legend class="legend">
        {{ __('forms.additional_info') }}
    </legend>

    <div class="form-row-2">
        <div class="form-group group">
            <label for="based_treatment_plan" class="label">
                {{ __('treatment-plan.based_treatment_plan') }}
            </label>

            <select id="based_treatment_plan"
                    name="based_treatment_plan"
                    class="input-select peer"
                    type="text"
            >
                <option selected value="">{{ __('treatment-plan.choose_treatment_plan') }}</option>
            </select>

            @error('treatment-plan.based_treatment_plan')
            <p class="text-error">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group group">
            <label for="part_treatment_plan" class="label">
                {{ __('treatment-plan.part_treatment_plan') }}
            </label>

            <select id="part_treatment_plan"
                    name="part_treatment_plan"
                    class="input-select peer"
                    type="text"
            >
                <option selected value="">{{ __('treatment-plan.choose_treatment_plan') }}</option>
            </select>

            @error('treatment-plan.part_treatment_plan')
            <p class="text-error">{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="extended_description"
                   class="peer appearance-none bg-white">{{ __('treatment-plan.extended_description') }}</label>
            <textarea
                id="extended_description"
                class="textarea !text-gray-500 dark:!text-gray-400 mt-1"
                placeholder="{{ __('forms.comment') }}">
                </textarea>
            @error('treatment-plan.extended_description') <p class="text-error">{{ $message }}</p> @enderror
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="notes"
                   class="peer appearance-none bg-white">{{ __('treatment-plan.notes') }}</label>
            <textarea
                id="notes"
                class="textarea !text-gray-500 dark:!text-gray-400 mt-1"
                placeholder="{{ __('forms.comment') }}">
                </textarea>
            @error('treatment-plan.notes') <p class="text-error">{{ $message }}</p> @enderror
        </div>
    </div>
</fieldset>
