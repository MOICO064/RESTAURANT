<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $Administrador = Role::create(['name' => 'Administrador']);
        $Personal = Role::create(['name' => 'Personal']);
        $Cajero = Role::create(['name' => 'Cajero']);

        // Insertar usuario administrador
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin',
            'password' => Hash::make('admin123'),
            'avatar' => null,
            'email_verified_at' => now(),
        ]);

        $user->assignRole('Administrador');



        Permission::create(['name' => 'admin.categorias.index'])->syncRoles([$Administrador]);
        Permission::create(['name' => 'admin.productos.index'])->syncRoles([$Administrador]);
        Permission::create(['name' => 'admin.usuarios.index'])->syncRoles([$Administrador]);
        Permission::create(['name' => 'admin.system.edit'])->syncRoles([$Administrador]);

    }
}
