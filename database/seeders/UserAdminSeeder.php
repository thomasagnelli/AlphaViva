<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use \App\Models\User;


class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => env('APP_ADMIN_NAME'),
            'email' => env('APP_ADMIN_EMAIL'),
            'email_verified_at' => now(),
            'password' => env('APP_ADMIN_PASS'),
            'roleId' => Role::getByName('admin'),
            'remember_token' => Str::random(10),
        ]);

        $admin->save();
    }
}
