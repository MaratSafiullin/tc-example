<?php

namespace App\Http\PublicApi\Controllers;

use App\Http\Core\Controllers\ChecksModelStateRules;
use App\Http\Core\Controllers\Controller;
use App\Http\Core\Request\Keys;
use App\Http\PublicApi\Controllers\ResponseExamples\ThemeControllerExamples;
use App\Http\PublicApi\Request\ThemeController\StoreRequest;
use App\Http\PublicApi\Resources\ThemeResource;
use App\Models\Set;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response as ScribeResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[Group('Themes')]
class ThemeController extends Controller
{
    use ChecksModelStateRules;

    #[Endpoint(title: 'List themes in set')]
    #[QueryParam(name: 'per_page', type: 'integer', required: false, example: 10)]
    #[ScribeResponse(content: ThemeControllerExamples::INDEX, status: SymfonyResponse::HTTP_OK)]
    public function index(Request $request, Set $set): AnonymousResourceCollection
    {
        Gate::authorize('manage', $set);
        $this->checkModelStateRule($set, 'status', 'canReadContent');

        $page = $set->themes()->paginate($request->perPage());

        return ThemeResource::collection($page);
    }

    #[Endpoint(title: 'Store themes (bulk)')]
    #[ScribeResponse(status: SymfonyResponse::HTTP_NO_CONTENT)]
    public function store(StoreRequest $request, Set $set): Response
    {
        Gate::authorize('manage', $set);
        $this->checkModelStateRule($set, 'status', 'canAddContent');
        //TODO add max allowed themes in set check

        $data = collect($request->validated(Keys::THEMES))->map(
            fn(array $themes) => [
                'set_id' => $set->id,
                'name'   => $themes['name'],
            ]
        );
        Theme::insert($data->toArray());

        return response(null, SymfonyResponse::HTTP_CREATED);
    }
}
