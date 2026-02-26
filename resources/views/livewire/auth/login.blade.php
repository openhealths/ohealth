@extends('livewire.auth.login-layout')

@section('showPassword')
    <div class="mt-6"
         x-show="isLocalAuth"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
    >
        <div class="form-group group">
            <input wire:model="password"
                   :required="isLocalAuth"
                   type="password"
                   placeholder=" "
                   autocomplete="off"
                   id="password"
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
    </div>

    <div class="block mt-4">
        <div class="form-group group">
            <input x-model="isLocalAuth"
                   type="checkbox"
                   id="is_local_auth"
                   class="default-checkbox text-blue-500 focus:ring-blue-300"
                   :checked="isLocalAuth"
            >

            <label for="is_local_auth" class="ms-2 text-xs font-medium text-gray-500 dark:text-gray-300">
                {{ __('auth.login.no_ehealth_login') }}
            </label>
        </div>
    </div>
@endsection
