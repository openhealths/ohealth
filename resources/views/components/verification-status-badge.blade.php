@props([
    'status' => null,
    'prefix' => '',
])

@php
    $badgeClass = '';
    $text = '';

    switch ($status) {
        case 'VERIFIED':
            $badgeClass = 'badge-green';
            $text = __('party_verification.statuses.VERIFIED');
            break;

        case 'NOT_VERIFIED':
            $badgeClass = 'badge-red';
            $text = __('party_verification.statuses.NOT_VERIFIED');
            break;

        case 'VERIFICATION_NEEDED':
            $badgeClass = 'badge-yellow';
            $text = __('party_verification.statuses.VERIFICATION_NEEDED');
            break;

        case 'VERIFICATION_NOT_NEEDED':
            $badgeClass = 'badge-gray';
            $text = __('party_verification.statuses.VERIFICATION_NOT_NEEDED');
            break;

        default:
            if ($status === null || $status === '-') {
                $text = '-';
            } else {
                $badgeClass = 'badge-gray';
                $text = $status;
            }
            break;
    }
@endphp

@if ($text !== '-')
    <span class="{{ $badgeClass }} whitespace-nowrap">
        @if($prefix)
            <span class="opacity-75 mr-1">{{ $prefix }}</span>
        @endif
        {{ $text }}
    </span>
@else
    <span class="text-gray-400">-</span>
@endif
