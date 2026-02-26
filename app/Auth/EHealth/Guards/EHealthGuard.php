<?php

declare(strict_types=1);

namespace App\Auth\EHealth\Guards;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Session\Session;
use App\Auth\EHealth\Services\TokenStorage;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Override;

class EHealthGuard extends SessionGuard
{
    /**
     * @var TokenStorage
     */
    protected TokenStorage $tokenStorage;

    public function __construct(string $name, UserProvider $provider, Session $session, Request $request, TokenStorage $tokenStorage)
    {
        parent::__construct($name, $provider, $session, $request);

        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritDoc}
     *
     * Depends on it's UUID
     *
     * @return Authenticatable|null
     */
    #[Override]
    public function user(): Authenticatable|null
    {
        if (!empty($this->user)) {
            return $this->user;
        }

        if ($this->user && !$this->tokenStorage->hasBearerToken()) {
            $this->logout();

            return null;
        }

        $uuid = $this->session->get($this->getName());

        if ($uuid) {
            $this->user = $this->provider->retrieveById($uuid);
        }

        return $this->user;
    }

    public function getUserUUID(Authenticatable $user): ?string
    {
        return $user->uuid;
    }

    /**
     * {@inheritDoc}
     *
     * Add additional checks for Bearer token presents
     *
     * @param  Authenticatable  $user
     * @param  bool  $remember
     * @return void
     */
    #[Override]
    public function login(Authenticatable $user, $remember = false): void
    {
        if (!$this->tokenStorage->hasBearerToken()) {
            Log::error(__('Bearer token missing in session', [], 'en'));

            throw new Exception(__('Bearer token missing in session'));
        }

        $this->updateSession($this->getUserUUID($user));

        $this->fireLoginEvent($user, $remember);

        $this->setUser($user);
    }

    /**
     * {@inheritDoc}
     *
     * Additionally, clears eHealth token storage.
     */
    #[Override]
    public function logout(): void
    {
        parent::logout();

        $this->tokenStorage->clear();
    }
}
