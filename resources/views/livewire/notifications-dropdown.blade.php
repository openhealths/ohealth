<div x-data="{ open: false }" class="relative">
    <button @click="open = !open"
            x-transition
            type="button"
            aria-label="Notifications"
            class="cursor-pointer p-2 mr-1 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
    >
        <div class="relative">
            @icon('bell', 'w-6 h-6')
            @if($this->totalUnreadCount > 0)
                <div class="absolute -bottom-1 -right-2 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-600 border-2 border-white rounded-full dark:border-gray-800">
                    @if($this->totalUnreadCount > 99)
                        99+
                    @else
                        {{ $this->totalUnreadCount }}
                    @endif
                </div>
            @endif
        </div>
    </button>

    {{-- List of notifications --}}
    <div x-show="open"
         x-cloak
         @click.away="open = false"
         class="absolute right-0 mt-2.75 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-lg z-50 overflow-hidden"
         style="width: 320px; max-width: 320px; min-width: 320px;"
    >
        <div class="p-4 space-y-3">
            @forelse($notifications as $notification)
                @php
                    $iconType = $this->getNotificationIconType($notification);
                @endphp
                <div wire:key="notification-{{ $notification->id }}"
                     class="bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 p-3 flex items-start gap-3 relative"
                >
                    <div class="flex-shrink-0 mt-0.5">
                        @if($iconType === 'started')
                            @icon('refresh', 'w-5 h-5 text-blue-600')
                        @elseif($iconType === 'completed')
                            @icon('check', 'w-5 h-5 text-green-600')
                        @elseif($iconType === 'failed')
                            @icon('alert', 'w-5 h-5 text-red-600')
                        @else
                            @icon('alert-circle', 'w-5 h-5 text-gray-600')
                        @endif
                    </div>

                    <div class="flex-1 min-w-0 overflow-hidden">
                        <p class="text-sm text-gray-900 dark:text-white leading-tight break-words">
                            {{ $notification->data['message'] ?? '' }}
                        </p>
                        <small class="block text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </small>
                    </div>

                    <button wire:click="markAsRead('{{ $notification->id }}')"
                            wire:loading.attr="disabled"
                            type="button"
                            class="flex-shrink-0 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            aria-label="Закрити"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @empty
                <div class="px-4 py-8 text-center text-gray-400 dark:text-gray-500">
                    {{ __('forms.empty') }}
                </div>
            @endforelse
        </div>

        {{-- Link to all notifications --}}
        @if($notifications->count() > 0)
            <a href="{{ Route::has('notifications.index') ? route('notifications.index') : '#' }}"
               class="block text-left text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:underline py-2.5 px-4.5"
            >
                Перейти до сповіщень
            </a>
        @endif
    </div>
</div>
