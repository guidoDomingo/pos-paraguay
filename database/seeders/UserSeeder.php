<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
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

        // Usuarios de prueba
        $users = [
            [
                'name' => 'Administrador Sistema',
                'email' => 'admin@bodegaapp.com',
                'password' => Hash::make('password123'),
                'company_id' => $company->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Usuario Demo',
                'email' => 'usuario@demo.com',
                'password' => Hash::make('demo123'),
                'company_id' => $company->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'María González',
                'email' => 'maria@bodegaapp.com',
                'password' => Hash::make('maria123'),
                'company_id' => $company->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Carlos López',
                'email' => 'carlos@bodegaapp.com',
                'password' => Hash::make('carlos123'),
                'company_id' => $company->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana@bodegaapp.com',
                'password' => Hash::make('ana123'),
                'company_id' => $company->id,
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
        echo "   • admin@bodegaapp.com / password123\n";
        echo "   • usuario@demo.com / demo123\n";
        echo "   • maria@bodegaapp.com / maria123\n";
        echo "   • carlos@bodegaapp.com / carlos123\n";
        echo "   • ana@bodegaapp.com / ana123\n";
    }
}
