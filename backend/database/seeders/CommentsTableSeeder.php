<?php
namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Faker\Factory as Faker;

class CommentsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $posts = Post::all();
        $users = User::all();

        for ($i = 0; $i < 300; $i++) {
            $post = $posts->random();
            $user = $users->random();
            $parentComment = $faker->boolean(20) ? Comment::where('post_id', $post->id)->inRandomOrder()->first() : null;

            Comment::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'content' => $faker->paragraph,
                'parent_id' => $parentComment ? $parentComment->id : null,
                'created_at' => $faker->dateTimeBetween($post->created_at, 'now'),
                'updated_at' => $faker->dateTimeBetween($post->created_at, 'now'),
            ]);
        }
    }
}