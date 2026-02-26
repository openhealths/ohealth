@if($showLogo)
    <div class="w-full block xl:w-1/2">
        <x-authentication-card-logo />
    </div>
@endif

<div class="w-full max-w-xl p-6 space-y-8 sm:p-8 bg-white rounded-lg shadow dark:bg-gray-800">
    {{ $slot }}
</div>
