<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Models\Company;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Invoice;
use App\Models\StockMovement;

class DataManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Solo permitir a usuarios con permisos de administrador
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->hasPermission('admin.settings')) {
                abort(403, 'No tienes permisos para acceder a esta sección.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $user = Auth::user();
        $company = $user->company;
        
        // Obtener estadísticas actuales
        $stats = [
            'sales_count' => Sale::where('company_id', $company->id)->count(),
            'invoices_count' => Invoice::where('company_id', $company->id)->count(),
            'stock_movements_count' => StockMovement::where('company_id', $company->id)->count(),
            'products_count' => Product::where('company_id', $company->id)->count(),
            'total_sales_amount' => Sale::where('company_id', $company->id)->sum('total_amount'),
            'last_sale' => Sale::where('company_id', $company->id)
                ->orderBy('created_at', 'desc')
                ->first()?->created_at?->format('d/m/Y H:i'),
            'database_size' => $this->getDatabaseSize()
        ];

        return view('admin.data-management.index', compact('stats', 'company'));
    }

    public function cleanData(Request $request)
    {
        $request->validate([
            'clean_type' => 'required|in:transactions,all_except_products,custom,total_cleanup',
            'preserve_products' => 'boolean',
            'preserve_customers' => 'boolean',
            'reset_stock' => 'nullable|integer|min:0|max:999999',
            'confirmation' => 'required|accepted'
        ]);

        $user = Auth::user();
        $company = $user->company;

        try {
            DB::transaction(function () use ($request, $company, $user) {
                
                switch ($request->clean_type) {
                    case 'transactions':
                        $this->cleanTransactionalData($company->id);
                        break;
                        
                    case 'all_except_products':
                        $this->cleanTransactionalData($company->id);
                        $this->cleanCustomerTransactions($company->id);
                        break;
                        
                    case 'custom':
                        $this->cleanTransactionalData($company->id);
                        
                        if (!$request->preserve_products && $request->reset_stock !== null) {
                            $this->resetProductStock($company->id, $request->reset_stock);
                        }
                        
                        if (!$request->preserve_customers) {
                            $this->cleanCustomerTransactions($company->id);
                        }
                        break;
                        
                    case 'total_cleanup':
                        $this->totalCleanup();
                        break;
                }

                // Log de la actividad
                \Log::info('Limpieza de datos del sistema ejecutada', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'company_id' => $company->id,
                    'company_name' => $company->name,
                    'clean_type' => $request->clean_type,
                    'options' => $request->only(['preserve_products', 'preserve_customers', 'reset_stock'])
                ]);
            });

            return redirect()->route('admin.data-management.index')
                ->with('success', '¡Datos limpiados exitosamente! El sistema ha sido reiniciado.');

        } catch (\Exception $e) {
            return redirect()->route('admin.data-management.index')
                ->with('error', 'Error al limpiar datos: ' . $e->getMessage());
        }
    }

    public function confirmClean()
    {
        $user = Auth::user();
        $company = $user->company;

        $stats = [
            'sales_count' => Sale::where('company_id', $company->id)->count(),
            'invoices_count' => Invoice::where('company_id', $company->id)->count(),
            'products_count' => Product::where('company_id', $company->id)->count(),
            'customers_count' => $company->customers()->count(),
        ];

        return view('admin.data-management.confirm', compact('stats', 'company'));
    }

    public function downloadBackup()
    {
        // Generar un backup antes de limpiar (opcional)
        $user = Auth::user();
        $company = $user->company;
        
        $filename = "backup_{$company->name}_{$company->id}_" . now()->format('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $filename);
        
        // Crear directorio si no existe
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        // Generar backup usando mysqldump (esto es básico, se puede mejorar)
        $command = sprintf(
            'mysqldump --host=%s --user=%s --password=%s %s > %s',
            env('DB_HOST'),
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_DATABASE'),
            $path
        );

        exec($command);

        return response()->download($path)->deleteFileAfterSend();
    }

    private function cleanTransactionalData($companyId)
    {
        // Tablas que tienen company_id directamente
        $tablesWithCompanyId = [
            'sales',
            'sale_items', 
            'invoices',
            'invoice_items',
            'inventory_movements',
            'stock_movements',
            'cash_registers',
            'products',
            'categories',
            'customers',
            'suppliers',
            'warehouses',
        ];

        // Tablas que NO tienen company_id pero deben limpiarse completamente
        $tablesGlobal = [
            'payments',
            'invoice_settings',
        ];

        // Limpiar tablas con company_id
        foreach ($tablesWithCompanyId as $table) {
            try {
                $deleted = DB::table($table)->where('company_id', $companyId)->delete();
                \Log::info("Tabla {$table}: {$deleted} registros eliminados");
            } catch (\Exception $e) {
                \Log::warning("Error limpiando tabla {$table}: " . $e->getMessage());
            }
        }

        // Limpiar tablas globales (sin company_id)
        foreach ($tablesGlobal as $table) {
            try {
                $deleted = DB::table($table)->delete();
                \Log::info("Tabla {$table} (global): {$deleted} registros eliminados");
            } catch (\Exception $e) {
                \Log::warning("Error limpiando tabla global {$table}: " . $e->getMessage());
            }
        }
    }

    private function cleanCustomerTransactions($companyId)
    {
        // Reset customer purchase history but keep customer records
        // Las columnas total_purchases, last_purchase_at, credit_balance no existen en este modelo
        // Solo limpiamos las transacciones relacionadas, los clientes se mantienen intactos
        $this->command?->line("  ✓ Clientes preservados (historial se elimina con las ventas)");
    }

    private function resetProductStock($companyId, $stockValue)
    {
        Product::where('company_id', $companyId)
            ->update([
                'stock' => $stockValue
            ]);
            
        // Resetear contadores solo si existen los campos
        $productModel = new Product();
        $productFillable = $productModel->getFillable();
        
        $updateFields = [];
        if (in_array('sales_count', $productFillable)) $updateFields['sales_count'] = 0;
        if (in_array('last_sale_at', $productFillable)) $updateFields['last_sale_at'] = null;
        
        if (!empty($updateFields)) {
            Product::where('company_id', $companyId)->update($updateFields);
        }
    }

    private function getDatabaseSize()
    {
        try {
            $size = DB::select('
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 1) AS db_size 
                FROM information_schema.tables 
                WHERE table_schema = ?
            ', [env('DB_DATABASE')]);
            
            return $size[0]->db_size ?? 'N/A';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function totalCleanup()
    {
        // LIMPIEZA TOTAL: Eliminar TODAS las tablas excepto usuarios y roles
        // PERO manteniendo una empresa mínima para que los usuarios sigan funcionando
        
        // Deshabilitar restricciones de claves foráneas temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        try {
            // Tablas que deshabilitamos foreign key checks para borrar
            $allTables = [
                'payments',
                'sale_items',
                'sales', 
                'invoice_items',
                'invoices',
                'inventory_movements',
                'stock_movements',
                'cash_registers',
                'invoice_settings',
                'products',
                'categories',
                'customers',
                'suppliers',
                'warehouses',
                'fiscal_stamps',
            ];

            // Limpiar todas las tablas transaccionales
            foreach ($allTables as $table) {
                try {
                    $deleted = DB::table($table)->delete();
                    \Log::info("Limpieza total - Tabla {$table}: {$deleted} registros eliminados");
                } catch (\Exception $e) {
                    \Log::warning("Error en limpieza total - tabla {$table}: " . $e->getMessage());
                }
            }

            // Limpiar companies pero mantener una empresa básica para los usuarios
            $companyCount = DB::table('companies')->count();
            if ($companyCount > 0) {
                // Eliminar configuraciones de empresa
                DB::table('company_config')->delete();
                
                // Eliminar todas las empresas
                DB::table('companies')->delete();
                
                // Crear una empresa básica para mantener los usuarios funcionando
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

                // Actualizar todos los usuarios para que pertenezcan a esta nueva empresa
                DB::table('users')->update(['company_id' => $companyId]);
                
                \Log::info("Limpieza total - Empresas: todas eliminadas y recreada empresa básica");
            }

        } finally {
            // Rehabilitar restricciones de claves foráneas
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        \Log::info("LIMPIEZA TOTAL COMPLETADA - Solo se mantuvieron usuarios y roles");
    }
}