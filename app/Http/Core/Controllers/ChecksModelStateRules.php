<?php

namespace App\Http\Core\Controllers;

use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

trait ChecksModelStateRules
{
    private function checkModelStateRule(Model $model, string $stateField, string $rule): void
    {
        $defaultErrorMessage = 'This action is not allowed in current state.';

        $response = $model->$stateField->$rule();

        if ($response === false) {
            throw new ConflictHttpException($defaultErrorMessage);
        }

        if ($response === true) {
            return;
        }

        if (! is_a($response, Response::class)) {
            throw new ConflictHttpException($defaultErrorMessage);
        }

        if ($response->denied()) {
            throw new ConflictHttpException($response->message() ?? $defaultErrorMessage);
        }
    }
}
