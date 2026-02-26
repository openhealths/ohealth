<div class="fragment">
    <livewire:components.x-message :key="now()->timestamp"/>

    <x-authentication-card>
        <h2 class="text-lg font-medium text-gray-900 text-center dark:text-gray-100">
            {{  __('forms.email_confirmation') }}
        </h2>

        <p class="text-gray-600 text-sm mb-4 text-center">
            {{ __('forms.register_thanks') }}
        </p>

        <p class="text-gray-600 text-sm mb-6 text-center">
            {{ __('forms.resend_note') }}
        </p>

        <div class="flex flex-col gap-4">
            <button
                wire:click="sendVerification"
                class="default-button cursor-pointer"
            >
                {{ __('forms.resend_letter') }}
            </button>
        </div>

        <x-forms.loading />

    </x-authentication-card>
</div>
