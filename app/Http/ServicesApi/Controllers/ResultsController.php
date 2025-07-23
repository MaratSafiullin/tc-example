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

        $this->createMissingThemes($data, $set);
        $this->saveResults($set, $data);

        return response()->noContent(SymfonyResponse::HTTP_CREATED);
    }

    private function createMissingThemes(array $tcData, Set $set): void
    {
        $uniqueNewThemeNames = collect($tcData[Keys::TEXTS])
            ->pluck(Keys::THEMES)
            ->flatten(1)
            ->pluck(Keys::NAME)
            ->unique()
            ->values();

        $existingThemes = $set->themes()
            ->whereIn('name', $uniqueNewThemeNames)
            ->get()
            ->keyBy('name');

        $newThemesData = $uniqueNewThemeNames
            ->diff($existingThemes->keys())
            ->map(fn(string $name) => [
                'name'   => $name,
                'set_id' => $set->id,
            ]);

        if ($newThemesData->isNotEmpty()) {
            Theme::insert($newThemesData->toArray());
        }
    }

    private function saveResults(Set $set, array $tcData): void
    {
        /** @var \LaravelIdea\Helper\App\Models\_IH_Theme_C $allThemes */
        $allThemes = $set->themes->keyBy('name');

        $themeTextRecords = collect($tcData[Keys::TEXTS])
            ->flatMap(function (array $textData) use ($allThemes) {
                $textId = $textData[Keys::ID];

                return collect($textData[Keys::THEMES])
                    ->unique(Keys::NAME)
                    ->map(function (array $themeData) use ($textId, $allThemes) {
                        $theme = $allThemes->get($themeData[Keys::NAME]);

                        return [
                            'text_id'   => $textId,
                            'theme_id'  => $theme->id,
                            'sentiment' => $themeData[Keys::SENTIMENT],
                        ];
                    });
            })
            ->values();

        ThemeText::upsert($themeTextRecords->toArray(), ['text_id', 'theme_id'], ['sentiment']);
    }
}
