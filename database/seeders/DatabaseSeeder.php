<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar seeders en orden de dependencias
        $this->call([
            CompanySeeder::class,      // Primero las empresas
            RoleSeeder::class,         // Segundo los roles (antes de usuarios)
            CategorySeeder::class,     // Luego las categorías
            WarehouseSeeder::class,    // Almacenes
            ProductSeeder::class,      // Productos
            UserSeeder::class,         // Usuarios (después de empresas y roles)
            FiscalStampSeeder::class,  // Timbres fiscales
        ]);
    }
}
