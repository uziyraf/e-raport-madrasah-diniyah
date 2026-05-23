<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $roles = [
            'super_admin',
            'kepala_sekolah',
            'wali_kelas',
            'guru_fan',
            'wali_santri',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);
        }

        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@simadu.test'],
            [
                'name' => 'Super Admin',
                'username' => 'admin',
                'phone' => null,
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );

        $superAdmin->assignRole('super_admin');

        $superAdmin->assignRole('super_admin');
    }
}