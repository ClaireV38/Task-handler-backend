<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);
        $user  = Role::create(['name' => 'user']);

        Permission::create(['name' => 'view tasks']);
        Permission::create(['name' => 'edit tasks']);

        $admin->givePermissionTo(Permission::all());
        $user->givePermissionTo('view tasks');
    }
}
