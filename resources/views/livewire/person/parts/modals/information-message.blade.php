@use('App\Enums\Person\AuthenticationMethod')

<div x-data="{
    showInformationMessageModal: $wire.entangle('showInformationMessageModal'),
    isInformed: $wire.entangle('form.processDisclosureDataConsent'),
    printContent() {
        let printWindow = window.open('https://ehealth.gov.ua/privacy_patient.html', '_blank');
        printWindow.focus();
    }
}">
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
                <div @click.stop x-trap.noscroll.inert="showInformationMessageModal"
                     class="modal-content w-full max-w-4xl mx-auto"
                >
                    <div class="text-end">
                        <button @click="printContent()"
                                class="mb-6 underline font-medium text-sm cursor-pointer dark:text-white"
                        >
                            {{ __('patients.print_leaflet_for_patient') }}
                        </button>
                    </div>

                    <ul class="list-disc list-inside mb-8">
                        <p class="default-p">Ви, як медичний працівник закладу охорони здоров'я:</p>
                        <li class="default-p pl-2">підтверджуєте, що пацієнта як особу ідентифіковано;</li>
                        <li class="default-p pl-2">підтверджуєте, що повідомили пацієнту або його законному представнику
                            мету та підстави обробки персональних даних.
                        </li>
                        @if($form->person['authenticationMethods'][0]['type'] === AuthenticationMethod::THIRD_PERSON->value)
                            <li class="default-p pl-2">підтверджуєте перевірку повноважень представника пацієнта (у разі
                                надання даних про законного представника).
                            </li>
                        @endif

                        <p class="default-p">ПАМ'ЯТКА ПАЦІЄНТУ</p>
                        @if($form->person['authenticationMethods'][0]['type'] === AuthenticationMethod::THIRD_PERSON->value)
                            <p class="default-p">Надаючи код законний представник пацієнта, від імені пацієнта, для
                                якого створюється запис в електронній системі охорони здоров'я
                            </p>
                        @else
                            <p class="default-p">Надаючи код або документи особа чи її законний представник:</p>
                        @endif
                        <li class="default-p pl-2"> підтверджує, що інформована/ий медичним працівником закладу
                            охорони здоров’я про мету та підстави обробки персональних даних пацієнта,
                            для якого створюється запис в реєстрі пацієнтів Електронної системи охорони здоров'я;
                        </li>
                        <li class="default-p pl-2">надає згоду медичному працівнику закладу охорони здоров’я
                            створити та при необхідності оновити запис про пацієнта у Електронній системі охорони
                            здоров’я від імені особи або її законного представника
                        </li>
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
                                {{ __('patients.informed') }}
                            </label>
                        </div>
                    </div>

                    {{-- Action buttons --}}
                    <div class="flex justify-center gap-8.5 mt-16">
                        <button type="button" @click="showInformationMessageModal = false" class="button-minor">
                            {{ __('forms.cancel') }}
                        </button>
                        <button wire:click="openNewState"
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
