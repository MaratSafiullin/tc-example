<?php

namespace App\Http\PublicApi\Controllers;

use App\Http\Core\Controllers\ChecksModelStateRules;
use App\Http\Core\Controllers\Controller;
use App\Http\Core\Requests\Keys;
use App\Http\PublicApi\Controllers\ResponseExamples\TextControllerExamples;
use App\Http\PublicApi\Requests\TextController\StoreRequest;
use App\Http\PublicApi\Resources\TextResource;
use App\Models\Set;
use App\Models\Text;
use App\ModesStates\Text\Created;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
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

    #[Endpoint(title: 'Store texts')]
    #[ScribeResponse(status: SymfonyResponse::HTTP_NO_CONTENT)]
    public function store(StoreRequest $request, Set $set): Response
    {
        Gate::authorize('manage', $set);
        $texts = $request->validated(Keys::TEXTS);
        $this->checkModelStateRule(fn() => $set->status->canAddTexts(count($texts)));

        $data = collect($texts)->map(
            fn(array $text) => [
                'set_id'      => $set->id,
                'status'      => Created::$name,
                'external_id' => $text['external_id'] ?? null,
                'text'        => $text['text'],
            ]
        );
        Text::insert($data->toArray());

        return response(null, SymfonyResponse::HTTP_CREATED);
    }
}
