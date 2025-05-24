<?php

namespace App\Http\PublicApi\Controllers;

use App\Http\Core\Controllers\Controller;
use App\Http\PublicApi\Controllers\ResponseExamples\SetControllerExamples;
use App\Http\PublicApi\Resources\SetResource;
use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response as ScribeResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[Group('Sets')]
class SetController extends Controller
{
    #[Endpoint(title: 'List sets')]
    #[QueryParam(name: 'per_page', type: 'integer', required: false, example: 10)]
    #[ScribeResponse(content: SetControllerExamples::INDEX, status: SymfonyResponse::HTTP_OK)]
    public function index(Request $request): AnonymousResourceCollection
    {
        $page = Set::where('owner_id', auth()->id())->paginate($request->perPage());

        return SetResource::collection($page);
    }
}
