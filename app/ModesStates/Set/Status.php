<?php

namespace App\ModesStates\Set;

use App\ModesStates\Set\Transitions\ToProcessing;
use Illuminate\Auth\Access\Response;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class Status extends State
{
    private const ADD_CONTENT_ERROR_MESSAGE = 'Content can be added only to a set in draft status.';

    /**
     * @throws \Spatie\ModelStates\Exceptions\InvalidConfig
     */
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Draft::class)
            ->allowTransition([Draft::class, Processing::class], Processing::class, ToProcessing::class)
            ->allowTransition(Processing::class, Completed::class);
    }

    public function canDelete(): Response
    {
        return Response::deny();
    }

    public function canReadContent(): Response
    {
        return Response::allow();
    }

    public function canAddThemes(int $count): Response
    {
        return Response::deny(self::ADD_CONTENT_ERROR_MESSAGE);
    }

    public function canAddTexts(int $count): Response
    {
        return Response::deny(self::ADD_CONTENT_ERROR_MESSAGE);
    }

    public function canStartProcessing(): Response
    {
        return Response::deny();
    }
}
