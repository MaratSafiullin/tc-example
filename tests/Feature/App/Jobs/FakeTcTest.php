<?php

namespace Tests\Feature\App\Jobs;

use App\Jobs\FakeTc;
use App\Models\Set;
use App\Models\Text;
use App\Models\Theme;
use App\Models\ThemeText;
use App\ModesStates\Set\Completed;
use App\ModesStates\Set\Draft;
use App\ModesStates\Set\Processing;
use App\ModesStates\Text\Failed;
use App\ModesStates\Text\Processed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Bus;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\RefreshDatabase;
use Tests\TestCase;

#[CoversClass(FakeTc::class)]
class FakeTcTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itSkipsIfStatusIsWrong(): void
    {
        $set = Set::factory()->create(['status' => $status = Draft::class]);

        Bus::dispatch(new FakeTc($set));

        $this->assertInstanceOf($status, $set->fresh()->status);
        $this->assertDatabaseEmpty(Theme::class);
        $this->assertDatabaseEmpty(ThemeText::class);
    }

    #[Test]
    public function itUpdatesStatus(): void
    {
        $set = Set::factory()->create(['status' => Processing::class]);

        Bus::dispatch(new FakeTc($set));

        $this->assertInstanceOf(Completed::class, $set->fresh()->status);
    }

    #[Test]
    public function itMakesSureSetHasThemes(): void
    {
        $set = Set::factory()->create(['status' => Processing::class]);

        Bus::dispatch(new FakeTc($set));

        $this->assertDatabaseHas(Theme::class, ['set_id' => $set->id]);
    }

    #[Test]
    public function itMakesSureEachTextGetsProcessedOrFailed(): void
    {
        $set = Set::factory()->create(['status' => Processing::class]);
        Text::factory()->usingSet($set)->count(10)->create();

        Bus::dispatch(new FakeTc($set));

        $this->assertFalse(
            $set->texts()
                ->whereNotIn('status', [Processed::$name, Failed::$name])
                ->exists()
        );

        $this->assertFalse(
            $set->texts()
                ->where('status', Processed::$name)
                ->whereDoesntHave(
                    'themes',
                    fn(Builder $builder) => $builder->where('set_id', $set->id)
                )
                ->exists()
        );
    }
}
