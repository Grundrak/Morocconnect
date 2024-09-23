<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use Faker\Factory as Faker;

class PostsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $users = User::all();

        for ($i = 0; $i < 100; $i++) {
            $user = $users->random();
            Post::create([
                'user_id' => $user->id,
                'title' => $faker->sentence,
                'content' => $faker->paragraphs(3, true),
                'image' => $faker->imageUrl(640, 480, 'nature'),
                'likes' => $faker->numberBetween(0, 1000),
                'shares' => $faker->numberBetween(0, 100),
                'is_verified' => $faker->boolean(20),
                'verification_status' => $faker->randomElement(['pending', 'approved', 'rejected']),
                'verification_request' => $faker->optional()->sentence,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);
        }
    }
}