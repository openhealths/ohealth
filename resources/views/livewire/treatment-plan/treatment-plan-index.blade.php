@use('App\Livewire\TreatmentPlan\TreatmentPlanIndex')

<section class="section-form">
    <x-header-navigation x-data="{ showFilter: false }" class="breadcrumb-form">
        <x-slot name="title">
            {{ __('treatment-plan.treatment_plan') }}

            @if(isset($treatmentPlan) && $treatmentPlan->number)
                №{{ $treatmentPlan->number }}
            @elseif(isset($this->treatmentPlanNumber))
                №{{ $this->treatmentPlanNumber }}
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
                    {{ __('treatment-plan.plan_info') }}
                </button>
                <button
                    type="button"
                    @click="activeTab = 'prescriptions'"
                    :class="activeTab === 'prescriptions'
                        ? 'border-b-2 border-blue-500 text-blue-500'
                        : 'border-b-2 border-gray-200 text-gray-500 hover:text-gray-700 dark:border-gray-700 dark:text-gray-400'"
                    class="px-4 py-2 text-sm font-medium focus:outline-none"
                >
                    {{ __('treatment-plan.prescriptions') }}
                </button>
            </div>

            {{-- New Prescription Dropdown on the right --}}
            <div class="relative" @click.outside="openDropdown = false">
                <button
                    type="button"
                    @click="openDropdown = !openDropdown"
                    class="text-blue-500 hover:text-blue-600 text-sm font-medium"
                >
                    + {{ __('treatment-plan.new_prescription') }}
                </button>

                <div
                    x-show="openDropdown"
                    x-transition
                    x-cloak
                    class="dropdown-panel absolute right-0 mt-2 w-48"
                >
                    <button type="button" @click="openDropdown = false">
                        {{ __('treatment-plan.services') }}
                    </button>
                    <button type="button" @click="openDropdown = false">
                        {{ __('treatment-plan.medications') }}
                    </button>
                    <button type="button" @click="openDropdown = false">
                        {{ __('treatment-plan.medical_devices') }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Tab Content: Plan Info --}}
        <div x-show="activeTab === 'info'" class="form" wire:key="{{ time() }}">
            @include('livewire.treatment-plan.parts.doctors')
            @include('livewire.treatment-plan.parts.patient_data')
            @include('livewire.treatment-plan.parts.treatment_plan_data')
            @include('livewire.treatment-plan.parts.condition_diagnosis')
            @include('livewire.treatment-plan.parts.supporting_information')
            @include('livewire.treatment-plan.parts.additional_info', ['context' => 'create'])

            <div class="mt-6 flex flex-row items-center gap-4 pt-6">
                <div class="flex items-center space-x-3">
                    <a href=" " class="button-primary-outline-red">
                        {{ __('Видалити') }}
                    </a>

                    @if(get_class($this) === TreatmentPlanCreate::class)
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
                    {{ __('treatment-plan.services') }}
                </legend>
                <div class="p-4 rounded-lg bg-blue-100 flex items-center gap-3 mb-4">
                    @icon('check-round', 'w-5 h-5 text-blue-500 flex-shrink-0')
                    <p class="text-sm text-blue-700">
                        {{ __('treatment-plan.no_prescriptions_yet') }}
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
                    {{ __('treatment-plan.add_services') }}
                </button>
            </fieldset>

            {{-- Medications Section --}}
            <fieldset class="fieldset">
                <legend class="legend">
                    {{ __('treatment-plan.medications') }}
                </legend>
                <div class="p-4 rounded-lg bg-blue-100 flex items-center gap-3 mb-4">
                    @icon('check-round', 'w-5 h-5 text-blue-500 flex-shrink-0')
                    <p class="text-sm text-blue-700">
                        {{ __('treatment-plan.no_prescriptions_yet') }}
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
                    {{ __('treatment-plan.add_medications') }}
                </button>
            </fieldset>

            {{-- Medical Devices Section --}}
            <fieldset class="fieldset">
                <legend class="legend">
                    {{ __('treatment-plan.medical_devices') }}
                </legend>
                <div class="p-4 rounded-lg bg-blue-100 flex items-center gap-3 mb-4">
                    @icon('check-round', 'w-5 h-5 text-blue-500 flex-shrink-0')
                    <p class="text-sm text-blue-700">
                        {{ __('treatment-plan.no_prescriptions_yet') }}
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
                    {{ __('treatment-plan.add_medical_devices') }}
                </button>
            </fieldset>

            {{-- Complete Treatment Plan Button --}}
            <div class="pt-6">
                <button type="button" class="button-primary-outline-red">
                    {{ __('treatment-plan.complete_treatment_plan') }}
                </button>
            </div>

            {{-- Drawers --}}
            @include('livewire.treatment-plan.parts.modals.services-drawer')
            @include('livewire.treatment-plan.parts.modals.service-search-drawer')
            @include('livewire.treatment-plan.parts.modals.medications-drawer')
            @include('livewire.treatment-plan.parts.modals.medication-search-drawer')
            @include('livewire.treatment-plan.parts.modals.medication-form-drawer')
            @include('livewire.treatment-plan.parts.modals.medical-devices-drawer')
            @include('livewire.treatment-plan.parts.modals.medical-device-search-drawer')
            @include('livewire.treatment-plan.parts.modals.medical-device-form-drawer')
        </div>
    </div>

    <x-messages/>
    <x-forms.loading/>
</section>
