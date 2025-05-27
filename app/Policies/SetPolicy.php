<?php

namespace App\Policies;

use App\Models\Set;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SetPolicy
{
    public const MANAGE = 'manage';

    public function manage(User $user, Set $set): Response
    {
        return $set->owner_id === $user->id
            ? Response::allow()
            : Response::deny();
    }
}
