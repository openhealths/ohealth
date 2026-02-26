<fieldset class="fieldset">
    <legend class="legend">
        {{ __('forms.main_information') }}
    </legend>

    {{-- Patient's full name --}}
    <div class="form-row-2">
        <div class="form-group group">
            <input type="text"
                   name="person"
                   id="person"
                   class="input-select peer"
                   placeholder=" "
                   autocomplete="off"
                   disabled
                   value="{{ $patientFullName }}"
            >

            <label for="person" class="label">
                {{ __('patients.patient_full_name') }}
            </label>
        </div>
    </div>

    @if(!empty($employeesInfo) && count($employeesInfo) <= 1)
        {{-- Dr.'s full name --}}
        <div class="form-row-2">
            <div class="form-group group">
                <input type="text"
                       name="employee"
                       id="employee"
                       class="input-select peer"
                       value="{{ $employeesInfo[0]['fullName'] }} ({{ $this->dictionaries['POSITION'][$employeesInfo[0]['position']] }}) — {{ $employeesInfo[0]['divisionName'] }}"
                       disabled
                >

                <label for="employee" class="label">
                    {{ __('employees.doctor_full_name') }}
                </label>

                @error('form.employeeId') <p class="text-error">{{ $message }}</p> @enderror
            </div>
        </div>
    @else
        {{-- Choose doctor --}}
        <div class="form-row-2">
            <div class="form-group group">
                <label class="label" for="employeeId">{{ __('employees.doctor_full_name') }}</label>
                <select wire:model="form.employeeId"
                        id="employeeId"
                        name="employeeId"
                        class="input-select peer"
                        type="text"
                        required
                        @disabled(empty($employeesInfo))
                >
                    <option selected value="">
                        @empty($employeesInfo)
                            {{ __('Лікар має бути зареєстрованим у працюючому місці надання послуг') }}
                        @else
                            {{ __('forms.select') }}
                        @endif
                    </option>
                    @foreach($employeesInfo as $key => $employeeInfo)
                        <option value="{{ $employeeInfo['employeeId'] }}">
                            {{ $employeeInfo['fullName'] }}
                            ({{ $this->dictionaries['POSITION'][$employeeInfo['position']] }})
                            — {{ $employeeInfo['divisionName'] }}
                        </option>
                    @endforeach
                </select>

                @error('form.employeeId')
                <p class="text-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    @endif
</fieldset>
