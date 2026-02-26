<section class="section-form">
    <x-header-navigation class="breadcrumb-form">
        <x-slot name="title">
            {{ __('patients.procedures') }} - {{ $patientFullName }}
        </x-slot>
    </x-header-navigation>

    <form class="form"
          x-data="{
              procedures: $wire.entangle('form.procedures'),
              modalProcedure: new Procedure(),
              showSignatureModal: false
          }"
    >

        @include('livewire.encounter.procedure-parts.main-information', ['context' => 'procedure'])
        @include('livewire.encounter.procedure-parts.additional-information', ['context' => 'procedure'])
        @include('livewire.encounter.procedure-parts.reason-references')
        @include('livewire.encounter.procedure-parts.used-codes')

        <div class="flex gap-8">
            <a href="{{ url()->previous() }}" type="submit" class="button-minor">
                {{ __('forms.back') }}
            </a>

            <button @click.prevent="$wire.save(modalProcedure)" type="submit" class="button-primary-outline">
                {{ __('forms.save') }}
            </button>

            <button @click="showSignatureModal = true"
                    type="button"
                    class="button-primary flex items-center gap-2"
            >
                @icon('key', 'w-5 h-5')
                {{ __('forms.complete_the_interaction_and_sign') }}
                @icon('arrow-right', 'w-5 h-5')
            </button>
        </div>

        <template x-if="showSignatureModal">
            @include('livewire.procedure.modals.signature')
        </template>
    </form>

    <livewire:components.x-message :key="time()" />
    <x-forms.loading />
</section>

<script>
    /**
     * Representation of the user's personal procedure
     */
    class Procedure {
        isReferralAvailable = true;
        referralType = '';
        paperReferral = {
            requesterLegalEntityEdrpou: '',
            requesterLegalEntityName: '',
            serviceRequestDate: ''
        };
        category = {
            coding: [{ system: 'eHealth/procedure_categories', code: '' }],
            text: ''
        };
        code = {
            identifier: {
                type: {
                    coding: [{ system: 'eHealth/resources', code: 'service' }],
                    text: ''
                },
                value: ''
            }
        };
        recordedBy = {
            identifier: {
                type: {
                    coding: [{ system: 'eHealth/resources', code: 'employee' }],
                    text: ''
                }
            }
        };
        division = {
            identifier: {
                type: {
                    coding: [{ system: 'eHealth/resources', code: 'division' }],
                    text: ''
                },
                value: ''
            }
        };
        outcome = {
            coding: [{ system: 'eHealth/procedure_outcomes', code: '' }],
            text: ''
        };
        primarySource = true;
        performer = {
            identifier: {
                type: {
                    coding: [{ system: 'eHealth/resources', code: 'employee' }],
                    text: ''
                }
            }
        };
        reportOrigin = {
            coding: [{ system: 'eHealth/report_origins', code: '' }],
            text: ''
        };
        reasonReferences = [];
        usedCodes = [];

        // Create date
        #now = new Date();
        #endTime = new Date(this.#now.getTime() + 15 * 60 * 1000); // add 15 minutes

        performedPeriodStartDate = this.#now.toISOString().split('T')[0];
        performedPeriodStartTime = this.#now.toLocaleTimeString('uk-UA', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });
        performedPeriodEndDate = this.#endTime.toISOString().split('T')[0];
        performedPeriodEndTime = this.#endTime.toLocaleTimeString('uk-UA', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });

        constructor(obj = null) {
            if (obj) {
                this.procedures = JSON.parse(JSON.stringify(obj.procedures || obj));
            }
        }
    }
</script>
