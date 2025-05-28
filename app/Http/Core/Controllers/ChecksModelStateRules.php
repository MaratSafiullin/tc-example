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

        $result = $model->$stateField->$rule();

        if (is_a($result, Response::class)) {
            if ($result->allowed()) {
                return;
            }

            throw new ConflictHttpException($result->message() ?? $defaultErrorMessage);
        }

        if ($result === true) {
            return;
        }

        throw new ConflictHttpException($defaultErrorMessage);
    }
}
