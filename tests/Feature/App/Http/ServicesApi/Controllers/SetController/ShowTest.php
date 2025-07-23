<?php

namespace Tests\Feature\App\Http\ServicesApi\Controllers\SetController;

use App\Http\ServicesApi\Controllers\SetController;
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
    public function itReturnsSet(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::ServicesApi->value]);

        $set = Set::factory()->create();

        $response = $this->get(
            URL::route('api.services.sets.show', $set->getRouteKey())
        );

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $set->getRouteKey(),
            ],
        ]);
    }
}
