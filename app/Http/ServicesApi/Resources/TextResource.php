<?php

namespace App\Http\ServicesApi\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Text $resource
 */
class TextResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->resource->getRouteKey(),
            'text' => $this->resource->text,
        ];
    }
}
