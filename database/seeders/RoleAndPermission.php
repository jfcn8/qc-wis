<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'create']);
        Permission::create(['name' => 'edit']);
        Permission::create(['name' => 'delete']);

        Permission::create(['name' => 'create-ris']);
        Permission::create(['name' => 'generate-ris']);
        Permission::create(['name' => 'generate']);

        $adminRole = Role::create(['name' => 'Admin']);
        $userRole = Role::create(['name' => 'User']);

        $adminRole->givePermissionTo([
            'create',
            'edit',
            'delete',
            'create-ris',
            'generate',
        ]);

        $userRole->givePermissionTo([
            'create-ris',
            'generate-ris',
        ]);
    }
}
