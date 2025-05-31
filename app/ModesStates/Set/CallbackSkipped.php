<?php

namespace App\ModesStates\Set;

use Illuminate\Auth\Access\Response;

class CallbackSkipped extends Status
{
    public static string $name = StatusName::CallbackSkipped->value;

    public function canDelete(): Response
    {
        return Response::allow();
    }
}
