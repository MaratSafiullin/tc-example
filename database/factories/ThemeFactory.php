<?php

namespace Database\Factories;

use App\Models\Set;
use App\Models\Theme;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThemeFactory extends Factory
{
    protected $model = Theme::class;

    public function definition(): array
    {
        return [
            'set_id' => Set::factory(),
            'name'      => $this->faker->safeEmailDomain,
        ];
    }
}
