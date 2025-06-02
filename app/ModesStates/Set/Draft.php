<?php

namespace App\ModesStates\Set;

use Illuminate\Auth\Access\Response;

class Draft extends Status
{
    public static string $name = StatusName::Draft->value;

    public function canDelete(): Response
    {
        return Response::allow();
    }

    public function canAddThemes(int $count): Response
    {
        /** @var \App\Models\Set $set */
        $set      = $this->getModel();
        $maxCount = config('tc.max_themes_count_per_set');
        if (($set->themes()->count() + $count) > $maxCount) {
            return Response::deny("Maximum number of themes in a set exceeded ($maxCount).");
        }

        return Response::allow();
    }

    public function canAddTexts(int $count): Response
    {
        /** @var \App\Models\Set $set */
        $set      = $this->getModel();
        $maxCount = config('tc.max_texts_count_per_set');
        if (($set->texts()->count() + $count) > $maxCount) {
            return Response::deny("Maximum number of texts in a set exceeded ($maxCount).");
        }

        return Response::allow();
    }

    public function canStartProcessing(): Response
    {
        return Response::allow();
    }
}
