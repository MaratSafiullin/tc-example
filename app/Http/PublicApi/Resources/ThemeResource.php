<?php

namespace App\Http\PublicApi\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Theme $resource
 */
class ThemeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->resource->getRouteKey(),
            'name'         => $this->resource->name,
        ];
    }
}
