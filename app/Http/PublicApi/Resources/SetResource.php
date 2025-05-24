<?php

namespace App\Http\PublicApi\Resources;

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
            'external_id'  => $this->resource->external_id,
            'owner_id'     => $this->resource->owner_id,
            'name'         => $this->resource->name,
            'status'       => $this->resource->status,
            'context_type' => $this->resource->context_type,
            'context'      => $this->resource->context,
            'callback_url' => $this->resource->callback_url,
        ];
    }
}
