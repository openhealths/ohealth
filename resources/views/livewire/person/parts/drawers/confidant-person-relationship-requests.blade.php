<div class="mt-8">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            {{ __('patients.confidant_relationship_requests') }}
        </h3>
        <button wire:click.prevent="syncConfidantPersonRelationshipRequestsList" type="button" class="button-sync">
            @icon('refresh', 'w-4 h-4 mr-2')
            {{ __('patients.sync_requests') }}
        </button>
    </div>

    <table class="table-input w-full">
        <thead class="thead-input">
        <tr>
            <th scope="col" class="th-input">{{ __('ID') }}</th>
            <th scope="col" class="th-input">{{ __('forms.status.label') }}</th>
            <th scope="col" class="th-input">{{ __('forms.action') }}</th>
            <th scope="col" class="th-input">{{ __('patients.channel') }}</th>
            <th scope="col" class="th-input text-center">{{ __('forms.action') }}</th>
        </tr>
        </thead>
        <tbody>
        @if($this->confidantPersonRelationshipRequests->isNotEmpty())
            @foreach($this->confidantPersonRelationshipRequests as $index => $request)
                <tr>
                    <td class="td-input text-sm text-gray-600 dark:text-gray-400">{{ $request->uuid }}</td>
                    <td class="td-input">
                        <span class="text-gray-700 dark:text-gray-300">
                            {{ $request->status->label() }}
                        </span>
                    </td>
                    <td class="td-input text-gray-700 dark:text-gray-300">
                        {{ $request->action === 'INSERT' ? __('patients.activate_relationship') : __('patients.deactivate_relationship') }}
                    </td>
                    <td class="td-input text-gray-700 dark:text-gray-300">
                        {{ $request->channel === 'MIS' ? __('patients.mis_system') : $request->channel }}
                    </td>
                    <td class="td-input text-center">
                        <div class="relative"
                             x-data="{ openRequestDropdown: false }"
                             @click.outside="openRequestDropdown = false"
                        >
                            <button @click="openRequestDropdown = !openRequestDropdown"
                                    type="button"
                                    class="cursor-pointer p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            >
                                @icon('edit-user-outline', 'w-6 h-6 text-gray-800 dark:text-gray-200')
                            </button>

                            <div x-show="openRequestDropdown"
                                 x-transition
                                 x-cloak
                                 class="absolute right-0 z-10 w-44 bg-white rounded shadow-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600"
                            >
                                <div class="py-1">
                                    <button type="button"
                                            class="flex items-center gap-2 w-full px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200"
                                            @click="openRequestDropdown = false"
                                    >
                                        {{ __('forms.confirm') }}
                                    </button>
                                    <button type="button"
                                            class="flex items-center gap-2 w-full px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-600 text-red-600 dark:text-red-400"
                                            wire:click.prevent="deactivateConfidantPersonRelationshipRequest('{{ $request->uuid }}')"
                                    >
                                        {{ __('patients.cancel_request') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
