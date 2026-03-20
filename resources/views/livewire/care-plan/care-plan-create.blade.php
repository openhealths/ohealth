@use('App\Livewire\CarePlan\CarePlanCreate')

<section class="section-form">
    <x-header-navigation x-data="{ showFilter: false }" class="breadcrumb-form">
        <x-slot name="title">
            {{ __('care-plan.new_care_plan') }}
        </x-slot>
    </x-header-navigation>

    <div x-data="{ showSignatureModal: $wire.entangle('showSignatureModal').live }" class="form shift-content" wire:key="{{ time() }}">

        @include('livewire.care-plan.parts.doctors')
        @include('livewire.care-plan.parts.patient_data')
        @include('livewire.care-plan.parts.care_plan_data')
        @include('livewire.care-plan.parts.condition_diagnosis')
        @include('livewire.care-plan.parts.supporting_information')
        @include('livewire.care-plan.parts.additional_info', ['context' => 'create'])

        <div class="mt-6 flex flex-row items-center gap-4 pt-6">
            <div class="flex items-center space-x-3">
                <button type="button"
                        class="button-primary-outline flex items-center gap-2 px-4 py-2"
                        wire:click="save"
                >
                    @icon('archive', 'w-4 h-4')
                    {{ __('forms.save') }}
                </button>

                <button type="button" @click="showSignatureModal = true" class="button-primary">
                    {{ __('forms.sign_with_KEP') }}
                </button>
            </div>
        </div>

        @include('components.signature-modal', ['method' => 'sign'])
    </div>
</section>
