<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LegalEntityCreate
{
    use
        Dispatchable,
        SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public User $authenticatedUser,
        public User $owner,
        public string $password
    ) {}
}
