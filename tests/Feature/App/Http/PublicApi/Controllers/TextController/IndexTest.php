<?php

namespace Tests\Feature\App\Http\PublicApi\Controllers\TextController;

use App\Http\PublicApi\Controllers\TextController;
use App\Models\AccessToken\Ability;
use App\Models\Set;
use App\Models\Text;
use App\Models\User;
use App\ModesStates\Set\Completed;
use App\ModesStates\Set\Draft;
use App\ModesStates\Set\Processing;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RefreshDatabase;
use Tests\TestCase;

#[CoversMethod(TextController::class, 'index')]
class IndexTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itChecksAccess(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $randomSet = Set::factory()->create();

        $this->get(
            URL::route('api.public.sets.texts.index', $randomSet->getRouteKey())
        )->assertForbidden();
    }

    #[Test]
    public function itReturnsSetTextsList(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $otherSet = Set::factory()->usingOwner($user)->create();
        Text::factory()->usingSet($otherSet)->count(10)->create();

        $set = Set::factory()->usingOwner($user)->create();
        Text::factory()->usingSet($set)->count($countInSet = 15)->create();

        $response = $this->get(
            URL::route('api.public.sets.texts.index', ['set' => $set->getRouteKey(), 'per_page' => $perPage = 5])
        );

        $response->assertOk();
        $response->assertJsonPath('meta.total', $countInSet);
        $response->assertJsonCount($perPage, 'data');
    }

    #[Test]
    #[DataProvider('stateRuleData')]
    public function itChecksStateRule(string $status, bool $allowed): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $set = Set::factory()->usingOwner($user)->create(['status' => $status]);

        $response = $this->get(
            URL::route('api.public.sets.texts.index', $set->getRouteKey())
        );

        $allowed ?
            $response->assertSuccessful() :
            $response->assertConflict();
    }

    public static function stateRuleData(): array
    {
        return [
            ['status' => Draft::class, 'allowed' => true],
            ['status' => Completed::class, 'allowed' => true],
            ['status' => Processing::class, 'allowed' => false],
        ];
    }
}
