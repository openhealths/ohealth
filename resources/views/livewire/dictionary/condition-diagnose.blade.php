<div x-data="{ showCodes: false }">
    <x-header-navigation x-data="{ showFilter: false }" class="breadcrumb-form">
        <x-slot name="title">
            {{ __('dictionaries.condition_diagnose.title') }}
        </x-slot>

        <x-slot name="navigation">
            <div class="flex flex-col gap-4 max-w-sm">
                <div class="flex items-center gap-1 font-semibold text-gray-900 dark:text-white">
                    @icon('search-outline', 'w-4.5 h-4.5')
                    <p>{{ __('dictionaries.condition_diagnose.search_title') }}</p>
                </div>

                <div class="form-group group w-full">
                    <label
                        for="conditionDiagnoseGroup"
                        class="default-label mb-2"
                    >
                        {{ __('dictionaries.condition_diagnose.group_label') }}
                    </label>

                    <select
                        id="conditionDiagnoseGroup"
                        name="conditionDiagnoseGroup"
                        class="peer input-select w-full"
                    >
                        <option value="" selected>{{ __('forms.select') }}</option>
                        <option value="example">
                            {{ __('dictionaries.condition_diagnose.example_group') }}
                        </option>
                    </select>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button
                        type="button"
                        class="button-primary flex items-center gap-2"
                    >
                        @icon('search', 'w-4 h-4')
                        <span>{{ __('forms.search') }}</span>
                    </button>
                    <button
                        type="button"
                        class="button-primary-outline-red"
                    >
                        {{ __('forms.reset_all_filters') }}
                    </button>
                </div>
            </div>
        </x-slot>
    </x-header-navigation>

    <section class="shift-content pl-3.5 mt-6 max-w-[1280px]">
        <template x-if="!showCodes">
            <fieldset class="fieldset p-6 sm:p-8">
                <legend class="legend">
                    {{ __('dictionaries.condition_diagnose.details_title') }}
                </legend>

                <div class="space-y-4 text-gray-900 dark:text-gray-100">
                    <p class="text-lg font-semibold">
                        {{ __('dictionaries.condition_diagnose.example_group') }}
                    </p>

                    <button
                        type="button"
                        class="button-outline-primary"
                        @click="showCodes = true"
                    >
                        {{ __('dictionaries.condition_diagnose.codes_list_button') }}
                    </button>
                </div>
            </fieldset>
        </template>

        <template x-if="showCodes">
            <fieldset
                x-cloak
                x-transition
                class="fieldset p-6 sm:p-8"
            >
                <div class="space-y-1 text-sm sm:text-base leading-relaxed text-gray-900 dark:text-gray-100">
                    <p class="text-lg font-semibold mb-4">
                        {{ __('dictionaries.condition_diagnose.example_group') }}
                    </p>
                    <p>B20 - Хвороба, зумовлена вірусом імунодефіциту людини [ВІЛ], яка проявляється інфекційними та паразитарними хворобами</p>
                    <p>B21 - Хвороба, зумовлена вірусом імунодефіциту людини [ВІЛ], внаслідок чого виникають злоякісні новоутворення</p>
                    <p>22 - Хвороба, зумовлена вірусом імунодефіциту людини [ВІЛ], з проявами інших уточнених хвороб</p>
                    <p>B23.0 - Гострий ВІЛ-інфекційний синдром</p>
                    <p>B23.8 - Хвороба ВІЛ з проявами інших уточнених станів</p>
                    <p>B24 - Хвороба, зумовлена вірусом імунодефіциту людини [ВІЛ], неуточнена</p>
                    <p>098.7 - Вірус імунодефіциту людини [ВІЛ] під час вагітності, пологів та післяпологового періоду</p>
                    <p>R75 - Лабораторне виявлення вірусу імунодефіциту людини [ВІЛ]</p>
                    <p>Z11.4 - Спеціальне скринінгове обстеження з метою виявлення інфікування вірусом імунодефіциту людини [ВІЛ]</p>
                    <p>Z20.6 - Контакт з хворим або можливість зараження вірусом імунодефіциту людини [ВІЛ]</p>
                    <p>Z21 - Безсимптомне носійство вірусу імунодефіциту людини [ВІЛ]</p>
                    <p>Z71.7 - Консультації з питань, пов’язаних з вірусом імунодефіциту людини [ВІЛ]</p>
                </div>
            </fieldset>
        </template>
    </section>

    <x-forms.loading />
    <livewire:components.x-message :key="time()" />
</div>
