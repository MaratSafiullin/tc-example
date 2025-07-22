<?php

namespace App\Http\ServicesApi\Controllers;

use App\Http\Core\Controllers\ChecksModelStateRules;
use App\Http\Core\Controllers\Controller;
use App\Http\ServicesApi\Resources\SetResource;
use App\Models\Set;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response as ScribeResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[Group('Sets')]
class SetController extends Controller
{
    use ChecksModelStateRules;

    #[Endpoint(title: 'Show set')]
    #[ScribeResponse(status: SymfonyResponse::HTTP_OK)]
    public function show(Set $set): SetResource
    {
        return new SetResource($set);
    }
}
