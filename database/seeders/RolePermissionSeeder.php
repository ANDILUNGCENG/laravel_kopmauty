<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Hapus cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat permissions
        $permissions = [
            'view_admin',
            'view_kasir',
            'view_kepalatoko',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Buat roles dan assign permissions
        $roles = [
            'admin' => ['view_admin'],
            'kasir' => ['view_kasir'],
            'kepalatoko' => ['view_kepalatoko'],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }

        // Buat users dan assign role
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12345'),
                'role' => 'admin',
            ],
            [
                'name' => 'kasir',
                'email' => 'kasir@gmail.com',
                'password' => Hash::make('12345'),
                'role' => 'kasir',
            ],
            [
                'name' => 'kepalatoko',
                'email' => 'kepalatoko@gmail.com',
                'password' => Hash::make('12345'),
                'role' => 'kepalatoko',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate([
                'email' => $userData['email'],
            ], [
                'name' => $userData['name'],
                'password' => $userData['password'],
            ]);

            $user->assignRole($userData['role']);
        }
    }
}

