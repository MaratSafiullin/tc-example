<?php

namespace App\ModesStates\Set;

use Illuminate\Auth\Access\Response;

class Processing extends Status
{
    public static string $name = StatusName::Processing->value;

    public function canReadContent(): Response
    {
        return Response::deny('Cannot read content in a set that is being processed.');
    }
}
