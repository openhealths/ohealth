@use('App\Livewire\CarePlan\CarePlanShow')

<section class="section-form">
    <x-header-navigation class="breadcrumb-form">
        <x-slot name="title">
            {{ __('care-plan.care_plan_details') }} #{{ $carePlan->requisition ?? $carePlan->id }}
        </x-slot>
    </x-header-navigation>

    <div x-data="{ showSignatureModal: $wire.entangle('showSignatureModal').live }" class="form shift-content" wire:key="{{ time() }}">

        {{-- Plan Header --}}
        <div class="flex items-center gap-3 mb-4">
            <h2 class="title">{{ $carePlan->title }}</h2>
            <span class="badge {{ in_array($carePlan->status, ['ACTIVE', 'active']) ? 'badge-success' : 'badge-secondary' }}">
                {{ $carePlan->status }}
            </span>
        </div>

        {{-- Core Details --}}
        <fieldset class="fieldset">
            <legend class="legend">{{ __('care-plan.care_plan_data') }}</legend>

            <div class="form-row-2">
                <div>
                    <p class="label">{{ __('care-plan.category') }}</p>
                    <p class="value">{{ $carePlan->category ?? '-' }}</p>
                </div>
                <div>
                    <p class="label">{{ __('care-plan.name_care_plan') }}</p>
                    <p class="value">{{ $carePlan->title }}</p>
                </div>
            </div>

            <div class="form-row-2 mt-3">
                <div>
                    <p class="label">{{ __('forms.start_date') }}</p>
                    <p class="value">{{ $carePlan->period_start?->format('d.m.Y') ?? '-' }}</p>
                </div>
                <div>
                    <p class="label">{{ __('forms.end_date') }}</p>
                    <p class="value">{{ $carePlan->period_end ? $carePlan->period_end->format('d.m.Y') : __('care-plan.no_end_date') }}</p>
                </div>
            </div>

            <div class="form-row-2 mt-3">
                <div>
                    <p class="label">{{ __('care-plan.patient') }}</p>
                    <p class="value">{{ $carePlan->person?->last_name }} {{ $carePlan->person?->first_name }}</p>
                </div>
                <div>
                    <p class="label">{{ __('care-plan.author') }}</p>
                    <p class="value">{{ $carePlan->author?->party?->last_name }} {{ $carePlan->author?->party?->first_name }}</p>
                </div>
            </div>

            @if($carePlan->description)
            <div class="mt-3">
                <p class="label">{{ __('care-plan.description') }}</p>
                <p class="value">{{ $carePlan->description }}</p>
            </div>
            @endif
        </fieldset>

        {{-- Activities Table --}}
        <fieldset class="fieldset mt-6">
            <legend class="legend">{{ __('care-plan.activities') }}</legend>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('care-plan.kind') }}</th>
                            <th>{{ __('care-plan.quantity') }}</th>
                            <th>{{ __('forms.start_date') }}</th>
                            <th>{{ __('forms.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($carePlan->activities as $activity)
                            <tr>
                                <td>{{ $activity->kind }}</td>
                                <td>{{ $activity->quantity ?? '-' }}</td>
                                <td>{{ $activity->scheduled_period_start?->format('d.m.Y') }}</td>
                                <td>{{ $activity->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-400">
                                    {{ __('care-plan.no_activities') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </fieldset>

        {{-- Action Buttons for ACTIVE/NEW plans synced with eHealth --}}
        @if(in_array($carePlan->status, ['ACTIVE', 'NEW', 'active', 'new']) && $carePlan->uuid)
            <div class="mt-6 flex flex-row items-center gap-4 pt-6">

                {{-- Status Reason (shown above the modal trigger) --}}
                <x-forms.textarea
                    id="statusReason"
                    name="statusReason"
                    label="{{ __('care-plan.status_reason') }}"
                    wire:model="statusReason"
                    class="flex-1"
                />

                <div class="flex items-center gap-3">
                    <button type="button"
                            class="button-success"
                            @click="$wire.openSignatureModal('complete')">
                        {{ __('care-plan.complete_care_plan') }}
                    </button>

                    <button type="button"
                            class="button-danger"
                            @click="$wire.openSignatureModal('cancel')">
                        {{ __('care-plan.cancel_care_plan') }}
                    </button>
                </div>
            </div>

            @include('components.signature-modal', ['method' => 'sign'])
        @endif
    </div>
</section>
