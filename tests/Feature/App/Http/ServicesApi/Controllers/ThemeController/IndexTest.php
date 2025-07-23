<?php

namespace Tests\Feature\App\Http\ServicesApi\Controllers\ThemeController;

use App\Http\ServicesApi\Controllers\ThemeController;
use App\Models\AccessToken\Ability;
use App\Models\Set;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use Tests\RefreshDatabase;
use Tests\TestCase;

#[CoversMethod(ThemeController::class, 'index')]
class IndexTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itReturnsSetThemesList(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::ServicesApi->value]);

        $otherSet = Set::factory()->create();
        Theme::factory()->usingSet($otherSet)->count(10)->create();

        $set = Set::factory()->create();
        Theme::factory()->usingSet($set)->count($countInSet = 15)->create();

        $response = $this->get(
            URL::route('api.services.sets.themes.index', $set->getRouteKey())
        );

        $response->assertOk();
        $response->assertJsonCount($countInSet, 'data');
    }
}
