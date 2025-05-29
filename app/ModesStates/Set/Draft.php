<?php

namespace App\ModesStates\Set;

use Illuminate\Auth\Access\Response;

class Draft extends Status
{
    public static string $name = StatusNames::Draft->value;

    public function canDelete(): Response
    {
        return Response::allow();
    }

    public function canAddContent(): Response
    {
        return Response::allow();
    }
}
