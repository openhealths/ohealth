@use('App\Models\Person\PersonRequest')

<div x-data="{ showLeafletModal: $wire.entangle('showLeafletModal') }">
    <template x-teleport="body">
        <div x-show="showLeafletModal"
             style="display: none"
             @keydown.escape.prevent.stop="showLeafletModal = false"
             role="dialog"
             aria-modal="true"
             class="modal"
        >
            <div x-transition.opacity class="fixed inset-0 bg-black/30"></div>
            <div x-transition @click="showLeafletModal = false" class="modal-wrapper">
                <div @click.stop x-trap.noscroll.inert="showLeafletModal"
                     class="modal-content w-full max-w-4xl mx-auto"
                >
                    <div class="mb-4.5 flex flex-col gap-6 xl:flex-container"
                         x-data="{
                             printContent() {
                                 let printWindow = window.open('', '_blank');
                                 printWindow.document.body.innerHTML = $wire.leafletContent;
                                 printWindow.focus();
                                 printWindow.print();
                             }
                         }"
                    >

                        <div class="mb-4.5 flex flex-col gap-6 xl:flex-container">
                            {!! $leafletContent !!}
                        </div>

                        <button @click="printContent()"
                                class="mb-6 underline font-medium text-sm cursor-pointer dark:text-white"
                        >
                            {{ __('patients.print_leaflet_for_patient') }}
                        </button>

                        <div class="mb-4.5 flex gap-6 xl:flex-row justify-center items-center">
                            <button type="button" class="button-danger" wire:click="reject">
                                {{ __('patients.reject') }}
                            </button>
                            @can('create', PersonRequest::class)
                                <button wire:click="openSignatureModal" type="button" class="button-primary">
                                    {{ __('forms.sign') }}
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
