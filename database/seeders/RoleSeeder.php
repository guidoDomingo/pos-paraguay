<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Rol Administrador con todos los permisos
        $adminRole = Role::updateOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrador',
                'description' => 'Acceso completo al sistema',
                'permissions' => [
                    'pos.access',
                    'pos.sell',
                    'products.view',
                    'products.create',
                    'products.edit',
                    'products.delete',
                    'sales.view',
                    'sales.create',
                    'invoices.view',
                    'invoices.create',
                    'customers.view',
                    'customers.create',
                    'customers.edit',
                    'reports.view',
                    'cash_register.open',
                    'cash_register.close',
                    'admin.users',
                    'admin.company',
                    'admin.settings', // ← PERMISO CLAVE PARA GESTIÓN DE DATOS
                    'inventory.view',
                    'inventory.adjust',
                    'fiscal_stamps.manage',
                ],
                'is_active' => true,
            ]
        );

        // Rol Vendedor con permisos básicos
        $sellerRole = Role::updateOrCreate(
            ['name' => 'seller'],
            [
                'display_name' => 'Vendedor',
                'description' => 'Acceso solo para ventas y consultas básicas',
                'permissions' => [
                    'pos.access',
                    'pos.sell',
                    'products.view',
                    'sales.view',
                    'sales.create',
                    'invoices.view',
                    'customers.view',
                    'cash_register.open',
                    'cash_register.close',
                ],
                'is_active' => true,
            ]
        );

        // Rol Supervisor con permisos intermedios
        $supervisorRole = Role::updateOrCreate(
            ['name' => 'supervisor'],
            [
                'display_name' => 'Supervisor',
                'description' => 'Acceso a ventas, reportes e inventario',
                'permissions' => [
                    'pos.access',
                    'pos.sell',
                    'products.view',
                    'products.create',
                    'products.edit',
                    'sales.view',
                    'sales.create',
                    'invoices.view',
                    'invoices.create',
                    'customers.view',
                    'customers.create',
                    'customers.edit',
                    'reports.view',
                    'cash_register.open',
                    'cash_register.close',
                    'inventory.view',
                    'inventory.adjust',
                ],
                'is_active' => true,
            ]
        );

        echo "✅ Roles creados exitosamente:\n";
        echo "   • Administrador (ID: {$adminRole->id}) - Todos los permisos\n";
        echo "   • Supervisor (ID: {$supervisorRole->id}) - Permisos intermedios\n";
        echo "   • Vendedor (ID: {$sellerRole->id}) - Permisos básicos\n";
    }
}