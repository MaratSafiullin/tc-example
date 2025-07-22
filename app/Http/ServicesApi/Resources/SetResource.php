<?php

namespace App\Http\ServicesApi\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Set $resource
 */
class SetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->resource->getRouteKey(),
            'context_type' => $this->resource->context_type,
            'context'      => $this->resource->context,
        ];
    }
}
