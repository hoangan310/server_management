<?php

namespace Database\Seeders;

use App\Enums\AdminPermissionsEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing permissions and recreate them
        Permission::query()->delete();
        $permissions = AdminPermissionsEnum::cases();

        foreach ($permissions as $permission) {
            Permission::query()->updateOrCreate([
                'name' => $permission->value,
            ]);
        }

        // Delete existing roles and recreate Super Admin
        Role::query()->delete();
        $role = Role::query()->updateOrCreate(['name' => 'Super Admin']);

        // Get all permissions including any newly added ones
        $allPermissions = Permission::all();

        // Map each permission to the Super Admin role
        foreach ($allPermissions as $permission) {
            $role->givePermissionTo($permission);
        }

        // Sync permissions to ensure any removed permissions are handled
        $permissions = Permission::all()->pluck('name')->toArray();
        $role->syncPermissions($permissions);
    }
}
