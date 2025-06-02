<?php

namespace Tests\Feature\App\Http\PublicApi\Controllers\SetController;

use App\Http\PublicApi\Controllers\SetController;
use App\Jobs\FakeTc;
use App\Models\AccessToken\Ability;
use App\Models\Set;
use App\Models\User;
use App\ModesStates\Set\Completed;
use App\ModesStates\Set\Draft;
use App\ModesStates\Set\Processing;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\RefreshDatabase;
use Tests\TestCase;

#[CoversMethod(SetController::class, 'startTc')]
class StartTcTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itChecksAccess(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $randomSet = Set::factory()->create();

        $this->post(
            URL::route('api.public.sets.start-tc', $randomSet->getRouteKey())
        )->assertForbidden();
    }

    #[Test]
    public function itStartsFakeProcessing(): void
    {
        Queue::fake();
        config()->set('tc.fake', true);

        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $set = Set::factory()->usingOwner($user)->create();

        $response = $this->post(
            URL::route('api.public.sets.start-tc', $set->getRouteKey())
        );

        $response->assertAccepted();
        $this->assertInstanceOf(Processing::class, $set->refresh()->status);
        Queue::assertPushed(FakeTc::class);
    }

    #[Test]
    #[DataProvider('stateRuleData')]
    public function itChecksStatus(string $status, bool $allowed): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $set = Set::factory()->usingOwner($user)->create(['status' => $status]);

        $response = $this->post(
            URL::route('api.public.sets.start-tc', $set->getRouteKey())
        );

        $allowed ?
            $response->assertAccepted() :
            $response->assertConflict();
    }

    public static function stateRuleData(): array
    {
        return [
            ['status' => Draft::class, 'allowed' => true],
            ['status' => Completed::class, 'allowed' => false],
            ['status' => Processing::class, 'allowed' => false],
        ];
    }
}
