<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EhealthUserVerified
{
    use Dispatchable;
    use SerializesModels;

    public User $user;
    public int $legalEntityId;

    public function __construct(User $user, int $legalEntityId)
    {
        $this->user = $user;
        $this->legalEntityId = $legalEntityId;
    }
}
