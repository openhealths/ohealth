@extends('livewire.auth.login-layout')

@section('showPassword')
    <div class="mt-6"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
    >
        <div class="form-group group pb-5">
            <input wire:model="password"
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
@endsection
