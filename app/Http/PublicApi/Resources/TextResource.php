<?php

namespace App\Http\PublicApi\Resources;

use App\Models\ThemeText;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

/**
 * @property \App\Models\Text $resource
 */
class TextResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->resource->getRouteKey(),
            'external_id' => $this->resource->external_id,
            'text'        => $this->resource->text,
            'themes'      => $this->themesData(),
        ];
    }

    private function themesData(): Collection
    {
        return $this->resource->themeTexts->map(
            fn(ThemeText $themeText) => [
                'id'        => $themeText->theme->getRouteKey(),
                'name'      => $themeText->theme->name,
                'sentiment' => $themeText->sentiment,
            ]
        );
    }
}
