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

#[CoversMethod(TextController::class, 'store')]
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
            URL::route('api.public.sets.texts.store', $randomSet->getRouteKey())
        )->assertForbidden();
    }

    #[Test]
    public function itCreatesTextsInSet(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $set = Set::factory()->usingOwner($user)->create();

        $response = $this->post(
            URL::route('api.public.sets.texts.store', $set->getRouteKey()),
            [
                'texts' => [
                    ['text' => $text = 'Text', 'external_id' => $externalId = 'text_id_1'],
                ],
            ]
        );

        $response->assertCreated();
        $this->assertDatabaseHas(Text::class, [
            'set_id' => $set->id,
            'text' => $text,
            'external_id' => $externalId,
        ]);
    }

    #[Test]
    #[DataProvider('stateRuleData')]
    public function itChecksStatus(string $status, bool $allowed): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $set = Set::factory()->usingOwner($user)->create(['status' => $status]);

        $response = $this->post(
            URL::route('api.public.sets.texts.store', $set->getRouteKey()),
            [
                'texts' => [
                    ['text' => 'Text', 'external_id' => 'text_id_1'],
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
            ['status' => Completed::class, 'allowed' => false],
        ];
    }

    #[Test]
    public function itPreventsTooManyTextsInSet(): void
    {
        config()->set('tc.max_texts_count_per_set', $maxCount = 2);

        $user = User::factory()->create();
        Sanctum::actingAs($user, [Ability::PublicApi->value]);

        $set = Set::factory()->usingOwner($user)->create(['status' => Draft::class]);
        Text::factory()->usingSet($set)->count($maxCount - 1)->create();

        $response = $this->post(
            URL::route('api.public.sets.texts.store', $set->getRouteKey()),
            [
                'texts' => [
                    ['text' => 'Text', 'external_id' => 'text_id_1'],
                ],
            ]
        );
        $response->assertCreated();

        $response = $this->post(
            URL::route('api.public.sets.texts.store', $set->getRouteKey()),
            [
                'texts' => [
                    ['text' => 'Text', 'external_id' => 'text_id_2'],
                ],
            ]
        );
        $response->assertConflict();
    }
}
