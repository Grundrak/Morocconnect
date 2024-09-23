<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $adminRole = Role::where('slug', 'admin')->first();
        $moderatorRole = Role::where('slug', 'moderator')->first();
        $userRole = Role::where('slug', 'user')->first();

        // Define a single password for all accounts
        $password = Hash::make('password123');

        // Create admin
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => $password,
            'avatar' => $faker->imageUrl(150, 150, 'people'),
            'bio' => $faker->paragraph,
            'location' => $faker->city,
            'birthday' => $faker->date('Y-m-d', '-18 years'),
            'reputation' => $faker->numberBetween(1000, 5000),
        ])->roles()->attach($adminRole);

        // Create moderator
        User::create([
            'name' => 'Moderator User',
            'username' => 'moderator',
            'email' => 'moderator@example.com',
            'password' => $password,
            'avatar' => $faker->imageUrl(150, 150, 'people'),
            'bio' => $faker->paragraph,
            'location' => $faker->city,
            'birthday' => $faker->date('Y-m-d', '-18 years'),
            'reputation' => $faker->numberBetween(500, 2000),
        ])->roles()->attach($moderatorRole);

        // Create regular users
        for ($i = 1; $i <= 50; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'username' => $faker->unique()->userName . $i, // Ensure uniqueness
                'email' => $faker->unique()->safeEmail, // Ensure uniqueness
                'password' => $password,
                'avatar' => $faker->imageUrl(150, 150, 'people'),
                'bio' => $faker->paragraph,
                'location' => $faker->city,
                'birthday' => $faker->date('Y-m-d', '-18 years'),
                'reputation' => $faker->numberBetween(0, 1000),
            ]);
            $user->roles()->attach($userRole);
        }
    }
}