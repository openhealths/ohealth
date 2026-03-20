<?php

declare(strict_types=1);

namespace App\Livewire\CarePlan;

use App\Classes\eHealth\EHealth;
use App\Core\Arr;
use App\Exceptions\EHealth\EHealthResponseException;
use App\Exceptions\EHealth\EHealthValidationException;
use App\Models\CarePlan;
use App\Repositories\CarePlanRepository;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class CarePlanShow extends Component
{
    use WithFileUploads;

    public CarePlan $carePlan;

    public bool $showSignatureModal = false;
    public string $actionType = ''; // 'cancel' or 'complete'
    public string $statusReason = ''; // Used when cancelling or completing

    // KEP signature fields
    public string $knedp = '';
    public $keyContainerUpload = null;
    public string $password = '';

    public function mount(CarePlanRepository $repository, int $carePlan): void
    {
        $plan = $repository->findById($carePlan);
        if (!$plan) {
            abort(404, 'Care Plan not found');
        }
        $this->carePlan = $plan;
    }

    protected function rulesForSigning(): array
    {
        return [
            'statusReason'       => 'required|string',
            'knedp'              => 'required|string',
            'keyContainerUpload' => 'required|file|max:1024',
            'password'           => 'required|string',
        ];
    }

    public function openSignatureModal(string $actionType): void
    {
        $this->actionType = $actionType;
        $this->statusReason = ''; // Reset reason
        $this->showSignatureModal = true;
    }

    public function sign(CarePlanRepository $repository): void
    {
        try {
            $validated = $this->validate($this->rulesForSigning());
        } catch (ValidationException $exception) {
            Session::flash('error', $exception->validator->errors()->first());
            $this->setErrorBag($exception->validator->getMessageBag());
            $this->showSignatureModal = false;
            return;
        }

        if (empty($this->carePlan->uuid)) {
            Session::flash('error', 'Цей план лікування ще не синхронізовано з ЕСОЗ.');
            $this->showSignatureModal = false;
            return;
        }

        // Action-specific payload
        $statusMap = [
            'cancel' => 'entered_in_error', // or cancelled, depends on exact spec constraints
            'complete' => 'completed',
        ];

        $payload = [
            'status' => $statusMap[$this->actionType] ?? 'cancelled',
            'status_reason' => $this->statusReason,
        ];

        try {
            $signedContent = signatureService()->signData(
                Arr::toSnakeCase($payload),
                $this->password,
                $this->knedp,
                $this->keyContainerUpload,
                Auth::user()->party->taxId
            );

            // Send to eHealth based on action type
            $apiMethod = $this->actionType === 'complete' ? 'complete' : 'cancel';
            
            $eHealthResponse = EHealth::carePlan()->{$apiMethod}(
                $this->carePlan->uuid,
                [
                    'signed_content'          => $signedContent,
                    'signed_content_encoding' => 'base64',
                ]
            );

            $responseData = $eHealthResponse->getData();

            // Update local state
            $repository->updateById($this->carePlan->id, [
                'status' => $responseData['status'] ?? $payload['status'],
            ]);

            $this->carePlan->refresh();

            Session::flash('success', 'План лікування успішно оновлено в ЕСОЗ.');
            $this->showSignatureModal = false;

        } catch (ConnectionException $exception) {
            Log::error('CarePlanShow: connection error: ' . $exception->getMessage());
            Session::flash('error', "Виникла помилка. Відсутній зв'язок із ЕСОЗ.");
            $this->showSignatureModal = false;
        } catch (EHealthValidationException|EHealthResponseException $exception) {
            Log::error('CarePlanShow: eHealth error: ' . $exception->getMessage());
            $msg = $exception instanceof EHealthValidationException
                ? $exception->getFormattedMessage()
                : 'Помилка від ЕСОЗ: ' . $exception->getMessage();
            Session::flash('error', $msg);
            $this->showSignatureModal = false;
        } catch (\Throwable $exception) {
            Log::error('CarePlanShow: unexpected error: ' . $exception->getMessage());
            Session::flash('error', 'Виникла помилка. Зверніться до адміністратора.');
            $this->showSignatureModal = false;
        }
    }

    public function render()
    {
        return view('livewire.care-plan.care-plan-show');
    }
}
