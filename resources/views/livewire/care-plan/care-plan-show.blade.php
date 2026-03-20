@use('App\Livewire\CarePlan\CarePlanShow')

<section class="section-form">
    <x-header-navigation class="breadcrumb-form">
        <x-slot name="title">
            {{ __('care-plan.care_plan_details') }} #{{ $carePlan->requisition ?? $carePlan->id }}
        </x-slot>
    </x-header-navigation>

    <div x-data="{ showSignatureModal: $wire.entangle('showSignatureModal').live }" class="form shift-content" wire:key="{{ time() }}">
        <div class="row align-items-center mb-4">
            <h2 class="title">{{ $carePlan->title }}</h2>
            <span class="badge {{ $carePlan->status === 'ACTIVE' ? 'badge-success' : 'badge-secondary' }} ml-2">
                {{ $carePlan->status }}
            </span>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <strong>{{ __('care-plan.category') }}:</strong> {{ $carePlan->category }}<br>
                <strong>{{ __('forms.start_date') }}:</strong> {{ $carePlan->period_start?->format('d.m.Y') }}<br>
                <strong>{{ __('forms.end_date') }}:</strong> {{ $carePlan->period_end ? $carePlan->period_end->format('d.m.Y') : 'Безтерміново' }}<br>
            </div>
            <div class="col-md-6">
                <strong>{{ __('care-plan.patient') }}:</strong> {{ $carePlan->person?->last_name }} {{ $carePlan->person?->first_name }}<br>
                <strong>{{ __('care-plan.author') }}:</strong> {{ $carePlan->author?->party?->last_name }} {{ $carePlan->author?->party?->first_name }}<br>
                <strong>{{ __('care-plan.description') }}:</strong> {{ $carePlan->description ?? '-' }}<br>
            </div>
        </div>

        <hr>

        <div class="row align-items-center mb-4 mt-4">
            <h3 class="title">{{ __('care-plan.activities') }}</h3>
        </div>

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
                            <td colspan="4" class="text-center">{{ __('care-plan.no_activities') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(in_array($carePlan->status, ['ACTIVE', 'NEW']))
        <div class="mt-6 flex flex-row items-center gap-4 pt-6">
            @if($carePlan->uuid)
            <div class="flex items-center space-x-3">
                <button type="button" @click="$wire.openSignatureModal('complete')" class="button-success">
                    Завершити План (з КЕП)
                </button>
                <button type="button" @click="$wire.openSignatureModal('cancel')" class="button-danger">
                    Скасувати План (з КЕП)
                </button>
            </div>
            @endif
        </div>
        @endif

        @if($showSignatureModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content p-4">
                    <h5 class="modal-title mb-3">Підтвердження дії (КЕП)</h5>
                    <x-forms.textarea 
                        id="statusReason" 
                        name="statusReason" 
                        label="Обґрунтування зміни статусу" 
                        wire:model="statusReason" 
                        class="mb-3" />
                    
                    @include('components.signature-modal', ['method' => 'sign'])
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
        @endif
    </div>

    <x-messages/>
    <x-forms.loading/>
</section>
