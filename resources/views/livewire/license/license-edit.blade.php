<div>
    <x-header-navigation class="breadcrumb-form" title="{{ __('licenses.edit') }}"></x-header-navigation>

    @section('action-buttons')
        <div class="flex justify-start gap-4 mt-10">
            <a href="{{ url()->previous() }}" type="button" class="button-minor">
                {{ __('forms.cancel') }}
            </a>
            <button wire:click="update" type="submit" class="button-primary">
                {{ __('forms.save') }}
            </button>
        </div>
    @endsection

    @include('livewire.license.license')
</div>
