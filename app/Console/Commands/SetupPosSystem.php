<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Warehouse;
use App\Models\FiscalStamp;
use App\Models\CompanyConfig;
use Illuminate\Support\Facades\Hash;

class SetupPosSystem extends Command
{
    protected $signature = 'pos:setup
                          {--company-name= : Nombre de la empresa}
                          {--company-ruc= : RUC de la empresa}
                          {--company-dv= : Dígito verificador}
                          {--admin-email= : Email del administrador}
                          {--admin-password= : Contraseña del administrador}';

    protected $description = 'Configura el sistema POS con datos iniciales';

    public function handle()
    {
        $this->info('🏪 Configurando Sistema POS Paraguay...');
        
        // Validar que no existan datos
        if (Company::count() > 0) {
            if (!$this->confirm('Ya existe configuración. ¿Desea continuar?')) {
                return 0;
            }
        }

        $companyData = $this->getCompanyData();
        $adminData = $this->getAdminData();

        $this->info('📋 Creando configuración inicial...');

        // Crear roles
        $this->createRoles();

        // Crear empresa
        $company = $this->createCompany($companyData);

        // Crear depósito principal
        $warehouse = $this->createMainWarehouse($company);

        // Crear usuario administrador
        $admin = $this->createAdmin($company, $adminData);

        // Crear categorías básicas
        $this->createCategories($company);

        // Crear configuración de empresa
        $this->createCompanyConfig($company);

        $this->info('✅ Sistema POS configurado correctamente!');
        $this->newLine();
        $this->info('📊 Resumen de configuración:');
        $this->table(
            ['Campo', 'Valor'],
            [
                ['Empresa', $company->name],
                ['RUC', $company->getFormattedRucAttribute()],
                ['Depósito', $warehouse->name],
                ['Administrador', $admin->name],
                ['Email Admin', $admin->email],
            ]
        );

        $this->newLine();
        $this->warn('⚠️  IMPORTANTE:');
        $this->warn('1. Configure un timbrado fiscal en el sistema');
        $this->warn('2. Agregue productos al inventario');
        $this->warn('3. Configure impresoras si es necesario');
        
        return 0;
    }

    private function getCompanyData(): array
    {
        return [
            'name' => $this->option('company-name') ?: 
                     $this->ask('Nombre de la empresa'),
            'ruc' => $this->option('company-ruc') ?: 
                    $this->ask('RUC (sin dígito verificador)'),
            'dv' => $this->option('company-dv') ?: 
                   $this->ask('Dígito verificador'),
            'address' => $this->ask('Dirección de la empresa'),
            'phone' => $this->ask('Teléfono (opcional)'),
            'email' => $this->ask('Email (opcional)'),
        ];
    }

    private function getAdminData(): array
    {
        return [
            'name' => $this->ask('Nombre del administrador'),
            'email' => $this->option('admin-email') ?: 
                      $this->ask('Email del administrador'),
            'password' => $this->option('admin-password') ?: 
                         $this->secret('Contraseña del administrador'),
        ];
    }

    private function createRoles(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrador',
                'description' => 'Acceso completo al sistema',
                'permissions' => Role::getDefaultPermissions(),
            ],
            [
                'name' => 'manager',
                'display_name' => 'Gerente',
                'description' => 'Gestión de ventas y reportes',
                'permissions' => [
                    'pos.access', 'pos.sell', 'products.view', 'products.create',
                    'sales.view', 'invoices.view', 'customers.view', 'reports.view',
                    'cash_register.open', 'cash_register.close'
                ],
            ],
            [
                'name' => 'cashier',
                'display_name' => 'Cajero',
                'description' => 'Ventas y caja',
                'permissions' => [
                    'pos.access', 'pos.sell', 'products.view', 'sales.view',
                    'customers.view', 'cash_register.open', 'cash_register.close'
                ],
            ],
            [
                'name' => 'seller',
                'display_name' => 'Vendedor',
                'description' => 'Solo ventas',
                'permissions' => [
                    'pos.access', 'pos.sell', 'products.view', 'customers.view'
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        $this->info('✅ Roles creados');
    }

    private function createCompany(array $data): Company
    {
        $company = Company::firstOrCreate([
            'ruc' => $data['ruc'],
        ], [
            'name' => $data['name'],
            'ruc' => $data['ruc'],
            'dv' => $data['dv'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'taxpayer_type' => 'CONTRIBUYENTE',
        ]);

        $this->info('✅ Empresa creada');
        return $company;
    }

    private function createMainWarehouse(Company $company): Warehouse
    {
        $warehouse = Warehouse::firstOrCreate([
            'company_id' => $company->id,
            'code' => 'MAIN',
        ], [
            'name' => 'Depósito Principal',
            'address' => $company->address,
            'phone' => $company->phone,
            'is_main' => true,
        ]);

        $this->info('✅ Depósito principal creado');
        return $warehouse;
    }

    private function createAdmin(Company $company, array $data): User
    {
        $adminRole = Role::where('name', 'admin')->first();

        $admin = User::firstOrCreate([
            'email' => $data['email'],
        ], [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'company_id' => $company->id,
            'role_id' => $adminRole->id,
            'employee_code' => 'ADM001',
            'is_active' => true,
        ]);

        $this->info('✅ Usuario administrador creado');
        return $admin;
    }

    private function createCategories(Company $company): void
    {
        $categories = [
            ['name' => 'General', 'description' => 'Productos generales', 'color' => '#007bff'],
            ['name' => 'Bebidas', 'description' => 'Bebidas y refrescos', 'color' => '#28a745'],
            ['name' => 'Comestibles', 'description' => 'Productos alimentarios', 'color' => '#ffc107'],
            ['name' => 'Limpieza', 'description' => 'Productos de limpieza', 'color' => '#17a2b8'],
            ['name' => 'Higiene', 'description' => 'Productos de higiene personal', 'color' => '#e83e8c'],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate([
                'company_id' => $company->id,
                'name' => $categoryData['name'],
            ], $categoryData);
        }

        $this->info('✅ Categorías básicas creadas');
    }

    private function createCompanyConfig(Company $company): void
    {
        CompanyConfig::firstOrCreate([
            'company_id' => $company->id,
        ], [
            'default_iva_rate' => 10.00,
            'invoice_footer_text' => 'Gracias por su preferencia',
            'ticket_footer_text' => 'Conserve este ticket como comprobante',
            'print_after_sale' => true,
            'default_print_type' => 'TICKET',
        ]);

        $this->info('✅ Configuración de empresa creada');
    }
}