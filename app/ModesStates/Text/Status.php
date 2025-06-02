<?php

namespace App\ModesStates\Text;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class Status extends State
{
    /**
     * @throws \Spatie\ModelStates\Exceptions\InvalidConfig
     */
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Created::class)
            ->allowTransition(Created::class, Processed::class)
            ->allowTransition(Created::class, Failed::class);
    }
}
