<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;
use Faker\Factory as Faker;

class BadgesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $badges = [
            ['name' => 'Newcomer', 'description' => 'Welcome to the community!', 'icon' => '🌟', 'type' => 'automatic', 'requirement' => 1, 'requirement_type' => 'posts'],
            ['name' => 'Frequent Poster', 'description' => 'Posted 50 times', 'icon' => '✍️', 'type' => 'automatic', 'requirement' => 50, 'requirement_type' => 'posts'],
            ['name' => 'Liked', 'description' => 'Received 100 likes', 'icon' => '👍', 'type' => 'automatic', 'requirement' => 100, 'requirement_type' => 'likes'],
            ['name' => 'Verified', 'description' => 'Account verified', 'icon' => '✅', 'type' => 'manual', 'requirement' => null, 'requirement_type' => null],
            ['name' => 'Moderator', 'description' => 'Community moderator', 'icon' => '🛡️', 'type' => 'role-based', 'requirement' => null, 'requirement_type' => null],
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }

        // Add some random badges
        for ($i = 0; $i < 5; $i++) {
            Badge::create([
                'name' => $faker->unique()->word,
                'description' => $faker->sentence,
                'icon' => $faker->randomElement(['🏆', '🎖️', '🥇', '🥈', '🥉']),
                'type' => $faker->randomElement(['automatic', 'manual', 'role-based']),
                'requirement' => $faker->optional()->numberBetween(1, 1000),
                'requirement_type' => $faker->optional()->word,
            ]);
        }
    }
}