<?php

namespace Database\Seeders;

use App\Models\User;
use Backpack\PermissionManager\app\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $user = User::create([
        //     'name' => 'Administrator',
        //     'email' => 'admin@test.com',
        //     'password' => Hash::make(12345678),
        // ]);

        // $superAdmin = Role::create([
        //     'name' => 'Admin'
        // ]);

        // $user->assignRole($superAdmin);
    }
}
