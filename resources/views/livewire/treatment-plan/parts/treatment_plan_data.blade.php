<fieldset class="fieldset">
    <legend class="legend">
        {{ __('treatment-plan.treatment_plan_data') }}
    </legend>

    <div class="form-row-2">
        <div class="form-group group">
            <label for="category" class="label">
                {{ __('treatment-plan.category') }}
            </label>

            <select id="category"
                    name="category"
                    class="input-select peer"
                    type="text"
            >
                <option selected value="">{{ __('treatment-plan.category') }}</option>
            </select>

            @error('treatment-plan.category')
            <p class="text-error">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group group">
            <input type="text"
                   name="name_treatment_plan"
                   id="name_treatment_plan"
                   class="input-select peer"
                   placeholder=" "
                   autocomplete="off"
                   required
            >

            <label for="name_treatment_plan" class="label">
                {{ __('treatment-plan.name_treatment_plan') }}
            </label>
            @error('treatment-plan.name_treatment_plan')
            <p class="text-error">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="form-row-2">
        <div class="form-group group">
            <label for="intention" class="label">
                {{ __('treatment-plan.intention') }}
            </label>

            <select id="intention"
                    name="intention"
                    class="input-select peer"
                    type="text"
            >
                <option selected value="">{{ __('forms.select') }}</option>
            </select>

            @error('treatment-plan.intention')
            <p class="text-error">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group group">
            <label for="terms_service" class="label">
                {{ __('treatment-plan.terms_service') }}
            </label>

            <select id="terms_service"
                    name="terms_service"
                    class="input-select peer"
                    type="text"
            >
                <option selected value="">{{ __('forms.select') }}</option>
            </select>

            @error('treatment-plan.terms_service')
            <p class="text-error">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="form-row-2 mt-5">
        <div class="form-group datepicker-wrapper relative w-full">
            <input x-model="period.during.startDate"
                   type="text"
                   name="start"
                   :id="'startDate"
                   class="peer input pl-10 appearance-none datepicker-input dark:text-white"
                   placeholder=" "
                   required
                   datepicker-autohide
                   datepicker-format="dd.mm.yyyy"
                   datepicker-button="false"
                   x-bind:disabled="isDisabled"
            />
            <label :for="'startDate" class="wrapped-label">
                {{ __('treatment-plan.date_and_time_start') }}
            </label>
        </div>

        <div class="form-group w-full">
            <label :for="'startTime"
                   class="label !text-xs !text-gray-500 dark:!text-gray-400"
            >
                <span>{{ __('healthcare-services.choose_time') }}</span>
            </label>
            <div class="relative w-full">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400"
                         aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg"
                         width="24"
                         height="24"
                         fill="none"
                         viewBox="0 0 24 24"
                    >
                        <path stroke="currentColor"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                        />
                    </svg>
                </div>
                <input type="text"
                       class="input timepicker-uk text-gray-900 dark:text-white border-t-0 border-r-0 border-l-0 border-b border-gray-300 dark:border-gray-700 focus:ring-0 px-0 ps-8"
                       placeholder="00:00"
                       :id="'startTime-'+idx"
                       x-model="period.during.startTime"
                       x-bind:disabled="isDisabled"
                />
            </div>
        </div>
    </div>

    <div class="form-row-2">
        <div class="form-group datepicker-wrapper relative w-full">
            <input x-model="period.during.endDate"
                   type="text"
                   name="end"
                   :id="'endDate"
                   class="peer input pl-10 appearance-none datepicker-input dark:text-white"
                   placeholder=" "
                   required
                   datepicker-autohide
                   datepicker-format="dd.mm.yyyy"
                   datepicker-button="false"
                   x-bind:disabled="isDisabled"
            />
            <label :for="'endDate" class="wrapped-label">
                {{ __('treatment-plan.date_and_time_end') }}
            </label>
        </div>

        <div class="form-group w-full">
            <label :for="'endTime"
                   class="label !text-xs !text-gray-500 dark:!text-gray-400"
            >
                <span>{{ __('healthcare-services.choose_time') }}</span>
            </label>
            <div class="relative w-full">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400"
                         aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg"
                         width="24"
                         height="24"
                         fill="none"
                         viewBox="0 0 24 24"
                    >
                        <path stroke="currentColor"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                        />
                    </svg>
                </div>
                <input type="text"
                       class="input timepicker-uk text-gray-900 dark:text-white border-t-0 border-r-0 border-l-0 border-b border-gray-300 dark:border-gray-700 focus:ring-0 px-0 ps-8"
                       placeholder="00:00"
                       :id="'endTime-'+idx"
                       x-model="period.during.endTime"
                       x-bind:disabled="isDisabled"
                />
            </div>
        </div>
    </div>
    <div class="bg-red-100 rounded-lg">
        <div class="p-4">
            <div class="flex items-center gap-2 mb-2">
                @icon('alert-circle', 'w-5 h-5 text-red-700')
                <p class="font-semibold text-red-700">{{ __('treatment-plan.attention') }}</p>
            </div>
            <p class="text-sm text-red-700">{{ __('treatment-plan.you_specify_the_end_date') }}</p>
        </div>
    </div>
</fieldset>

