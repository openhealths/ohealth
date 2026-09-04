<?php

declare(strict_types=1);

namespace App\Livewire\Device;

use App\Classes\eHealth\EHealth;
use App\Core\Arr;
use App\Enums\Device\Status;
use App\Enums\JobStatus;
use App\Exceptions\EHealth\EHealthConnectionException;
use App\Exceptions\EHealth\EHealthException;
use App\Jobs\DeviceSync;
use App\Livewire\Person\Records\BasePatientComponent;
use App\Models\Employee\Employee;
use App\Models\LegalEntity;
use App\Models\MedicalEvents\Sql\Device;
use App\Repositories\MedicalEvents\Repository;
use App\Rules\InDictionary;
use App\Traits\BatchLegalEntityQueries;
use App\Traits\HandlesSyncBatch;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Throwable;

class DeviceIndex extends BasePatientComponent
{
    use BatchLegalEntityQueries;
    use HandlesSyncBatch;
    use WithPagination;

    /**
     * Filter dropdown options the user can pick from to narrow the devices search.
     *
     * @var array
     */
    public array $encounters = [];

    public array $episodes = [];

    public array $employees = [];

    /**
     * Bound search filter values applied when querying devices.
     *
     * @var string
     */
    public string $filterName = '';

    public string $filterType = '';

    public string $filterStatus = '';

    public string $filterEncounterId = '';

    public string $filterEpisodeId = '';

    public string $filterDefinition = '';

    public string $filterModelNumber = '';

    public string $filterManufacturer = '';

    public string $filterSerialNumber = '';

    public string $filterRecorder = '';

    public string $filterInsertedAtFrom = '';

    public string $filterInsertedAtTo = '';

    public bool $showAdditionalParams = false;

    public string $syncStatus = '';

    protected array $dictionaryNames = [
        'device_definition_classification_type',
        'device_name_type',
        'device_properties',
        'eHealth/report_origins',
        'eHealth/resources',
        'external_system'
    ];

    protected function getSyncStatus(string $entityType): ?string
    {
        return $this->syncStatus ?: null;
    }

    protected function getBatchName(string $entityType): string
    {
        return DeviceSync::BATCH_NAME;
    }

    protected function getJobClass(string $entityType): string
    {
        return DeviceSync::class;
    }

    protected function getEntityConstant(string $entityType): string
    {
        return LegalEntity::ENTITY_DEVICE;
    }

    protected function onSyncStatusChanged(string $entityType, JobStatus $status): void
    {
        $this->syncStatus = $status->value;
    }

    protected function initializeComponent(): void
    {
        $this->getDictionary();

        $this->syncStatus = legalEntity()->getEntityStatus(LegalEntity::ENTITY_DEVICE) ?? '';

        $this->loadFilterOptions();
    }

    #[Computed]
    public function paginatedDevices(): LengthAwarePaginator
    {
        return $this->isSearching
            ? $this->searchDevicesFromEHealth()
            : $this->paginateLocalDevices();
    }

    public function search(): void
    {
        $this->validate($this->filterValidationRules());

        $this->isSearching = true;
        $this->resetPage();
    }

    public function sync(): void
    {
        if ($this->cannotStartSync('device')) {
            return;
        }

        if ($this->shouldResumeSync('device')) {
            $this->handleResumeLogic('device');

            return;
        }

        try {
            $response = EHealth::device()->getBySearchParams(
                $this->uuid,
                ['recorder_legal_entity_id' => legalEntity()->uuid]
            );
        } catch (EHealthException|EHealthConnectionException $exception) {
            $exception->handle('Error while synchronizing devices');

            return;
        }

        try {
            $validatedData = $response->validate();
            Repository::device()->sync($this->patient(), $validatedData);
        } catch (Throwable $exception) {
            $this->handleDatabaseErrors($exception, 'Error while synchronizing devices');

            return;
        }

        if ($response->isNotLast()) {
            $this->dispatchRemainingPages('device');
        } else {
            legalEntity()->setEntityStatus(JobStatus::COMPLETED, LegalEntity::ENTITY_DEVICE);
            Session::flash('success', __('devices.messages.synced_successfully'));
        }

        $this->loadFilterOptions();

        $this->isSearching = false;
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset([
            'filterName',
            'filterType',
            'filterStatus',
            'filterEncounterId',
            'filterEpisodeId',
            'filterDefinition',
            'filterModelNumber',
            'filterManufacturer',
            'filterSerialNumber',
            'filterRecorder',
            'filterInsertedAtFrom',
            'filterInsertedAtTo',
            'isSearching'
        ]);

        $this->resetPage();
    }

    /**
     * Paginate locally stored (synced) devices straight from the database.
     *
     * @return LengthAwarePaginator
     */
    protected function paginateLocalDevices(): LengthAwarePaginator
    {
        $paginator = Device::forPatient($this->patient())
            ->withAllRelations()
            ->recentlyUpdatedFirst()
            ->paginate(config('pagination.per_page'));

        $paginator->setCollection(collect(Arr::toCamelCase($paginator->getCollection()->toArray())));

        return $paginator;
    }

    /**
     * Fetch a single page of devices from the eHealth API for the active search filters.
     *
     * @return LengthAwarePaginator
     */
    protected function searchDevicesFromEHealth(): LengthAwarePaginator
    {
        $perPage = config('pagination.per_page');
        $page = $this->getPage();

        // Devices are only readable within the legal entity that recorded them, so the scoping param is always sent
        $params = array_merge(
            array_filter([
                'name' => $this->filterName ?: null,
                'type' => $this->filterType ?: null,
                'status' => $this->filterStatus ?: null,
                'encounter_id' => $this->filterEncounterId ?: null,
                'episode_id' => $this->filterEpisodeId ?: null,
                'definition' => $this->filterDefinition ?: null,
                'model_number' => $this->filterModelNumber ?: null,
                'manufacturer' => $this->filterManufacturer ?: null,
                'serial_number' => $this->filterSerialNumber ?: null,
                'recorder' => $this->filterRecorder ?: null,
                'inserted_at_from' => $this->filterInsertedAtFrom ?: null,
                'inserted_at_to' => $this->filterInsertedAtTo ?: null
            ]),
            [
                'recorder_legal_entity_id' => legalEntity()->uuid,
                'page' => $page,
                'page_size' => $perPage
            ]
        );

        try {
            $response = EHealth::device()->getBySearchParams($this->uuid, $params);
            $devices = Arr::toCamelCase($this->formatDatesForDisplay($response->validate()));
            $total = $response->getPaging()['total_entries'];
        } catch (EHealthException|EHealthConnectionException $exception) {
            $exception->handle('Error while loading devices');
            $devices = [];
            $total = 0;
        }

        return new LengthAwarePaginator(collect($devices), $total, $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);
    }

    protected function loadFilterOptions(): void
    {
        $this->episodes = Repository::episode()->getByPersonId($this->patient());
        $this->encounters = Repository::encounter()->getByPersonId($this->patient());

        $this->employees = Employee::whereLegalEntityId(legalEntity()->id)
            ->active()
            ->select(['uuid', 'party_id'])
            ->with('party:id,last_name,first_name,second_name')
            ->get()
            ->map(static fn (Employee $employee): array => [
                'uuid' => $employee->uuid,
                'name' => $employee->fullName
            ])
            ->toArray();
    }

    protected function filterValidationRules(): array
    {
        return [
            'filterName' => ['nullable', 'string', 'max:255'],
            'filterType' => ['nullable', 'string', new InDictionary('device_definition_classification_type')],
            'filterStatus' => ['nullable', Rule::in(Status::values())],
            'filterEncounterId' => ['nullable', 'uuid'],
            'filterEpisodeId' => ['nullable', 'uuid'],
            'filterDefinition' => ['nullable', 'uuid'],
            'filterModelNumber' => ['nullable', 'string', 'max:255'],
            'filterManufacturer' => ['nullable', 'string', 'max:255'],
            'filterSerialNumber' => ['nullable', 'string', 'max:255'],
            'filterRecorder' => ['nullable', 'uuid'],
            'filterInsertedAtFrom' => ['nullable', 'date_format:' . config('app.date_format')],
            'filterInsertedAtTo' => ['nullable', 'date_format:' . config('app.date_format')]
        ];
    }

    public function render(): View
    {
        return view('livewire.device.devices');
    }
}
