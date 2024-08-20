<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //creando permisos si no existen
        Permission::firstOrCreate(['name' => 'edit blog']);
        Permission::firstOrCreate(['name' => 'delete blog']);
        Permission::firstOrCreate(['name' => 'publish blog']);
        Permission::firstOrCreate(['name' => 'unpublish blog']);
        Permission::firstOrCreate(['name' => 'Create User']);
        Permission::firstOrCreate(['name' => 'Read User']);
        Permission::firstOrCreate(['name' => 'Update User']);
        Permission::firstOrCreate(['name' => 'Delete User']);


        //creando roles si no existen
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'editor']);
        Role::firstOrCreate(['name' => 'author']);
        Role::firstOrCreate(['name' => 'reviewer']);


        //asignando un rol a un usuario

    }
}
