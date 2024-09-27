<?php

namespace Database\Factories;

use App\Models\Badge;
use Illuminate\Database\Eloquent\Factories\Factory;

class BadgeFactory extends Factory
{
    protected $model = Badge::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            'description' => $this->faker->sentence,
            'icon' => $this->faker->word() . '.svg',
            'type' => $this->faker->randomElement(['automatic', 'manual', 'role-based']),
            'requirement' => $this->faker->numberBetween(1, 100),
            'requirement_type' => $this->faker->word(),
        ];
    }
}