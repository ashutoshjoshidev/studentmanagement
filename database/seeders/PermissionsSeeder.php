<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Hash;

class PermissionsSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'approve user']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'admin']);

        $role2 = Role::create(['name' => 'teacher']);

        $role3 = Role::create(['name' => 'student']);


        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin@123'),
        ]);
        $user->assignRole($role1);

        $user = \App\Models\User::factory()->create([
            'name' => 'Teacher User',
            'email' => 'teacher@example.com',
            'password' => Hash::make('teacher@123'),
        ]);
        $user->assignRole($role2);

        $user = \App\Models\User::factory()->create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'password' => Hash::make('student@123')
        ]);
        $user->assignRole($role3);
    }
}
