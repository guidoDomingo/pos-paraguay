<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Obtener la primera empresa
        $company = Company::first();
        
        if (!$company) {
            echo "❌ No hay empresas creadas. Ejecuta primero CompanySeeder\n";
            return;
        }

        // Obtener roles
        $adminRole = Role::where('name', 'admin')->first();
        $supervisorRole = Role::where('name', 'supervisor')->first();
        $sellerRole = Role::where('name', 'seller')->first();
        
        if (!$adminRole) {
            echo "❌ No hay roles creados. Ejecuta primero RoleSeeder\n";
            return;
        }

        // Usuarios de prueba
        $users = [
            [
                'name' => 'Administrador Sistema',
                'email' => 'admin@bodegaapp.com',
                'password' => Hash::make('password123'),
                'company_id' => $company->id,
                'role_id' => $adminRole->id,  // ← ADMIN
                'employee_code' => 'ADM001',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Usuario Demo',
                'email' => 'usuario@demo.com',
                'password' => Hash::make('demo123'),
                'company_id' => $company->id,
                'role_id' => $supervisorRole->id,  // ← SUPERVISOR
                'employee_code' => 'SUP001',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'María González',
                'email' => 'maria@bodegaapp.com',
                'password' => Hash::make('maria123'),
                'company_id' => $company->id,
                'role_id' => $sellerRole->id,  // ← VENDEDOR
                'employee_code' => 'VEN001',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Carlos López',
                'email' => 'carlos@bodegaapp.com',
                'password' => Hash::make('carlos123'),
                'company_id' => $company->id,
                'role_id' => $sellerRole->id,  // ← VENDEDOR
                'employee_code' => 'VEN002',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana@bodegaapp.com',
                'password' => Hash::make('ana123'),
                'company_id' => $company->id,
                'role_id' => $supervisorRole->id,  // ← SUPERVISOR
                'employee_code' => 'SUP002',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']], // Buscar por email
                $userData // Actualizar o crear con estos datos
            );
        }

        echo "✅ Usuarios de prueba creados exitosamente!\n";
        echo "📧 Credenciales disponibles:\n";
        echo "   🔑 ADMIN: admin@bodegaapp.com / password123 (Acceso completo)\n";
        echo "   👥 SUPERVISOR: usuario@demo.com / demo123 (Reportes + inventario)\n";
        echo "   🛒 VENDEDOR: maria@bodegaapp.com / maria123 (Solo ventas)\n";
        echo "   🛒 VENDEDOR: carlos@bodegaapp.com / carlos123 (Solo ventas)\n";
        echo "   👥 SUPERVISOR: ana@bodegaapp.com / ana123 (Reportes + inventario)\n";
        echo "\n💡 Solo el usuario ADMIN puede acceder a 'Gestión de Datos'\n";
    }
}
