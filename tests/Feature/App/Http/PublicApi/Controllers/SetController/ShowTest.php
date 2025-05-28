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

#[CoversMethod(SetController::class, 'show')]
class ShowTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itChecksAccess(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $randomSet = Set::factory()->create();

        $this->get(
            URL::route('api.public.sets.show', $randomSet->getRouteKey())
        )->assertForbidden();
    }

    #[Test]
    public function itReturnsSet(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $ownSet    = Set::factory()->usingOwner($user)->create();

        $response = $this->get(
            URL::route('api.public.sets.show', $ownSet->getRouteKey())
        );

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $ownSet->getRouteKey(),
            ],
        ]);
    }
}
