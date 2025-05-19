<?php

namespace Database\Factories;

use App\Models\Text;
use App\Models\Theme;
use App\Models\ThemeText;
use App\Models\ThemeText\Sentiment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThemeTextFactory extends Factory
{
    protected $model = ThemeText::class;

    public function definition(): array
    {
        return [
            'theme_id'  => Theme::factory(),
            'text_id'   => Text::factory(),
            'sentiment' => Sentiment::Neutral,
        ];
    }

    public function usingText(Text $text): static
    {
        return $this->state(fn() => ['text_id' => $text->id]);
    }

    public function usingTheme(Theme $theme): static
    {
        return $this->state(fn() => ['theme_id' => $theme->id]);
    }
}
