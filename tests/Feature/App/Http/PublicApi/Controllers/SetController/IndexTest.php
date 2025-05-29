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

#[CoversMethod(SetController::class, 'index')]
class IndexTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itReturnsUserSetsList(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        Set::factory()->usingOwner($user)->count($countForUser = 15)->create();
        Set::factory()->count(20)->create();

        $response = $this->get(
            URL::route('api.public.sets.index', ['per_page' => $perPage = 5])
        );

        $response->assertOk();
        $response->assertJsonPath('meta.total', $countForUser);
        $response->assertJsonCount($perPage, 'data');
    }
}
