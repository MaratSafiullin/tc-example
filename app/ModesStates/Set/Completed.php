<?php

namespace App\ModesStates\Set;

use Illuminate\Auth\Access\Response;

class Completed extends Status
{
    public static string $name = StatusName::Completed->value;

    public function canDelete(): Response
    {
        return Response::allow();
    }
}
