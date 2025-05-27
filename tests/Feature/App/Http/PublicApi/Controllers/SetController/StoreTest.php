<?php

namespace Tests\Feature\App\Http\PublicApi\Controllers\SetController;

use App\Http\PublicApi\Controllers\SetController;
use App\Models\AccessToken\Ability;
use App\Models\Set;
use App\Models\Set\ContextType;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use Tests\RefreshDatabase;
use Tests\TestCase;

#[CoversMethod(SetController::class, 'store')]
class StoreTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itCreatesSet(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $response = $this->post(
            URL::route('api.public.sets.store'),
            [
                'name'         => $name = 'name',
                'external_id'  => $externalId = 'external_id',
                'callback_url' => $callbackURL = 'https://callback.url',
                'context'      => $context = 'context',
                'context_type' => $contextType = ContextType::QuestionAnswer->value,
            ]
        );

        $response->assertCreated();
        $this->assertDatabaseHas(Set::class, [
            'external_id'  => $externalId,
            'owner_id'     => $user->id,
            'name'         => $name,
            'status'       => Set\Status::Draft,
            'callback_url' => $callbackURL,
            'context'      => $context,
            'context_type' => $contextType,
        ]);
    }
}
