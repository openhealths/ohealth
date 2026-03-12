<div x-data="{ showDetails: false }">
    <x-header-navigation x-data="{ showFilter: false }" class="breadcrumb-form">
        <x-slot name="title">
            {{ __('dictionaries.sensitive_group.title') }}
        </x-slot>

        <x-slot name="navigation">
            <div class="flex flex-col gap-4 max-w-sm">
                <div class="flex items-center gap-1 font-semibold text-gray-900 dark:text-white">
                    @icon('search-outline', 'w-4.5 h-4.5')
                    <p>{{ __('dictionaries.sensitive_group.search_title') }}</p>
                </div>

                <div class="form-group group w-full">
                    <label
                        for="sensitiveGroup"
                        class="default-label mb-2"
                    >
                        {{ __('dictionaries.sensitive_group.group_label') }}
                    </label>

                    <select
                        id="sensitiveGroup"
                        name="sensitiveGroup"
                        class="peer input-select w-full"
                    >
                        <option value="" selected>{{ __('forms.select') }}</option>
                        <option value="example">
                            {{ __('dictionaries.sensitive_group.example_group') }}
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
        <fieldset class="fieldset p-6 sm:p-8">
            <legend class="legend">
                {{ __('dictionaries.sensitive_group.details_title') }}
            </legend>

            <template x-if="!showDetails">
                <div class="space-y-4 text-gray-900 dark:text-gray-100">
                    <p class="text-lg font-semibold">
                        {{ __('dictionaries.sensitive_group.example_group') }}
                    </p>
                    <button
                        type="button"
                        class="button-outline-primary"
                        @click="showDetails = true"
                    >
                        {{ __('dictionaries.sensitive_group.details_button') }}
                    </button>
                </div>
            </template>

            <template x-if="showDetails">
                <div
                    x-cloak
                    x-transition
                    class="space-y-6 text-gray-900 dark:text-gray-100"
                >
                    <p class="text-lg font-semibold">
                        {{ __('dictionaries.sensitive_group.example_group') }}
                    </p>

                    <div class="space-y-3">
                        <p class="font-semibold">
                            {{ __('dictionaries.sensitive_group.codes_list_title') }}
                        </p>
                        <div class="space-y-1 text-sm sm:text-base leading-relaxed">
                            <p>B20 - Хвороба, зумовлена вірусом імунодефіциту людини [ВІЛ], яка проявляється інфекційними та паразитарними хворобами</p>
                            <p>B21 - Хвороба, зумовлена вірусом імунодефіциту людини [ВІЛ], внаслідок чого виникають злоякісні новоутворення</p>
                            <p>B22 - Хвороба, зумовлена вірусом імунодефіциту людини [ВІЛ], з проявами інших уточнених хвороб</p>
                            <p>B23.0 - Гострий ВІЛ-інфекційний синдром</p>
                            <p>B23.8 - Хвороба ВІЛ з проявами інших уточнених станів</p>
                            <p>B24 - Хвороба, зумовлена вірусом імунодефіциту людини [ВІЛ], неуточнена</p>
                            <p>O98.7 - Вірус імунодефіциту людини [ВІЛ] під час вагітності, пологів та післяпологового періоду</p>
                            <p>R75 - Лабораторне виявлення вірусу імунодефіциту людини [ВІЛ]</p>
                            <p>Z11.4 - Спеціальне скринінгове обстеження з метою виявлення інфікування вірусом імунодефіциту людини [ВІЛ]</p>
                            <p>Z20.6 - Контакт з хворим або можливість зараження вірусом імунодефіциту людини [ВІЛ]</p>
                            <p>Z21 - Безсимптомне носійство вірусу імунодефіциту людини [ВІЛ]</p>
                            <p>Z71.7 - Консультації з питань, пов’язаних з вірусом імунодефіциту людини [ВІЛ]</p>
                        </div>
                    </div>

                    <hr class="border-gray-200 dark:border-gray-700">

                    <div class="space-y-3">
                        <p class="font-semibold">
                            {{ __('dictionaries.sensitive_group.services_list_title') }}
                        </p>
                        <div class="space-y-1 text-sm sm:text-base leading-relaxed">
                            <p>96242-01 - Видача антиретровірусних препаратів</p>
                            <p>96242-02 - Видача антиретровірусних препаратів - доконтактна профілактика</p>
                            <p>96242-03 - Видача антиретровірусних препаратів - postконтактна профілактика</p>
                            <p>B33006 - Аналіз: ВІЛ B33008 - Аналіз: аналіз на СНІД B33012 - Аналіз: вірусне навантаження ВІЛ</p>
                        </div>
                    </div>
                </div>
            </template>
        </fieldset>
    </section>

    <x-forms.loading />
    <livewire:components.x-message :key="time()" />
</div>
