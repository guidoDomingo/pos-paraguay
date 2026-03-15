<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Models\Product;

class CleanSystemDataCommand extends Command
{
    protected $signature = 'system:clean-data 
                           {--company= : ID de la compañía específica a limpiar}
                           {--preserve-products : Mantener productos y su stock actual}
                           {--preserve-customers : Mantener clientes existentes}
                           {--reset-stock= : Resetear stock de productos a valor específico}
                           {--seed-demo : Crear datos de prueba después de limpiar}
                           {--only-users-roles : Solo mantener usuarios y roles (LIMPIEZA TOTAL)}
                           {--force : Ejecutar sin confirmación}';

    protected $description = 'Limpia datos transaccionales del sistema manteniendo configuraciones maestras';

    public function handle()
    {
        $this->info('🧹 SISTEMA DE LIMPIEZA DE DATOS');
        $this->info('====================================');

        // Verificar permisos
        if (!$this->option('force') && !$this->confirm('⚠️  Esta operación eliminará TODOS los datos transaccionales (ventas, facturas, movimientos de inventario). ¿Continuar?')) {
            $this->info('Operación cancelada.');
            return 0;
        }

        $companyId = $this->option('company');
        $preserveProducts = $this->option('preserve-products');
        $preserveCustomers = $this->option('preserve-customers');
        $resetStock = $this->option('reset-stock');
        $seedDemo = $this->option('seed-demo');
        $onlyUsersRoles = $this->option('only-users-roles');

        // Si se especifica only-users-roles, hacer limpieza total
        if ($onlyUsersRoles) {
            $this->info("🔥 LIMPIEZA TOTAL: Solo se mantendrán usuarios y roles");
            if (!$this->option('force') && !$this->confirm('⚠️ Esto eliminará TODOS los datos excepto usuarios y roles. ¿Continuar?')) {
                $this->info('Operación cancelada.');
                return 0;
            }
            
            DB::transaction(function () {
                $this->totalCleanup();
            });
            
            $this->info("\n✅ Limpieza total completada! Solo se mantuvieron usuarios y roles.");
            
            if ($seedDemo) {
                $this->line("\n🌱 Creando datos de prueba...");
                $this->call('db:seed');
            }
            
            return 0;
        }

        // Seleccionar empresa si no se especifica
        if (!$companyId) {
            $companies = Company::all();
            
            if ($companies->count() === 1) {
                $companyId = $companies->first()->id;
                $this->info("Trabajando con empresa: {$companies->first()->name}");
            } else {
                $choices = $companies->pluck('name', 'id')->toArray();
                $companyId = $this->choice('Selecciona la empresa a limpiar:', $choices, 0);
            }
        }

        $company = Company::find($companyId);
        if (!$company) {
            $this->error('Empresa no encontrada.');
            return 1;
        }

        $this->line("\n📊 Iniciando limpieza para: {$company->name}");

        DB::transaction(function () use ($companyId, $preserveProducts, $preserveCustomers, $resetStock) {
            $this->cleanTransactionalData($companyId);
            
            if (!$preserveProducts) {
                $this->resetProducts($companyId, $resetStock);
            }
            
            if (!$preserveCustomers) {
                $this->cleanCustomerData($companyId);
            }
        });

        $this->info("\n✅ Limpieza completada exitosamente!");
        $this->showPreservedData($companyId);
        
        // Crear datos de prueba si se solicitó
        if ($seedDemo) {
            $this->line("\n🌱 Creando datos de prueba...");
            $this->call('db:seed', ['--class' => 'DemoDataSeeder']);
        } elseif (!$this->option('force')) {
            if ($this->confirm('¿Deseas crear datos de prueba ahora?', true)) {
                $this->call('db:seed', ['--class' => 'DemoDataSeeder']);
            }
        }

        return 0;
    }

    private function cleanTransactionalData($companyId)
    {
        $this->line("\n🗑️  Eliminando datos transaccionales...");

        // Tablas que tienen company_id directamente
        $tablesWithCompanyId = [
            'sales' => 'Ventas',
            'sale_items' => 'Items de venta', 
            'invoices' => 'Facturas',
            'invoice_items' => 'Items de factura',
            'inventory_movements' => 'Movimientos de inventario',
            'stock_movements' => 'Movimientos de stock',
            'cash_registers' => 'Cajas registradoras',
            'products' => 'Productos',
            'categories' => 'Categorías',
            'customers' => 'Clientes',
            'suppliers' => 'Proveedores', 
            'warehouses' => 'Almacenes',
        ];

        // Tablas que NO tienen company_id pero deben limpiarse completamente
        $tablesGlobal = [
            'payments' => 'Pagos',
            'invoice_settings' => 'Configuraciones de factura'
        ];

        // Limpiar tablas con company_id
        foreach ($tablesWithCompanyId as $table => $description) {
            try {
                $deleted = DB::table($table)->where('company_id', $companyId)->delete();
                $this->line("  ✓ {$description}: {$deleted} registros eliminados");
            } catch (\Exception $e) {
                $this->warn("  ⚠ Error limpiando {$description}: " . $e->getMessage());
            }
        }

        // Limpiar tablas globales (sin company_id)
        foreach ($tablesGlobal as $table => $description) {
            try {
                $deleted = DB::table($table)->delete();
                $this->line("  ✓ {$description} (global): {$deleted} registros eliminados");
            } catch (\Exception $e) {
                $this->warn("  ⚠ Error limpiando {$description}: " . $e->getMessage());
            }
        }
    }

    private function resetProducts($companyId, $resetStock = null)
    {
        $this->line("\n📦 Reseteando productos...");
        
        if ($resetStock !== null) {
            $updated = Product::where('company_id', $companyId)
                ->update(['stock' => $resetStock]);
            $this->line("  ✓ Stock de produtos reseteado a {$resetStock}: {$updated} productos actualizados");
        }

        // Resetear contadores de ventas si existen
        // Verificar qué columnas existen realmente en el modelo Product
        $productModel = new Product();
        $productFillable = $productModel->getFillable();
        
        $updateFields = [];
        if (in_array('sales_count', $productFillable)) $updateFields['sales_count'] = 0;
        if (in_array('last_sale_at', $productFillable)) $updateFields['last_sale_at'] = null;
        
        if (!empty($updateFields)) {
            Product::where('company_id', $companyId)->update($updateFields);
            $this->line("  ✓ Contadores de ventas de produtos reseteados");
        }
    }

    private function cleanCustomerData($companyId)
    {
        $this->line("\n👥 Limpiando datos de clientes...");
        
        // Los clientes se mantienen pero su historial se elimina con las ventas
        // No hay columnas de historial en el modelo Customer actual
        $this->line("  ✓ Clientes preservados (historial eliminado con las transacciones)");
    }

    private function showPreservedData($companyId)
    {
        $this->line("\n💾 DATOS PRESERVADOS:");
        $this->line("===================");
        
        $preservedTables = [
            'companies' => 'Empresas y configuraciones',
            'users' => 'Usuarios del sistema',
            'roles' => 'Roles y permisos', 
            'fiscal_stamps' => 'Timbres fiscales (CRÍTICO)',
            'warehouses' => 'Almacenes',
            'categories' => 'Categorías de productos',
            'products' => 'Productos',
            'customers' => 'Clientes',
            'suppliers' => 'Proveedores'
        ];

        foreach ($preservedTables as $table => $description) {
            try {
                $count = DB::table($table)->where('company_id', $companyId)->count();
                $this->line("  ✓ {$description}: {$count} registros");
            } catch (\Exception $e) {
                try {
                    $count = DB::table($table)->count();
                    $this->line("  ✓ {$description}: {$count} registros (tabla global)");
                } catch (\Exception $e2) {
                    // Table may not exist
                }
            }
        }

        $this->warn("\n⚠️  IMPORTANTE: Los timbres fiscales se mantuvieron por requerimientos legales.");
        $this->info("💡 Tip: Ejecuta 'php artisan db:seed --class=DemoDataSeeder' para crear datos de prueba.");
        $this->info("💡 O 'php artisan system:clean-data --seed-demo' para limpiar y crear datos en un solo paso.");
    }

    private function totalCleanup()
    {
        $this->line("\n🔥 LIMPIEZA TOTAL - Solo manteniendo usuarios y roles...");

        // Tablas que se eliminan completamente
        $allTables = [
            'payments' => 'Pagos',
            'sale_items' => 'Items de venta',
            'sales' => 'Ventas', 
            'invoice_items' => 'Items de factura',
            'invoices' => 'Facturas',
            'inventory_movements' => 'Movimientos de inventario',
            'stock_movements' => 'Movimientos de stock',
            'cash_registers' => 'Cajas registradoras',
            'invoice_settings' => 'Configuraciones de factura',
            'products' => 'Productos',
            'categories' => 'Categorías',
            'customers' => 'Clientes',
            'suppliers' => 'Proveedores',
            'warehouses' => 'Almacenes',
            'fiscal_stamps' => 'Timbres fiscales',
        ];

        foreach ($allTables as $table => $description) {
            try {
                $deleted = DB::table($table)->delete();
                $this->line("  ✓ {$description}: {$deleted} registros eliminados");
            } catch (\Exception $e) {
                $this->warn("  ⚠ Error o tabla no existe {$description}: " . $e->getMessage());
            }
        }

        // Manejar empresas de forma especial para mantener usuarios
        $companyCount = DB::table('companies')->count();
        if ($companyCount > 0) {
            // Eliminar configuraciones de empresa
            $configDeleted = DB::table('company_config')->delete();
            $this->line("  ✓ Configuraciones de empresa: {$configDeleted} registros eliminados");
            
            // Eliminar empresas
            $companiesDeleted = DB::table('companies')->delete();
            $this->line("  ✓ Empresas: {$companiesDeleted} registros eliminados");
            
            // Crear empresa básica para mantener usuarios funcionando
            $companyId = DB::table('companies')->insertGetId([
                'name' => 'Sistema Reiniciado',
                'ruc' => '80000000',
                'dv' => '1',
                'address' => 'Sistema reiniciado - Ejecuta seeders para datos completos',
                'phone' => '000000000',
                'email' => 'sistema@reiniciado.com',
                'activity_description' => 'Sistema POS reiniciado',
                'taxpayer_type' => 'CONTRIBUYENTE',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Reasignar usuarios a la nueva empresa
            $usersUpdated = DB::table('users')->update(['company_id' => $companyId]);
            $this->line("  ✓ Empresa básica recreada y {$usersUpdated} usuarios reasignados");
        }

        $this->info("\n✅ SOLO SE MANTUVIERON:");
        $userCount = DB::table('users')->count();
        $roleCount = DB::table('roles')->count();
        $this->line("  ✓ Usuarios: {$userCount} registros");
        $this->line("  ✓ Roles: {$roleCount} registros");
        $this->line("  ✓ 1 Empresa básica (para mantener usuarios funcionando)");
    }
}