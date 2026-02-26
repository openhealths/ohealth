<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Classes\Cipher\Api\CipherRequest;
use App\Classes\Cipher\Exceptions\CipherApiException;
use App\Events\EhealthUserVerified;
use App\Models\LegalEntity;
use App\Models\Relations\Party;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use JsonException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('layouts.guest')]
class VerifyPersonality extends Component
{
    use WithFileUploads;

    #[Validate(['required', 'string'])]
    public string $knedp;

    #[Validate(['required', 'file', 'extensions:dat,pfx,pk8,zs2,jks,p7s'])]
    public ?TemporaryUploadedFile $keyContainerUpload = null;

    #[Validate(['required', 'string'])]
    public string $password;

    public function login(): void
    {
        $this->validate();

        try {
            $response = new CipherRequest()->getPersonalData($this->knedp, $this->keyContainerUpload, $this->password);
        } catch (ConnectionException|CipherApiException $exception) {
            Log::channel('api_errors')->error($exception->getMessage(), ['context' => $exception->getContext()]);
            Session::flash('error', 'Сталася помилка під час завантаження ключа');

            return;
        } catch (JsonException $exception) {
            Log::channel('api_errors')->error($exception->getMessage());
            Session::flash('error', 'Сталася помилка під час завантаження ключа');

            return;
        }

        $ownerFullName = $response?->getOwnerFullName();
        $taxId = $response?->getTaxId();
        [$lastName, $firstName, $secondName] = explode(' ', $ownerFullName);

        /*
         * Search for the Party (person) based on the e-signature data.
         * We no longer check for `whereNull('user_id')` as this column
         * was removed from the 'parties' table during refactoring.
         */
        $party = Party::whereTaxId($taxId)
            ->whereRaw('LOWER(TRIM(last_name)) = ?', [mb_strtolower($lastName)])
            ->whereRaw('LOWER(TRIM(first_name)) = ?', [mb_strtolower($firstName)])
            ->whereRaw('LOWER(TRIM(second_name)) = ?', [mb_strtolower($secondName)])
            ->first();

        if (!$party) {
            Session::flash('error', 'Співпадінь не знайдено, зверніться до адміністратора');

            return;
        }

        $user = Auth::user();

        /*
         * This check (`!$user->partyId`) is crucial for idempotency.
         * It handles scenarios where a user might land on this verification
         * page even after they are already linked to a Party.
         *
         * How can this happen?
         * 1. User verifies successfully, `$user->partyId` is set.
         * 2. They are redirected to the dashboard.
         * 3. They use the browser's "Back" button, which re-loads this page.
         * 4. They (mistakenly) try to submit the form a second time.
         *
         * This `if` block prevents our code from trying to re-link an
         * already-linked user.
         */
        if (!$user->partyId) {
            $user->partyId = $party->id;
            $user->save();
        }

        $legalEntityUuid = Session::pull('selected_legal_entity_uuid');
        $legalEntity = LegalEntity::whereUuid($legalEntityUuid)->firstOrFail();

        $isAlreadyVerified = $party->employees()
            ->whereLegalEntityId($legalEntity->id)
            ->exists();

        if (!$isAlreadyVerified) {
            Session::flash('error', 'Для вашого профілю не знайдено активних посад у цьому закладі. Зверніться до адміністратора.');

            return;
        }

        EhealthUserVerified::dispatch($user, $legalEntity->id);

        $this->redirectRoute('dashboard', ['legalEntity' => $legalEntity], navigate: true);
    }
}
