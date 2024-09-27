<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'image' => $this->faker->imageUrl(), 
            'user_id' => User::factory(),
            'is_verified' => $this->faker->boolean(30),
            'verification_status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'likes' => $this->faker->numberBetween(0, 1000),
            'shares' => $this->faker->numberBetween(0, 500),
        ];
    }
}