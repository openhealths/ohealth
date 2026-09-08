<?php

declare(strict_types=1);

namespace App\Livewire\Device;

use App\Classes\eHealth\EHealth;
use App\Exceptions\EHealth\EHealthConnectionException;
use App\Exceptions\EHealth\EHealthException;
use App\Livewire\Person\Records\BasePatientComponent;
use App\Models\LegalEntity;
use App\Models\MedicalEvents\Sql\Device;
use App\Models\Person\Person;
use App\Models\Preperson;
use App\Repositories\MedicalEvents\Repository;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Throwable;

class DeviceView extends BasePatientComponent
{
    /**
     * ID of the device being displayed.
     *
     * @var int
     */
    #[Locked]
    public int $deviceId;

    /**
     * eHealth ID of the device, kept so that a refresh does not have to read the record to find it.
     *
     * @var string
     */
    #[Locked]
    public string $deviceUuid;

    /**
     * Request-scoped memoized device.
     *
     * @var Device|null
     */
    private ?Device $deviceModel = null;

    protected array $dictionaryNames = [
        'device_definition_classification_type',
        'device_name_type',
        'device_properties',
        'eHealth/report_origins',
        'device_status_reasons',
        'external_system'
    ];

    /**
     * Bind the route models and load the device being displayed.
     *
     * @param  LegalEntity  $legalEntity
     * @param  Person|null  $person
     * @param  Preperson|null  $preperson
     * @param  Device|null  $device
     * @return void
     */
    public function mount(
        LegalEntity $legalEntity,
        ?Person $person = null,
        ?Preperson $preperson = null,
        ?Device $device = null
    ): void {
        parent::mount($legalEntity, $person, $preperson);

        $this->getDictionary();

        $this->deviceId = $device->id;
        $this->deviceUuid = $device->uuid;

        $this->device();
    }

    /**
     * Resolve the device being displayed, scoped to the patient so that a device belonging to somebody else
     * is not reachable by its ID. Loaded again on later requests, where Livewire hydrates without mount().
     *
     * @return Device
     */
    protected function device(): Device
    {
        return $this->deviceModel ??= Device::forPatient($this->patient())
            ->withAllRelations()
            ->whereId($this->deviceId)
            ->firstOrFail();
    }

    /**
     * Refresh the device from eHealth, so that the page shows the record as it stands there now.
     *
     * @return void
     */
    public function sync(): void
    {
        try {
            $response = EHealth::device()->getById($this->uuid, $this->deviceUuid);
        } catch (EHealthException|EHealthConnectionException $exception) {
            $exception->handle('Error while synchronizing the device');

            return;
        }

        try {
            Repository::device()->sync($this->patient(), [$response->validate()]);
        } catch (Throwable $exception) {
            $this->handleDatabaseErrors($exception, 'Error while synchronizing the device');

            return;
        }

        // Drop the memoized model so that the page renders what has just been stored
        $this->deviceModel = null;

        Session::flash('success', __('devices.messages.record_synced_successfully'));
    }

    public function render(): View
    {
        return view('livewire.device.device-view')->with(['device' => $this->device()]);
    }
}
