@use('App\Livewire\Equipment\EquipmentCreate')

<section class="section-form">
    <x-header-navigation x-data="{ showFilter: false }">
        <x-slot name="title">{{ __('equipments.new') }}</x-slot>
    </x-header-navigation>

    <div class="form shift-content" wire:key="{{ time() }}">

        @include('livewire.equipment.parts.main-data')
        @include('livewire.equipment.parts.additional-data', ['context' => 'create'])

        <div class="mt-6 flex flex-row items-center gap-4 pt-6">
            <div class="flex items-center space-x-3">
                <a href="{{ route('equipment.index', legalEntity()) }}" class="button-minor">
                    {{ __('forms.cancel') }}
                </a>

                @if(get_class($this) === EquipmentCreate::class)
                    <button type="submit"
                            class="button-primary-outline flex items-center gap-2 px-4 py-2"
                            wire:click="createLocally"
                    >
                        @icon('archive', 'w-4 h-4')
                        {{ __('forms.save') }}
                    </button>
                @endif

                <button type="button" wire:click="create" class="button-primary">
                    {{ __('forms.save_and_send') }}
                </button>
            </div>
        </div>
    </div>

    <livewire:components.x-message :key="time()" />
    <x-forms.loading />
</section>
