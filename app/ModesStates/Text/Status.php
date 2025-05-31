<?php

namespace App\ModesStates\Text;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class Status extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Created::class);
    }
}
