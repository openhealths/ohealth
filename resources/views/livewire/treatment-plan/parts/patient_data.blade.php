<fieldset class="fieldset">
    <legend class="legend">
        {{ __('treatment-plan.patient_data') }}
    </legend>

    <div class="form-row-2">
        <div class="form-group group">
            <input type="text"
                   name="patient"
                   id="patient"
                   class="input-select peer"
                   placeholder=" "
                   autocomplete="off"
                   required
            >

            <label for="patient" class="label">
                {{ __('treatment-plan.patient') }}
            </label>
            @error('treatment-plan.patient')
            <p class="text-error">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group group">
            <input type="text"
                   name="medical_number"
                   id="medical_number"
                   class="input-select peer"
                   placeholder=" "
                   autocomplete="off"
                   required
            >

            <label for="medical_number" class="label">
                {{ __('treatment-plan.medical_number') }}
            </label>
            @error('treatment-plan.medical_number')
            <p class="text-error">{{ $message }}</p>
            @enderror
        </div>
    </div>
</fieldset>
