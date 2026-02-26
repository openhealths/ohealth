{{-- Medications Drawer --}}
<template x-teleport="body">
    <div id="medications-drawer-right"
         class="fixed top-0 right-0 h-screen pt-20 p-4 overflow-y-auto transition-transform translate-x-full bg-white w-4/5 dark:bg-gray-800"
         style="z-index: 40;"
         tabindex="-1"
         aria-labelledby="medications-drawer-label"
    >
        <h3 class="modal-header" id="medications-drawer-label">
            {{ __('treatment-plan.new_medication_prescription') }}
        </h3>

        {{-- Content --}}
        <form>
            {{-- Program Selection Section --}}
            <fieldset class="fieldset">
                <legend class="legend">
                    {{ __('treatment-plan.program_selection') }}
                </legend>

                <div class="form-row-3">
                    <div class="form-group group">
                        <label for="medication_program" class="label">
                            {{ __('treatment-plan.program') }}*
                        </label>
                        <select id="medication_program"
                                name="medication_program"
                                class="input-select peer"
                        >
                            <option selected value="">{{ __('treatment-plan.prescription_medication') }}</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <div class="mt-6 flex justify-start gap-3">
                <button type="button"
                        class="button-minor"
                        data-drawer-hide="medications-drawer-right"
                        aria-controls="medications-drawer-right"
                >
                    {{ __('forms.cancel') }}
                </button>

                <button type="button"
                        class="button-primary"
                        data-drawer-target="medication-search-drawer-right"
                        data-drawer-show="medication-search-drawer-right"
                        data-drawer-placement="right"
                        aria-controls="medication-search-drawer-right"
                        @click="showMedicationSearchDrawer = true"
                >
                    {{ __('forms.continue') }}
                </button>
            </div>
        </form>
    </div>
</template>
