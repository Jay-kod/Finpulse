<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions (Optional, adding a few basics just in case)
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'manage configuration']);
        Permission::firstOrCreate(['name' => 'run analysis']);
        Permission::firstOrCreate(['name' => 'view reports']);

        // Create Roles and assign created permissions

        // Viewer: can only view reports
        $viewerRole = Role::firstOrCreate(['name' => 'Viewer']);
        $viewerRole->syncPermissions(['view reports']);

        // Analyst: can run analysis and view reports
        $analystRole = Role::firstOrCreate(['name' => 'Analyst']);
        $analystRole->syncPermissions(['run analysis', 'view reports']);

        // Admin: can manage users, config, and run analysis
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions(['manage users', 'manage configuration', 'run analysis', 'view reports']);

        // Super Admin: bypasses everything (handled in AuthServiceProvider or Gate via Super Admin name)
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);

        // Create a default Super Admin user
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('Super Admin');

        // Create a test analyst
        $analyst = User::firstOrCreate([
            'email' => 'analyst@example.com',
        ], [
            'name' => 'Test Analyst',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $analyst->assignRole('Analyst');

        // Create a test viewer
        $viewer = User::firstOrCreate([
            'email' => 'viewer@example.com',
        ], [
            'name' => 'Test Viewer',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $viewer->assignRole('Viewer');
    }
}
