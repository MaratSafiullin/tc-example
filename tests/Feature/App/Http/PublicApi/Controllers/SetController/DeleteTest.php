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

#[CoversMethod(SetController::class, 'delete')]
class DeleteTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itDeletesSet(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $ownSet    = Set::factory()->usingOwner($user)->create();
        $randomSet = Set::factory()->create();

        $response = $this->delete(
            URL::route('api.public.sets.delete', $ownSet->getRouteKey())
        );

        $response->assertNoContent();
        $this->assertDatabaseMissing(Set::class, ['id' => $ownSet->id]);

        $this->delete(
            URL::route('api.public.sets.delete', $randomSet->getRouteKey())
        )->assertForbidden();
    }

    //TODO: Add tests for state rules
}
