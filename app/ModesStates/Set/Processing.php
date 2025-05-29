<?php

namespace App\ModesStates\Set;

use Illuminate\Auth\Access\Response;

class Processing extends Status
{
    public static string $name = StatusNames::Processing->value;

    public function canReadContent(): Response
    {
        return Response::deny();
    }
}
