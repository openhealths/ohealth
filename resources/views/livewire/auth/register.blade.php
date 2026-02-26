@php
    $hasEmailError = $errors->has('email');
    $hasPasswordError = $errors->has('password');
    $hasPasswordConfirmationError = $errors->has('passwordConfirmation');
@endphp

<div class="fragment">
    <livewire:components.x-message :key="now()->timestamp"/>

    <x-authentication-card>

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('forms.register') }}
        </h2>

        <!-- ====== Forms Section Start -->
        <form
            wire:submit.prevent="register"
        >
            <div class="form-group group mt-6">
                <input
                    required
                    id="email"
                    type="email"
                    placeholder=" "
                    autocomplete="off"
                    wire:model="email"
                    aria-describedby="{{ $hasEmailError ? 'hasEmailErrorHelp' : '' }}"
                    class="input {{ $hasEmailError  ? 'input-error border-red-500 focus:border-red-500' : ''}} peer"
                />

                @if($hasEmailError)
                    <p id="hasEmailErrorHelp" class="text-error">
                        {{ $errors->first('email') }}
                    </p>
                @endif

                <label for="email" class="label z-10">
                    {{ __('forms.email') }}
                </label>
            </div>

            <div class="form-group group mt-6">
                <input
                    required
                    id="password"
                    type="password"
                    placeholder=" "
                    autocomplete="off"
                    wire:model="password"
                    aria-describedby="{{ $hasPasswordError ? 'hasPasswordErrorHelp' : '' }}"
                    class="input {{ $hasPasswordError ? 'input-error border-red-500 focus:border-red-500' : ''}} peer"
                />

                @if($hasPasswordError)
                    <p id="hasPasswordErrorHelp" class="text-error">
                        {{ $errors->first('password') }}
                    </p>
                @endif

                <label for="password" class="label z-10">
                    {{ __('forms.password') }}
                </label>
            </div>

            <div class="form-group group mt-6">
                <input
                    required
                    type="password"
                    placeholder=" "
                    autocomplete="off"
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

            <div class="flex items-center justify-between mt-12 flex-wrap">
                <div class="mt-1 text-left">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        {{ __('forms.has_account') }}
                        <a
                            wire:navigate
                            href="{{ route('login') }}"
                            class="text-blue-400 hover:text-blue-700 dark:text-blue-600"
                        >
                            {{ __('forms.to_enter') }}
                        </a>
                    </p>
                </div>

                <button
                    type="submit"
                    id="submitButton"
                    class="login-button cursor-pointer"
                >
                    {{ __('forms.register')  }}
                </button>
            </div>
        </form>
        <!-- ====== Forms Section End -->

        <x-forms.loading />

    </x-authentication-card>
</div>
