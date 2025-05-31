<?php

namespace App\ModesStates\Set;

use Illuminate\Auth\Access\Response;

class CallbackSent extends Status
{
    public static string $name = StatusName::CallbackSent->value;

    public function canDelete(): Response
    {
        return Response::allow();
    }
}
