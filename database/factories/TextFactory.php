<?php

namespace Database\Factories;

use App\Models\Set;
use App\Models\Text;
use App\Models\Text\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class TextFactory extends Factory
{
    protected $model = Text::class;

    public function definition(): array
    {
        return [
            'set_id' => Set::factory(),
            'text'   => $this->faker->sentence(),
            'status' => Status::Created,
        ];
    }

    public function usingSet(Set $set): static
    {
        return $this->state(fn() => ['set_id' => $set->id]);
    }
}
