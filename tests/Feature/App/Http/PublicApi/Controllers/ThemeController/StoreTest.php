<?php

namespace Tests\Feature\App\Http\PublicApi\Controllers\ThemeController;

use App\Http\PublicApi\Controllers\ThemeController;
use App\Models\AccessToken\Ability;
use App\Models\Set;
use App\Models\Theme;
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

#[CoversMethod(ThemeController::class, 'store')]
class StoreTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itChecksAccess(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $randomSet = Set::factory()->create();

        $this->get(
            URL::route('api.public.sets.themes.store', $randomSet->getRouteKey())
        )->assertForbidden();
    }

    #[Test]
    public function itCreatesThemesInSet(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $set = Set::factory()->usingOwner($user)->create();

        $response = $this->post(
            URL::route('api.public.sets.themes.store', $set->getRouteKey()),
            [
                'themes' => [
                    ['name' => $themeName = 'Theme 1'],
                ],
            ]
        );

        $response->assertCreated();
        $this->assertDatabaseHas(Theme::class, [
            'set_id' => $set->id,
            'name'   => $themeName,
        ]);
    }

    #[Test]
    #[DataProvider('stateRuleData')]
    public function itChecksStateRule(string $status, bool $allowed): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $set = Set::factory()->usingOwner($user)->create(['status' => $status]);

        $response = $this->post(
            URL::route('api.public.sets.themes.store', $set->getRouteKey()),
            [
                'themes' => [
                    ['name' => 'Theme 1'],
                ],
            ]
        );

        $allowed ?
            $response->assertCreated() :
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
