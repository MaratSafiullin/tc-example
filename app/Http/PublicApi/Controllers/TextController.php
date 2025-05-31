<?php

namespace App\Http\PublicApi\Controllers;

use App\Http\Core\Controllers\ChecksModelStateRules;
use App\Http\Core\Controllers\Controller;
use App\Http\PublicApi\Controllers\ResponseExamples\TextControllerExamples;
use App\Http\PublicApi\Resources\TextResource;
use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response as ScribeResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[Group('TC texts')]
class TextController extends Controller
{
    use ChecksModelStateRules;

    #[Endpoint(title: 'List texts in set')]
    #[QueryParam(name: 'per_page', type: 'integer', required: false, example: 10)]
    #[ScribeResponse(content: TextControllerExamples::INDEX, status: SymfonyResponse::HTTP_OK)]
    public function index(Request $request, Set $set): AnonymousResourceCollection
    {
        Gate::authorize('manage', $set);
        $this->checkModelStateRule(fn() => $set->status->canReadContent());

        $page = $set->texts()->with(['themeTexts.theme'])->paginate($request->perPage());

        return TextResource::collection($page);
    }
}
