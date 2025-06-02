<?php

namespace Tests\Integration\App\ModelStates\Set\Transitions;

use App\Jobs\FakeTc;
use App\Models\Set;
use App\ModesStates\Set\Draft;
use App\ModesStates\Set\Processing;
use App\ModesStates\Set\Transitions\ToProcessing;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\RefreshDatabase;
use Tests\TestCase;

#[CoversClass(ToProcessing::class)]
class ToProcessingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws \Spatie\ModelStates\Exceptions\CouldNotPerformTransition
     */
    #[Test]
    public function itUpdatesStatus(): void
    {
        $set = Set::factory()->create(['status' => Draft::class]);

        $set->status->transitionTo(Processing::class);

        $this->assertInstanceOf(Processing::class, $set->status);
    }

    /**
     * @throws \Spatie\ModelStates\Exceptions\CouldNotPerformTransition
     */
    #[Test]
    public function itDispatchesJobToFakeTc(): void
    {
        Queue::fake();
        config()->set('tc.fake', true);

        $set = Set::factory()->create(['status' => Draft::class]);

        $set->status->transitionTo(Processing::class);

        Queue::assertPushed(FakeTc::class);
    }
}
