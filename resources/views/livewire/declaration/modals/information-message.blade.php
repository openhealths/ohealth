<div x-data="{ showInformationMessageModal: $wire.entangle('showInformationMessageModal'), isInformed: true }">
    <template x-teleport="body">
        <div x-show="showInformationMessageModal"
             style="display: none"
             @keydown.escape.prevent.stop="showInformationMessageModal = false"
             role="dialog"
             aria-modal="true"
             class="modal"
        >
            <div x-transition.opacity class="fixed inset-0 bg-black/30"></div>
            <div x-transition @click="showInformationMessageModal = false" class="modal-wrapper">
                <div @click.stop
                     x-trap.noscroll.inert="showInformationMessageModal"
                     class="modal-content w-full max-w-4xl mx-auto"
                >
                    <h2 class="mb-12 text-2xl font-semibold text-gray-900 dark:text-white text-center">
                        {{ __('declarations.confirmation_of_application_for_registration_of_declaration') }}
                    </h2>

                    <ul class="list-disc list-inside mb-8">
                        <p class="default-p">{{ __('declarations.medical_worker_confirmation') }}</p>
                        <li class="default-p pl-2">{{ __('declarations.patient_identified') }}</li>
                        <li class="default-p pl-2">{{ __('declarations.informed_about_data_processing') }}</li>
                        <p class="default-p">{{ __('declarations.patient_memo') }}</p>
                        <p class="default-p">{{ __('declarations.sms_or_documents_note') }}</p>
                        <li class="default-p pl-2">{{ __('declarations.consent_data_processing') }}</li>
                        <li class="default-p pl-2">{{ __('declarations.consent_declaration_submission') }}</li>
                    </ul>

                    {{-- Is signed by patient --}}
                    <div class="form-row">
                        <div class="form-group group">
                            <input x-model="isInformed"
                                   type="checkbox"
                                   name="isInformed"
                                   id="isInformed"
                                   class="default-checkbox"
                            />
                            <label class="default-p" for="isInformed">
                                {{ __('declarations.patient_confirm_information_message') }}
                            </label>
                        </div>
                    </div>

                    {{-- Action buttons --}}
                    <div class="flex justify-center gap-8.5 mt-16">
                        <button type="button" @click="showInformationMessageModal = false" class="button-minor">
                            {{ __('forms.cancel') }}
                        </button>
                        <button wire:click="openApproveModal"
                                type="button"
                                class="button-primary flex items-center gap-2"
                                :disabled="!isInformed"
                        >
                            {{ __('forms.confirm') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
