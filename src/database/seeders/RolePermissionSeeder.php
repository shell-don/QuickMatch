<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Users & Auth
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.view',
            'permissions.assign',
            'profile.view',
            'profile.edit',

            // Offers
            'offers.view',
            'offers.create',
            'offers.edit',
            'offers.delete',
            'offers.manage',

            // Companies
            'companies.view',
            'companies.create',
            'companies.edit',
            'companies.delete',

            // Applications
            'applications.view',
            'applications.create',
            'applications.manage',

            // Chatbot
            'chatbot.use',

            // News
            'news.view',

            // Professions & Formations
            'professions.view',
            'formations.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Admin - Full access
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        // Manager (Entreprise)
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $managerRole->givePermissionTo([
            'users.view',
            'users.create',
            'users.edit',
            'profile.view',
            'profile.edit',
            'offers.view',
            'offers.manage',
            'companies.view',
            'applications.view',
            'applications.manage',
        ]);

        // User (Étudiant)
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $userRole->givePermissionTo([
            'profile.view',
            'profile.edit',
            'offers.view',
            'applications.view',
            'applications.create',
            'chatbot.use',
            'news.view',
            'professions.view',
            'formations.view',
        ]);
    }
}
