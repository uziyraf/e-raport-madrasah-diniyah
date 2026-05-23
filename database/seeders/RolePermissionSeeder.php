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

        $users = [
            [
                'name' => 'Super Admin',
                'username' => 'admin',
                'email' => 'admin@simadu.test',
                'role' => 'super_admin',
            ],
            [
                'name' => 'Kepala Sekolah',
                'username' => 'kepala',
                'email' => 'kepala@simadu.test',
                'role' => 'kepala_sekolah',
            ],
            [
                'name' => 'Wali Kelas',
                'username' => 'wali_kelas',
                'email' => 'wali.kelas@simadu.test',
                'role' => 'wali_kelas',
            ],
            [
                'name' => 'Guru Fan',
                'username' => 'guru_fan',
                'email' => 'guru.fan@simadu.test',
                'role' => 'guru_fan',
            ],
            [
                'name' => 'Wali Santri',
                'username' => 'wali_santri',
                'email' => 'wali.santri@simadu.test',
                'role' => 'wali_santri',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'username' => $userData['username'],
                    'phone' => null,
                    'password' => Hash::make('password'),
                    'status' => 'active',
                ]
            );

            $user->syncRoles([$userData['role']]);
        }
    }
}