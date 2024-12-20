<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $modules = ['system_notifications'];
        $actions = ['manage', 'delete'];

        // Permission::create(['name' => 'all_data_access', 'guard_name' => 'client']);

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permissionName = "{$action}_{$module}";
                Permission::create(['name' => $permissionName, 'guard_name' => 'web']);
            }
        }

        // Assign permissions to a role
        // $adminRole = Role::findByName('admin');
        // $adminRole->syncPermissions(Permission::all());
    }
}
