<?php

namespace App\Http\ServicesApi\Controllers;

use App\Http\Core\Controllers\Controller;
use App\Http\Core\Requests\Keys;
use App\Http\ServicesApi\Requests\ResultsController\StoreRequest;
use App\Models\Set;
use App\Models\Theme;
use App\Models\ThemeText;
use Illuminate\Http\Response;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response as ScribeResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[Group('Results')]
class ResultsController extends Controller
{
    #[Endpoint(title: 'Store text results')]
    #[ScribeResponse(status: SymfonyResponse::HTTP_CREATED)]
    public function store(StoreRequest $request, Set $set): Response
    {
        $data = $request->validated();

        // Get unique theme names from the request
        $themeNames = collect($data[Keys::TEXTS])
            ->pluck(Keys::THEMES)
            ->flatten(1)
            ->pluck(Keys::NAME)
            ->unique()
            ->values();

        // Get existing themes for this set
        $existingThemes = $set->themes()
            ->whereIn('name', $themeNames)
            ->get()
            ->keyBy('name');

        // Create new themes that don't exist
        $newThemes = $themeNames
            ->diff($existingThemes->keys())
            ->map(fn(string $name) => [
                'name'   => $name,
                'set_id' => $set->id,
            ]);

        if ($newThemes->isNotEmpty()) {
            Theme::insert($newThemes->toArray());
        }

        // Get all themes (existing + newly created)
        $allThemes = $set->themes()
            ->whereIn('name', $themeNames)
            ->get()
            ->keyBy('name');

        // Prepare and insert theme_text records
        $themeTextRecords = collect($data[Keys::TEXTS])
            ->flatMap(function ($textData) use ($allThemes) {
                $textId = $textData[Keys::ID];

                return collect($textData[Keys::THEMES])
                    ->unique(Keys::NAME)
                    ->map(function ($themeData) use ($textId, $allThemes) {
                        $theme = $allThemes->get($themeData[Keys::NAME]);

                        return [
                            'text_id'   => $textId,
                            'theme_id'  => $theme->id,
                            'sentiment' => $themeData[Keys::SENTIMENT],
                        ];
                    });
            })
            ->unique(function ($item) {
                return $item['text_id'] . '-' . $item['theme_id'];
            })
            ->values();

        ThemeText::insert($themeTextRecords->toArray());

        return response()->noContent(SymfonyResponse::HTTP_CREATED);
    }
}
