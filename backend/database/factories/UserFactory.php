<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => 'password',
            'remember_token' => Str::random(10),
            'username' => $this->faker->unique()->userName(),
            'avatar' => $this->faker->imageUrl(100, 100, 'people'),
            'bio' => $this->faker->sentence(),
            'location' => $this->faker->city(),
            'birthday' => $this->faker->date(),
            'reputation' => $this->faker->numberBetween(0, 1000),
        ];
    }


    /**
     * Indicate that the model's email address should be unverified.
     */
    // public function unverified(): static
    // {
    //     return $this->state(fn (array $attributes) => [
    //         'email_verified_at' => null,
    //     ]);
    // }
}
