<section class="section-form">
    <x-header-navigation class="breadcrumb-form">
        <x-slot name="title">
            {{ __('patients.diagnostic_reports') }} - {{ $patientFullName }}
        </x-slot>
    </x-header-navigation>

    <form class="form"
          x-data="{
              diagnosticReports: $wire.entangle('form.diagnosticReport'),
              modalDiagnosticReport: new DiagnosticReport(),
              diagnosticReportCategoriesDictionary: $wire.dictionaries['eHealth/diagnostic_report_categories'],
              servicesDictionary: $wire.dictionaries['custom/services'],
              showSignatureModal: false
          }"
    >

        @include('livewire.encounter.diagnostic-report-parts.main-information')
        @include('livewire.encounter.diagnostic-report-parts.additional-information', ['context' => 'diagnostic-report'])
        @include('livewire.encounter.parts.observations')

        <div class="flex gap-8">
            <a href="{{ url()->previous() }}" type="submit" class="button-minor">
                {{ __('forms.back') }}
            </a>

            <button @click.prevent="$wire.save(modalDiagnosticReport)" type="submit" class="button-primary-outline">
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
            @include('livewire.diagnostic-report.modals.signature')
        </template>
    </form>

    <livewire:components.x-message :key="time()" />
    <x-forms.loading />
</section>

<script>
    /**
     * Representation of the user's personal diagnostic report.
     */
    class DiagnosticReport {
        category = [
            {
                coding: [{ system: 'eHealth/diagnostic_report_categories', code: '' }],
                text: ''
            }
        ];
        code = {
            identifier: {
                type: {
                    coding: [{ system: 'eHealth/resources', code: 'service' }],
                    text: ''
                },
                value: ''
            }
        };
        isReferralAvailable = false;
        referralType = '';
        basedOn = {
            identifier: {
                type: {
                    coding: [{ system: 'eHealth/resources', code: 'service_request' }],
                    text: ''
                }
            }
        };
        paperReferral = {
            requesterLegalEntityEdrpou: '',
            requesterLegalEntityName: '',
            serviceRequestDate: ''
        };
        conclusionCode = {
            coding: [{ system: 'eHealth/ICD10_AM/condition_codes', code: '' }]
        };
        primarySource = true;
        performer = {
            reference: {
                identifier: {
                    type: {
                        coding: [{ system: 'eHealth/resources', code: 'employee' }]
                    }
                }
            }
        };
        reportOrigin = {
            coding: [{ system: 'eHealth/immunization_report_origins', code: '' }],
            text: ''
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
        resultsInterpreter = {
            reference: {
                identifier: {
                    type: {
                        coding: [{ system: 'eHealth/resources', code: 'employee' }],
                        text: ''
                    },
                    value: ''
                }
            }
        };

        // Create date
        #now = new Date();
        #endTime = new Date(this.#now.getTime() + 15 * 60 * 1000); // add 15 minutes

        issuedDate = this.#now.toISOString().split('T')[0];
        issuedTime = this.#now.toLocaleTimeString('uk-UA', { hour: '2-digit', minute: '2-digit', hour12: false });
        effectivePeriodStartDate = this.#now.toISOString().split('T')[0];
        effectivePeriodStartTime = this.#now.toLocaleTimeString('uk-UA', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });
        effectivePeriodEndDate = this.#endTime.toISOString().split('T')[0];
        effectivePeriodEndTime = this.#endTime.toLocaleTimeString('uk-UA', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });

        constructor(obj = null) {
            if (obj) {
                this.diagnosticReports = JSON.parse(JSON.stringify(obj.diagnosticReports || obj));
            }
        }
    }
</script>
