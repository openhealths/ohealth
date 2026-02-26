@php
    $hasPasswordError = $errors->has('password');
    $hasPasswordConfirmationError = $errors->has('passwordConfirmation');
@endphp

<div class="fragment">
    <livewire:components.x-message :key="now()->timestamp"/>

    <x-authentication-card>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('forms.new_password') }}
        </h2>

        <!-- ====== Forms Section Start -->
        <form
            autocomplete="off"
            wire:keydown.enter="resetPassword"
            class="mb-0"
        >
            <div class="form-group group mt-6">
                <input
                    required
                    type="password"
                    placeholder=" "
                    id="password"
                    wire:model="password"
                    aria-describedby="{{ $hasPasswordError ? 'hasPasswordErrorHelp' : '' }}"
                    class="input {{ $hasPasswordError ? 'input-error border-red-500 focus:border-red-500' : ''}} peer"
                />

                @if($hasPasswordError)
                    <p id="hasPasswordErrorHelp" class="text-error">
                        {{ $errors->first('password') }}
                    </p>
                @endif

                <p id="passwordResetNewOneHelp" class="text-note">
                    {{ __('forms.type_new_password') }}
                </p>

                <label for="password" class="label z-10">
                    {{ __('forms.password') }}
                </label>
            </div>

            <div class="form-group group mt-6">
                <input
                    required
                    type="password"
                    placeholder=" "
                    id="passwordConfirmation"
                    wire:model="passwordConfirmation"
                    aria-describedby="{{ $hasPasswordConfirmationError ? 'hasPasswordConfirmationErrorHelp' : '' }}"
                    class="input {{ $hasPasswordConfirmationError ? 'input-error border-red-500 focus:border-red-500' : ''}} peer"
                />

                @if($hasPasswordConfirmationError)
                    <p id="hasPasswordConfirmationErrorHelp" class="text-error">
                        {{ $errors->first('passwordConfirmation') }}
                    </p>
                @endif

                <label for="passwordConfirmation" class="label z-10">
                    {{ __('forms.password_confirmation') }}
                </label>
            </div>

            <div class="flex items-center mt-8">
                <button
                    type="button"
                    id="submitButton"
                    class="default-button cursor-pointer w-full"
                    wire:click="resetPassword"
                >
                    {{ __('forms.set_new_password')  }}
                </button>
            </div>
        </form>
        <!-- ====== Forms Section End -->

        <x-forms.loading />

    </x-authentication-card>
</div>
