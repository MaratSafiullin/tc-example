<?php

namespace Tests\Feature\App\Http\PublicApi\Controllers\SetController;

use App\Http\PublicApi\Controllers\SetController;
use App\Models\AccessToken\Ability;
use App\Models\Set;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use Tests\RefreshDatabase;
use Tests\TestCase;

#[CoversMethod(SetController::class, 'showByExternalId')]
class ShowByExternalIdTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itChecksAccess(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        Set::factory()->create(['external_id' => $randomExternalId = 'external-id-random']);

        $this->get(
            URL::route('api.public.sets.show-by-external-id', $randomExternalId)
        )->assertNotFound();
    }

    #[Test]
    public function itReturnsSet(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        Set::factory()->usingOwner($user)->create(['external_id' => $externalId = 'external-id-123']);

        $response = $this->get(
            URL::route('api.public.sets.show-by-external-id', $externalId)
        );

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'external_id' => $externalId,
            ],
        ]);
    }
}
