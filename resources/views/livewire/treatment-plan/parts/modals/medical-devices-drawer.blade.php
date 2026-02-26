{{-- Medical Devices Drawer --}}
<template x-teleport="body">
    <div id="medical-devices-drawer-right"
         class="fixed top-0 right-0 h-screen pt-20 p-4 overflow-y-auto transition-transform translate-x-full bg-white w-4/5 dark:bg-gray-800"
         style="z-index: 40;"
         tabindex="-1"
         aria-labelledby="medical-devices-drawer-label"
    >
        <h3 class="modal-header" id="medical-devices-drawer-label">
            {{ __('treatment-plan.new_medical_device_prescription') }}
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
                        <label for="medical_device_program" class="label">
                            {{ __('treatment-plan.program') }}*
                        </label>
                        <select id="medical_device_program"
                                name="medical_device_program"
                                class="input-select peer"
                        >
                            <option selected value="">{{ __('treatment-plan.medical_guarantees_program') }}</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <div class="mt-6 flex justify-start gap-3">
                <button type="button"
                        class="button-minor"
                        data-drawer-hide="medical-devices-drawer-right"
                        aria-controls="medical-devices-drawer-right"
                >
                    {{ __('forms.cancel') }}
                </button>

                <button type="button"
                        class="button-primary"
                        @click="showMedicalDeviceSearchDrawer = true"
                >
                    {{ __('forms.continue') }}
                </button>
            </div>
        </form>
    </div>
</template>
