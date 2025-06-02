<?php

namespace App\Jobs;

use App\Models\Set;
use App\Models\Text;
use App\Models\Theme;
use App\Models\ThemeText\Sentiment;
use App\ModesStates\Set\Completed;
use App\ModesStates\Set\Processing;
use App\ModesStates\Text\Failed;
use App\ModesStates\Text\Processed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class FakeTc implements ShouldQueue
{
    use Queueable;
    use WithFaker;

    private const MIN_THEMES_COUNT = 5;
    private const MIN_THEMES_PER_TEXT_COUNT = 2;
    private const FAILURE_CHANCE = 5;

    public function __construct(private readonly Set $set)
    {
    }

    public function middleware(): array
    {
        return [new WithoutOverlapping($this->set->id)];
    }

    /**
     * @throws \Spatie\ModelStates\Exceptions\CouldNotPerformTransition
     */
    public function handle(): void
    {
        if ($this->set->status::class !== Processing::class) {
            return;
        }

        $this->faker = $this->makeFaker();

        $this->fakeThemes();

        $this->set->texts()->chunk(
            1000,
            function (Collection $texts) {
                $texts->each(function (Text $text) {
                    $this->fakeTc($text);
                });
            }
        );

        $this->set->status->transitionTo(Completed::class);
    }

    private function fakeThemes(): void
    {
        $targetCount = self::MIN_THEMES_COUNT + rand(0, floor(self::MIN_THEMES_COUNT / 2));

        $count = $this->set->themes->count();
        while ($count < $targetCount) {
            $count += Theme::insertOrIgnore([
                'set_id' => $this->set->id,
                'name'   => $this->faker->unique()->colorName,
            ]);
        }
    }

    private function fakeTc(Text $text): void
    {
        if ($this->faker->boolean(self::FAILURE_CHANCE)) {
            $text->status->transitionTo(Failed::class);

            return;
        }

        $targetCount = self::MIN_THEMES_PER_TEXT_COUNT + rand(0, floor(self::MIN_THEMES_PER_TEXT_COUNT / 2));
        $count       = $text->themes->count();

        $themesToAttach = $this->set->themes()
            ->whereNotIn('id', $text->themes->pluck('id'))
            ->inRandomOrder()->limit($targetCount - $count)->get();
        $themesToAttach->each(function (Theme $theme) use ($text) {
            $sentiment = Sentiment::cases()[array_rand(Sentiment::cases())];
            $text->themes()->attach($theme->id, ['sentiment' => $sentiment]);
        });
        $text->status->transitionTo(Processed::class);
    }
}
