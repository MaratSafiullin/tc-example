<?php

namespace Tests\Feature\App\Http\PublicApi\Controllers\SetController;

use App\Http\PublicApi\Controllers\SetController;
use App\Models\AccessToken\Ability;
use App\Models\Set;
use App\Models\User;
use App\ModesStates\Set\Draft;
use App\ModesStates\Set\Processing;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RefreshDatabase;
use Tests\TestCase;

#[CoversMethod(SetController::class, 'delete')]
class DeleteTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itChecksAccess(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $randomSet = Set::factory()->create();

        $this->delete(
            URL::route('api.public.sets.delete', $randomSet->getRouteKey())
        )->assertForbidden();
    }

    #[Test]
    public function itDeletesSet(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $ownSet = Set::factory()->usingOwner($user)->create();

        $response = $this->delete(
            URL::route('api.public.sets.delete', $ownSet->getRouteKey())
        );

        $response->assertNoContent();
        $this->assertDatabaseMissing(Set::class, ['id' => $ownSet->id]);
    }

    #[Test]
    #[DataProvider('stateRuleData')]
    public function itChecksStateRule(string $status, bool $allowed): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $ownSet = Set::factory()->usingOwner($user)->create(['status' => $status]);

        $response = $this->delete(
            URL::route('api.public.sets.delete', $ownSet->getRouteKey())
        );

        $allowed ?
            $response->assertNoContent() :
            $response->assertConflict();
    }

    public static function stateRuleData(): array
    {
        return [
            ['status' => Draft::class, 'allowed' => true],
            ['status' => Processing::class, 'allowed' => false],
        ];
    }
}
