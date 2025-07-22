<?php

namespace App\Http\PublicApi\Controllers;

use App\Http\Core\Controllers\ChecksModelStateRules;
use App\Http\Core\Controllers\Controller;
use App\Http\PublicApi\Controllers\ResponseExamples\SetControllerExamples;
use App\Http\PublicApi\Requests\SetController\StoreRequest;
use App\Http\PublicApi\Resources\SetResource;
use App\Models\Set;
use App\ModesStates\Set\Processing;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response as ScribeResponse;
use Knuckles\Scribe\Attributes\UrlParam;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

#[Group('Sets')]
class SetController extends Controller
{
    use ChecksModelStateRules;

    #[Endpoint(title: 'List sets')]
    #[QueryParam(name: 'per_page', type: 'integer', required: false, example: 10)]
    #[ScribeResponse(content: SetControllerExamples::INDEX, status: SymfonyResponse::HTTP_OK)]
    public function index(Request $request): AnonymousResourceCollection
    {
        $page = auth()->user()->sets()->orderBy('id', 'DESC')->paginate($request->perPage());

        return SetResource::collection($page);
    }

    #[Endpoint(title: 'Store set')]
    #[ScribeResponse(content: SetControllerExamples::STORE, status: SymfonyResponse::HTTP_CREATED)]
    public function store(StoreRequest $request): SetResource
    {
        $set = Set::create(
            array_merge(
                $request->validated(),
                [
                    'owner_id' => auth()->id(),
                ]
            )
        );

        return new SetResource($set);
    }

    #[Endpoint(title: 'Show set')]
    #[ScribeResponse(content: SetControllerExamples::SHOW, status: SymfonyResponse::HTTP_OK)]
    public function show(Set $set): SetResource
    {
        Gate::authorize('manage', $set);

        return new SetResource($set);
    }

    #[Endpoint(title: 'Show set by external ID')]
    #[UrlParam(name: 'external_id', type: 'string', description: 'ID defined by client', example: 'external_id')]
    #[ScribeResponse(content: SetControllerExamples::SHOW, status: SymfonyResponse::HTTP_OK)]
    public function showByExternalId(string $external_id): SetResource
    {
        $set = auth()->user()->sets()->where('external_id', $external_id)->firstOrFail();
        Gate::authorize('manage', $set); //excessive in this case, but consistent and accounts for rules change

        return new SetResource($set);
    }

    #[Endpoint(title: 'Delete set')]
    #[ScribeResponse(status: SymfonyResponse::HTTP_NO_CONTENT)]
    public function delete(Set $set): Response
    {
        Gate::authorize('manage', $set);

        $this->checkModelStateRule(fn() => $set->status->canDelete());

        $set->delete();

        return response()->noContent();
    }

    /**
     * @throws \Spatie\ModelStates\Exceptions\CouldNotPerformTransition
     */
    #[Endpoint(title: 'Starts TC process')]
    #[ScribeResponse(content: 'Empty response', status: SymfonyResponse::HTTP_ACCEPTED)]
    public function startTc(Set $set): Response
    {
        Gate::authorize('manage', $set);

        $this->checkModelStateRule(fn() => $set->status->canStartProcessing());

        $set->status->transitionTo(Processing::class);

        return response()->noContent()->setStatusCode(SymfonyResponse::HTTP_ACCEPTED);
    }
}
