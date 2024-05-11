<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'password' => bcrypt('password'),
            'role' => UserRoleEnum::ADMIN->value,
        ]);

        User::firstOrCreate([
            'email' => 'user@gmail.com',
        ], [
            'first_name' => 'Godstime',
            'last_name' => 'John',
            'password' => bcrypt('password'),
            'role' => UserRoleEnum::USER->value,
        ]);
    }
}
