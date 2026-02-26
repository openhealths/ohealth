@use('App\Livewire\TreatmentPlan\TreatmentPlanCreate')

<section class="section-form">
    <x-header-navigation x-.data="{ showFilter: false }" class="breadcrumb-form">
        <x-slot name="title">
            {{ __('treatment-plan.new_treatment_plan') }}
        </x-slot>
    </x-header-navigation>

    <div class="form shift-content" wire:key="{{ time() }}">

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

    <x-messages/>
    <x-forms.loading/>
</section>
