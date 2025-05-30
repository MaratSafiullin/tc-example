<?php

namespace App\Http\Core\Controllers;

use Closure;
use Illuminate\Auth\Access\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

trait ChecksModelStateRules
{
    private function checkModelStateRule(Closure $check): void
    {
        $defaultErrorMessage = 'This action is not allowed in current state.';

        $result = $check();

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
