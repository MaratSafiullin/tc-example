<?php

namespace Database\Factories;

use App\Models\Set;
use App\Models\Set\ContextType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SetFactory extends Factory
{
    protected $model = Set::class;

    public function definition(): array
    {
        return [
            'owner_id'     => User::factory(),
            'name'         => 'Set name',
            'context'      => '',
            'context_type' => ContextType::QuestionAnswer,
        ];
    }

    public function usingOwner(User $user): static
    {
        return $this->state(fn() => ['owner_id' => $user->id]);
    }
}
