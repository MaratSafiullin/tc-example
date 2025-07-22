<?php

namespace App\Http\ServicesApi\Controllers;

use App\Http\Core\Controllers\Controller;
use App\Http\ServicesApi\Resources\TextResource;
use App\Models\Set;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response as ScribeResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[Group('Texts')]
class TextController extends Controller
{
    #[Endpoint(title: 'List texts')]
    #[ScribeResponse(status: SymfonyResponse::HTTP_OK)]
    public function index(Set $set): AnonymousResourceCollection
    {
        return TextResource::collection($set->texts);
    }
}