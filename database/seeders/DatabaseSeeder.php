<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
//            PermissionSeeder::class,
            UserSeeder::class,
            TascaSeeder::class,
            CustomerSeeder::class,
            ReviewSeeder::class,
            ReservationSeeder::class,
            PostSeeder::class,
            PictureSeeder::class,
            CommentSeeder::class,
            ManagerSeeder::class,
            EmployeeSeeder::class,
            FriendshipSeeder::class,
        ]);
    }
}
