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
        Permission::query()->delete();
        $permissions = AdminPermissionsEnum::cases();

        foreach ($permissions as $permission) {
            Permission::query()->updateOrCreate([
                'name' => $permission->value,
            ]);
        }

        Role::query()->delete();
        $role = Role::query()->updateOrCreate(['name' => 'Super Admin']);
        $permissions = Permission::all()->pluck('name')->toArray();
        $role->givePermissionTo($permissions);
    }
}
