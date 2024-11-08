<?php

namespace Database\Seeders;

use App\Models\User;
use Backpack\PermissionManager\app\Models\Permission;
use Backpack\PermissionManager\app\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'access_product',
            'create_product',
            'edit_product',
            'detail_product',
            'update_product',
            'delete_product',
            'access_category',
            'create_category',
            'edit_category',
            'update_category',
            'detail_category',
            'delete_category',
            'access_transaction',
            'create_transaction',
            'detail_transaction',
            'delete_transaction',
            'access_user',
            'create_user',
            'update_user',
            'delete_user',
            'access_role',
            'create_role',
            'update_role',
            'delete_role',
            'access_permission',
            'create_permission',
            'update_permission',
            'delete_permission'
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission
            ]);
        }

        $admin = Role::create([
            'name' => 'Admin'
        ]);

        $user = User::create([
            'name' => 'Administrator',
            'email' => 'admin@test.com',
            'password' => Hash::make(12345678),
        ]);

        $user->assignRole($admin);

        $admin->givePermissionTo($permissions);
    }
}
