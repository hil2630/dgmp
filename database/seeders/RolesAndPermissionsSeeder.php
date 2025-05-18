<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage tournaments',
            'manage donations',
            'view admin panel',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $moderator = Role::firstOrCreate(['name' => 'Moderator']);
        $user = Role::firstOrCreate(['name' => 'User']);

        $superAdmin->givePermissionTo(Permission::all());
        $admin->givePermissionTo(['manage tournaments', 'manage donations', 'view admin panel']);
        $moderator->givePermissionTo(['manage tournaments', 'view admin panel']);
        // User role gets no special permissions by default

        // Assign Super Admin to the first user
        $firstUser = User::first();
        if ($firstUser) {
            $firstUser->assignRole('Super Admin');
        }
    }
}
