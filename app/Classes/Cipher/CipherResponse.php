<?php

declare(strict_types=1);

namespace App\Classes\Cipher;

use App\Classes\Cipher\Exceptions\CipherApiException;
use Illuminate\Http\Client\Response;

class CipherResponse
{
    public function __construct(
        public Response $response {
            get {
                return $this->response;
            }
        }
    ) {
    }

    public function successful(): bool
    {
        return $this->response->successful();
    }

    /**
     * @throws CipherApiException
     */
    public function throw(): self
    {
        if ($this->response->failed()) {
            $message = $this->response->json('failureCause')
                ?? $this->response->json('message')
                ?? $this->response->json('error.message', 'An unknown Cipher API error occurred.');

            throw new CipherApiException($message, $this->response, $this->response->status());
        }

        return $this;
    }

    public function getTicketUuid(): ?string
    {
        return $this->response->json('ticketUuid');
    }

    public function getOwnerFullName(): ?string
    {
        return $this->response->json('signature.certificateInfo.ownerCertificateInfo.value.ownerFullName.value');
    }

    public function getTaxId(): ?string
    {
        return $this->response->json('signature.certificateInfo.extensionsCertificateInfo.value.personalData.value.drfou.value');
    }

    public function getBase64Data(): string
    {
        return $this->response->json('base64Data');
    }
}
