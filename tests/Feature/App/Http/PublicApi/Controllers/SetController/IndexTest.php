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

        Set::factory()->usingOwner($user)->count(20)->create();
        Set::factory()->count(20)->create();

        $routeName = 'api.public.sets.index';
        $url       = URL::route($routeName);
        $headers   = [['Accept' => 'application/json']];
        $response  = $this->get($url, $headers);

        $response->assertOk();
        $response->assertJsonPath('meta.total', 20);
        $response->assertJsonCount(10, 'data');
    }
}
