@use('App\Models\DeclarationRequest')
@use('App\Livewire\Declaration\DeclarationCreate')

<section class="section-form">
    <x-header-navigation class="breadcrumb-form">
        <x-slot name="title">
            {{ __('declarations.application_for_registration_of_declaration') }} - {{ $patientFullName }}
        </x-slot>
    </x-header-navigation>

    <form class="form shift-content">
        @include('livewire.declaration.parts.main-information')
        @include('livewire.declaration.parts.authentication')

        <div class="flex gap-8">
            <a href="{{ url()->previous() }}" type="submit" class="button-minor">
                {{ __('forms.cancel') }}
            </a>
            @can('create', DeclarationRequest::class)
                @if($this instanceof DeclarationCreate)
                    <button wire:click.prevent="createLocally" type="submit" class="button-primary-outline">
                        {{ __('forms.create_locally') }}
                    </button>
                @endif
                <button wire:click.prevent="create" type="submit" class="button-primary">
                    {{ __('declarations.create_an_application') }}
                </button>
            @endcan
        </div>

        @if($showInformationMessageModal)
            @include('livewire.declaration.modals.information-message')
        @endif

        @if($showAuthModal)
            @include('livewire.declaration.modals.authentication')
        @endif

        @if($showUploadingDocumentsModal)
            @include('livewire.declaration.modals.uploading-documents')
        @endif

        @if($showSignModal)
            @include('livewire.declaration.modals.sign')
        @endif

        <x-signature-modal method="sign" />
    </form>

    <livewire:components.x-message :key="time()" />
    <x-forms.loading />
</section>
