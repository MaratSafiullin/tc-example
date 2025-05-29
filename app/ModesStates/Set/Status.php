<?php

namespace App\ModesStates\Set;

use Illuminate\Auth\Access\Response;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class Status extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Draft::class);
    }

    public function canDelete(): Response
    {
        return Response::deny();
    }

    public function canReadContent(): Response
    {
        return Response::allow();
    }
}
