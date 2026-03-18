@use('App\Livewire\CarePlan\CarePlanIndex')

<section class="section-form">
    <x-header-navigation x-data="{ showFilter: false }" class="breadcrumb-form">
        <x-slot name="title">
            {{ __('care-plan.care_plan') }}

            @if(isset($carePlan) && $carePlan->number)
                №{{ $carePlan->number }}
            @elseif(isset($this->carePlanNumber))
                №{{ $this->carePlanNumber }}
            @endif
        </x-slot>
    </x-header-navigation>

    {{-- Tabs Container --}}
    <div x-data="{ activeTab: 'info', openDropdown: false, showServiceSearchDrawer: false, showMedicationSearchDrawer: false, showMedicationFormDrawer: false, showMedicalDeviceSearchDrawer: false, showMedicalDeviceFormDrawer: false }" class="form shift-content">
        {{-- Tab Switcher and New Prescription Button - on the same row --}}
        <div class="flex items-center justify-between mt-4 mb-6">
            {{-- Tabs on the left --}}
            <div class="flex">
                <button
                    type="button"
                    @click="activeTab = 'info'"
                    :class="activeTab === 'info'
                        ? 'border-b-2 border-blue-500 text-blue-500'
                        : 'border-b-2 border-gray-200 text-gray-500 hover:text-gray-700 dark:border-gray-700 dark:text-gray-400'"
                    class="px-4 py-2 text-sm font-medium focus:outline-none"
                >
                    {{ __('care-plan.plan_info') }}
                </button>
                <button
                    type="button"
                    @click="activeTab = 'prescriptions'"
                    :class="activeTab === 'prescriptions'
                        ? 'border-b-2 border-blue-500 text-blue-500'
                        : 'border-b-2 border-gray-200 text-gray-500 hover:text-gray-700 dark:border-gray-700 dark:text-gray-400'"
                    class="px-4 py-2 text-sm font-medium focus:outline-none"
                >
                    {{ __('care-plan.prescriptions') }}
                </button>
            </div>

            {{-- New Prescription Dropdown on the right --}}
            <div class="relative" @click.outside="openDropdown = false">
                <button
                    type="button"
                    @click="openDropdown = !openDropdown"
                    class="text-blue-500 hover:text-blue-600 text-sm font-medium"
                >
                    + {{ __('care-plan.new_prescription') }}
                </button>

                <div
                    x-show="openDropdown"
                    x-transition
                    x-cloak
                    class="dropdown-panel absolute right-0 mt-2 w-48"
                >
                    <button type="button" @click="openDropdown = false">
                        {{ __('care-plan.services') }}
                    </button>
                    <button type="button" @click="openDropdown = false">
                        {{ __('care-plan.medications') }}
                    </button>
                    <button type="button" @click="openDropdown = false">
                        {{ __('care-plan.medical_devices') }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Tab Content: Plan Info --}}
        <div x-show="activeTab === 'info'" class="form" wire:key="{{ time() }}">
            @include('livewire.care-plan.parts.doctors')
            @include('livewire.care-plan.parts.patient_data')
            @include('livewire.care-plan.parts.care_plan_data')
            @include('livewire.care-plan.parts.condition_diagnosis')
            @include('livewire.care-plan.parts.supporting_information')
            @include('livewire.care-plan.parts.additional_info', ['context' => 'create'])

            <div class="mt-6 flex flex-row items-center gap-4 pt-6">
                <div class="flex items-center space-x-3">
                    <a href=" " class="button-primary-outline-red">
                        {{ __('Видалити') }}
                    </a>

                    @if(get_class($this) === CarePlanCreate::class)
                        <button type="submit"
                                class="button-primary-outline flex items-center gap-2 px-4 py-2"
                                wire:click="createLocally"
                        >
                            @icon('archive', 'w-4 h-4')
                            {{ __('forms.save') }}
                        </button>
                    @endif

                    <button type="button" wire:click="create" class="button-primary">
                        {{ __('Створити план лікування') }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Tab Content: Prescriptions --}}
        <div x-show="activeTab === 'prescriptions'" x-cloak class="space-y-6">
            {{-- Services Section --}}
            <fieldset class="fieldset">
                <legend class="legend">
                    {{ __('care-plan.services') }}
                </legend>
                <div class="p-4 rounded-lg bg-blue-100 flex items-center gap-3 mb-4">
                    @icon('check-round', 'w-5 h-5 text-blue-500 flex-shrink-0')
                    <p class="text-sm text-blue-700">
                        {{ __('care-plan.no_prescriptions_yet') }}
                    </p>
                </div>
                <button type="button"
                        class="item-add"
                        data-drawer-target="services-drawer-right"
                        data-drawer-show="services-drawer-right"
                        data-drawer-placement="right"
                        data-drawer-body-scrolling="false"
                        aria-controls="services-drawer-right"
                >
                    {{ __('care-plan.add_services') }}
                </button>
            </fieldset>

            {{-- Medications Section --}}
            <fieldset class="fieldset">
                <legend class="legend">
                    {{ __('care-plan.medications') }}
                </legend>
                <div class="p-4 rounded-lg bg-blue-100 flex items-center gap-3 mb-4">
                    @icon('check-round', 'w-5 h-5 text-blue-500 flex-shrink-0')
                    <p class="text-sm text-blue-700">
                        {{ __('care-plan.no_prescriptions_yet') }}
                    </p>
                </div>
                <button type="button"
                        class="item-add"
                        data-drawer-target="medications-drawer-right"
                        data-drawer-show="medications-drawer-right"
                        data-drawer-placement="right"
                        data-drawer-body-scrolling="false"
                        aria-controls="medications-drawer-right"
                >
                    {{ __('care-plan.add_medications') }}
                </button>
            </fieldset>

            {{-- Medical Devices Section --}}
            <fieldset class="fieldset">
                <legend class="legend">
                    {{ __('care-plan.medical_devices') }}
                </legend>
                <div class="p-4 rounded-lg bg-blue-100 flex items-center gap-3 mb-4">
                    @icon('check-round', 'w-5 h-5 text-blue-500 flex-shrink-0')
                    <p class="text-sm text-blue-700">
                        {{ __('care-plan.no_prescriptions_yet') }}
                    </p>
                </div>
                <button type="button"
                        class="item-add"
                        data-drawer-target="medical-devices-drawer-right"
                        data-drawer-show="medical-devices-drawer-right"
                        data-drawer-placement="right"
                        data-drawer-body-scrolling="false"
                        aria-controls="medical-devices-drawer-right"
                >
                    {{ __('care-plan.add_medical_devices') }}
                </button>
            </fieldset>

            {{-- Complete Treatment Plan Button --}}
            <div class="pt-6">
                <button type="button" class="button-primary-outline-red">
                    {{ __('care-plan.complete_care_plan') }}
                </button>
            </div>

            {{-- Drawers --}}
            @include('livewire.care-plan.parts.modals.services-drawer')
            @include('livewire.care-plan.parts.modals.service-search-drawer')
            @include('livewire.care-plan.parts.modals.medications-drawer')
            @include('livewire.care-plan.parts.modals.medication-search-drawer')
            @include('livewire.care-plan.parts.modals.medication-form-drawer')
            @include('livewire.care-plan.parts.modals.medical-devices-drawer')
            @include('livewire.care-plan.parts.modals.medical-device-search-drawer')
            @include('livewire.care-plan.parts.modals.medical-device-form-drawer')
        </div>
    </div>

    <x-messages/>
    <x-forms.loading/>
</section>
