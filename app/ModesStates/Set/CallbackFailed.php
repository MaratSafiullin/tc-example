<?php

namespace App\ModesStates\Set;

use Illuminate\Auth\Access\Response;

class CallbackFailed extends Status
{
    public static string $name = StatusNames::CallbackFailed->value;

    public function canDelete(): Response
    {
        return Response::allow();
    }
}
