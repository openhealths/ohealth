<?php

declare(strict_types=1);

namespace App\Livewire\License;

use App\Classes\eHealth\EHealth;
use App\Enums\JobStatus;
use App\Exceptions\EHealth\EHealthResponseException;
use App\Exceptions\EHealth\EHealthValidationException;
use App\Models\LegalEntity;
use App\Models\License;
use App\Traits\FormTrait;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class LicenseIndex extends Component
{
    use FormTrait;
    use WithPagination;

    public function mount(LegalEntity $legalEntity): void
    {
    }

    #[Computed]
    public function licenses(): LengthAwarePaginator
    {
        $query = License::whereLegalEntityId(legalEntity()->id)
            ->select(['id', 'legal_entity_id', 'type', 'active_from_date', 'expiry_date', 'what_licensed', 'is_primary']);

        return $query->paginate(config('pagination.per_page'));
    }

    /**
     * Synchronize licenses with eHealth, the method overrides existing licenses if uuid is the same
     *
     * @return void
     */
    public function sync(): void
    {
        try {
            $response = EHealth::license()->getMany();
        } catch (ConnectionException $exception) {
            $this->logConnectionError($exception, 'Error connecting when getting licenses');
            Session::flash('error', "Виникла помилка. Відсутній зв'язок із ЕСОЗ");

            return;
        } catch (EHealthValidationException|EHealthResponseException $exception) {
            $this->logEHealthException($exception, 'Error when getting licenses');
            Session::flash('error', 'Виникла помилка. Зверніться до адміністратора.');

            return;
        }

        $licences = $this->normalizeDate($response->validate());

        try {
            License::upsert($response->map($licences), uniqueBy: ['uuid'], update: new License()->getFillable());
        } catch (Exception $exception) {
            Session::flash('error', 'Виникла помилка. Зверніться до адміністратора.');
            $this->logDatabaseErrors($exception, 'Error while synchronizing licenses with eHealth: ');

            return;
        }

        legalEntity()?->setEntityStatus(JobStatus::COMPLETED, LegalEntity::ENTITY_LICENSE);

        Session::flash('success', __('licenses.success.sync'));
    }

    public function render(): View
    {
        return view('livewire.license.license-index', ['licenses' => $this->licenses]);
    }
}
