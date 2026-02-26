@props(['status'])

@php
    $color = 'bg-gray-100 text-gray-800'; // Default gray
    $label = '-';

    if ($status) {
        if (method_exists($status, 'color')) {
            $color = $status->color();
        }

        if (method_exists($status, 'label')) {
            $label = $status->label();
        } else {
            $label = $status;
        }
    }
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium $color"]) }}>
    {{ $label }}
</span>
