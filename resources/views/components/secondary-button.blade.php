<button {{ $attributes->merge(['type' => 'button', 'class' => 'button-minor']) }}>
    {{ $slot }}
</button>
