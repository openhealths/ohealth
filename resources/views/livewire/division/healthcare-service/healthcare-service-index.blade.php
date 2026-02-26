@use('App\Enums\Status')
@use('App\Enums\JobStatus')
@use('App\Models\HealthcareService')

<div
    x-data="{
         serviceId: 0,
         textConfirmation: '',
         actionType: '',
         actionTitle: '',
         actionButtonText: ''
     }"
>
    <livewire:components.x-message :key="now()->timestamp"/>
    <x-forms.loading/>

    <x-header-navigation x-data="{ showFilter: false }">
        <x-slot name="title">{{ __('forms.services') }}</x-slot>

        <x-slot name="navigation">
            <div class="flex flex-col">
                <div class="flex flex-wrap items-end justify-between gap-4 max-w-6xl">
                    <div class="flex items-end gap-4"></div>

                    <div class="ml-auto flex items-center gap-6 self-start -mt-22 pl-4 sm:pl-0 translate-x-0 sm:translate-x-10">
                        {{-- Show the create button if a division is selected in the filter and has an active status --}}
                        @if(isset($divisionId, $divisionFilter))
                            @php
                                $selectedDivision = collect($divisions)->firstWhere('id', $divisionFilter);
                            @endphp
                            @if($selectedDivision['status'] === Status::ACTIVE->value)
                                @can('create', HealthcareService::class)
                                    <a href="{{ route('healthcare-service.create', [legalEntity(), $divisionId]) }}"
                                       class="button-primary flex items-center gap-2"
                                    >
                                        @icon('plus', 'w-4 h-4')
                                        {{ __('healthcare-services.add') }}
                                    </a>
                                @endcan
                            @endif
                        @endif

                        @can('sync', HealthcareService::class)
                            <button
                                wire:click="{{ !$this->isSync ? 'sync' : '' }}"
                                class="{{ $this->isSync ? 'button-sync-disabled' : 'button-sync' }} flex items-center gap-2 whitespace-nowrap"
                                {{ $this->isSync ? 'disabled' : '' }}
                            >
                                @icon('refresh', 'w-4 h-4')
                                <span>{{ ($syncStatus === JobStatus::PAUSED->value || $syncStatus === JobStatus::FAILED->value) ? __('forms.sync_retry') : __('forms.synchronise_with_eHealth') }}</span>
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        </x-slot>
    </x-header-navigation>

    {{-- Filters --}}
    <div class="shift-content flex flex-wrap items-end justify-between pl-2.5">
        <div class="ml-3.5 flex flex-col gap-4">
            <div class="form-group group">
                <select wire:model="typeFilter"
                        type="text"
                        name="specialityType"
                        id="specialityType"
                        class="input-select"
                >
                    <option value="" selected>{{ __('forms.select') }}</option>
                    @foreach($dictionaries['SPECIALITY_TYPE'] as $key => $specialityType)
                        <option value="{{ $key }}"> {{ $specialityType }}</option>
                    @endforeach
                </select>

                <label for="specialityType" class="label">{{ __('healthcare-services.specialisation') }}</label>
            </div>

            <div class="form-group group">
                <select wire:model="divisionFilter"
                        type="text"
                        name="divisionName"
                        id="divisionName"
                        class="input-select"
                >
                    <option value="" selected>{{ __('forms.select') }}</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division['id'] }}"> {{ $division['name'] }}</option>
                    @endforeach
                </select>

                <label for="divisionName" class="label">{{ __('forms.division_name') }}</label>
            </div>

            <div class="mb-9 mt-4 flex gap-2">
                @can('viewAny', HealthcareService::class)
                    <button wire:click.prevent="search" class="flex items-center gap-2 button-primary">
                        @icon('search', 'w-4 h-4')
                        <span>{{ __('patients.search') }}</span>
                    </button>
                    <button type="button" wire:click="resetFilters" class="button-primary-outline-red">
                        {{ __('forms.reset_all_filters') }}
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <div class="flow-root mt-8 shift-content pl-3.5"
         wire:key="healthcare-services-table-page-{{ $healthcareServices->total() }}-{{ $healthcareServices->currentPage() }}"
    >
        <div class="max-w-screen-xl">
            @if($healthcareServices->isNotEmpty())
                <div class="index-table-wrapper">
                    <table class="index-table">
                        <thead class="index-table-thead">
                        <tr>
                            <th class="index-table-th w-[24%]">{{ __('healthcare-services.specialisation') }}</th>
                            <th class="index-table-th w-[24%]">{{ __('forms.division_name') }}</th>
                            <th class="index-table-th w-[18%]">{{ __('healthcare-services.providing_condition') }}</th>
                            <th class="index-table-th w-[14%]">{{ __('forms.created_at') }}</th>
                            <th class="index-table-th w-[14%]">{{ __('healthcare-services.status') }}</th>
                            <th class="index-table-th w-[6%]">{{ __('forms.action') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($healthcareServices as $service)
                            <tr wire:key="healthcare-service-{{ $service->id }}" class="index-table-tr">
                                <td class="index-table-td-primary">
                                    {{ $dictionaries['SPECIALITY_TYPE'][$service->specialityType] ?? '-' }}
                                </td>

                                <td class="index-table-td">
                                    {{ $service->division->name }}
                                </td>

                                <td class="index-table-td">
                                    {{ $dictionaries['PROVIDING_CONDITION'][$service->providingCondition] ?? '-' }}
                                </td>

                                <td class="index-table-td">
                                    {{ $service->ehealthInsertedAt?->format('d.m.Y') ?? $service->createdAt->format('d.m.Y') }}
                                </td>

                                <td class="index-table-td">
                                    <span class="{{
                                        match($service->status) {
                                            Status::DRAFT => 'badge-dark',
                                            Status::ACTIVE => 'badge-green',
                                            Status::INACTIVE => 'badge-red',
                                            default => ''
                                        }
                                    }}">
                                        {{ $service->status->label() }}
                                    </span>
                                </td>

                                <td class="index-table-td-actions">
                                    @if($service->division->status === Status::ACTIVE)
                                        <div class="flex justify-center relative">
                                            <div x-data="{
                                                     open: false,
                                                     toggle() { this.open ? this.close() : (this.$refs.button.focus(), this.open = true) },
                                                     close(focusAfter) { if (!this.open) return; this.open = false; focusAfter && focusAfter.focus() }
                                                 }"
                                                 @keydown.escape.prevent.stop="close($refs.button)"
                                                 @focusin.window="!$refs.panel.contains($event.target) && close()"
                                                 x-id="['dropdown-button']"
                                                 class="relative"
                                            >
                                                <button @click="toggle()"
                                                        x-ref="button"
                                                        :aria-expanded="open"
                                                        :aria-controls="$id('dropdown-button')"
                                                        type="button"
                                                        class="hover:text-primary cursor-pointer"
                                                >
                                                    @icon('edit-user-outline', 'svg-hover-action w-6 h-6 text-gray-800 dark:text-gray-300')
                                                </button>

                                                <div x-show="open"
                                                     wire:key="dropdown-{{ $service->id }}-{{ $service->status->value }}"
                                                     x-cloak
                                                     x-ref="panel"
                                                     x-transition.origin.top.left
                                                     @click.outside="close($refs.button)"
                                                     :id="$id('dropdown-button')"
                                                     class="absolute right-0 mt-2 w-auto min-w-[10rem] max-w-[20rem] rounded-md bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 shadow-md z-50"
                                                >
                                                    @if ($service->status === Status::ACTIVE)
                                                        @can('view', $service)
                                                            <a href="{{ route('healthcare-service.view', [legalEntity(), $service->division, $service->id]) }}"
                                                               class="flex items-center gap-2 w-full px-4 py-2.5 text-left text-sm text-gray-600 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600"
                                                            >
                                                                @icon('eye', 'w-5 h-5 text-gray-600 dark:text-gray-300')
                                                                {{ __('forms.view') }}
                                                            </a>
                                                        @endcan

                                                        @can('update', $service)
                                                            <a href="{{ route('healthcare-service.update', [legalEntity(), $service->division, $service->id]) }}"
                                                               class="flex items-center gap-2 w-full px-4 py-2.5 text-left text-sm text-gray-600 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600"
                                                            >
                                                                @icon('edit', 'w-5 h-5 text-gray-600 dark:text-gray-300')
                                                                {{ __('forms.update') }}
                                                            </a>
                                                        @endcan

                                                        @can('deactivate', $service)
                                                            <button type="button"
                                                                    wire:key="deactivate-{{ $service->id }}"
                                                                    @click.prevent="
                                                                        serviceId= {{ $service->getKey() }};
                                                                        textConfirmation = @js(__('healthcare-services.modals.deactivate.confirmation_text'));
                                                                        actionType = 'deactivate';
                                                                        actionTitle = @js(__('healthcare-services.modals.deactivate.title'));
                                                                        actionButtonText = @js(__('forms.deactivate'));
                                                                        open = !open;
                                                                    "
                                                                    class="cursor-pointer flex items-center gap-2 w-full last-of-type:rounded-b-md px-4 py-2.5 text-left text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-gray-600"
                                                            >
@icon('delete', 'w-5 h-5 text-red-600 dark:text-red-400')
                                                            {{ __('forms.deactivate') }}
                                                            </button>
                                                        @endcan
                                                    @elseif($service->status === Status::DRAFT)
                                                        @can('edit', $service)
                                                            <a href="{{ route('healthcare-service.edit', [legalEntity(), $service->division->id, $service->id]) }}"
                                                               class="flex items-center gap-2 w-full px-4 py-2.5 text-left text-sm text-gray-600 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600"
                                                            >
                                                                @icon('edit', 'w-5 h-5 text-gray-600 dark:text-gray-300')
                                                                {{ __('healthcare-services.continue') }}
                                                            </a>
                                                        @endcan

                                                        @can('delete', $service)
                                                            <button wire:click="delete({{ $service->getKey() }}); toggle()"
                                                                    @click="openDropdown = false"
                                                                    type="button"
                                                                    class="cursor-pointer text-nowrap text-red-500 dark:text-red-400 flex gap-3 items-center py-2 pl-4 pr-5 hover:bg-gray-50 dark:hover:bg-gray-600"
                                                            >
                                                                @icon('delete', 'w-5 h-5 text-red-600 dark:text-red-400')
                                                                {{ __('healthcare-services.delete') }}
                                                            </button>
                                                        @endcan
                                                    @else
                                                        @can('activate', $service)
                                                            <button type="button"
                                                                    wire:key="activate-{{ $service->id }}"
                                                                    @click.prevent="
                                                                        serviceId= {{ $service->getKey() }};
                                                                        textConfirmation = @js(__('healthcare-services.modals.activate.confirmation_text'));
                                                                        actionType = 'activate';
                                                                        actionTitle = @js(__('healthcare-services.modals.activate.title'));
                                                                        actionButtonText = @js(__('forms.activate'));
                                                                        open = !open;
                                                                    "
                                                                    class="cursor-pointer flex items-center gap-2 w-full first-of-type:rounded-t-md last-of-type:rounded-b-md px-4 py-2.5 text-left text-sm text-green-600 hover:bg-green-50"
                                                            >
                                                                @icon('check-circle', 'w-5 h-5 text-green-600')
                                                                {{ __('forms.activate') }}
                                                            </button>
                                                        @endcan
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <fieldset class="fieldset !mx-auto mt-8 shift-content">
                    <legend class="legend relative -top-5">@icon('nothing-found', 'w-28 h-28')</legend>
                    <div class="p-4 rounded-lg bg-blue-100 flex items-start mb-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 mt-0.5">
                                @icon('alert-circle', 'w-5 h-5 text-blue-500 mr-3 mt-1')
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-blue-800">
                                    {{ __('forms.nothing_found') }}
                                </p>
                                <p class="text-sm text-blue-600">
                                    {{ __('forms.changing_search_parameters') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </fieldset>
            @endif

            <div class="mt-8 pl-3.5 pb-8 lg:pl-8 2xl:pl-5">
                {{ $healthcareServices->links() }}
            </div>
        </div>
    </div>

    <div class="shift-content footer flex flex-start border-stroke px-7 py-2 my-4">
        <a class="button-minor" href="{{ route('division.index', legalEntity()) }}">
            {{ __('forms.back') }}
        </a>
    </div>

    @include('livewire.division.healthcare-service.modal.confirmation-modal')
</div>
