<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::create([
            'first_name' => "admin",
            'last_name' => "adminov",
            'email' => "admin@site.com",
            'email_verified_at' => now(),
            'password' => bcrypt('admin'), // password
            'remember_token' => Str::random(10),
        ]);
        DB::table('users')->insert([
            'first_name' => "moderator",
            'last_name' => "moderatorov",
            'email' => "moderator@site.com",
            'email_verified_at' => now(),
            'password' => bcrypt('admin'), // password
            'remember_token' => Str::random(10),
        ]);

        factory(User::class, 7)->create();
    }
}
