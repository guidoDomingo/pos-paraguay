<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\InventoryController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestFormSubmission extends Command
{
    protected $signature = 'test:form-submission';
    protected $description = 'Test form submission like the web form';

    public function handle()
    {
        $this->info('Probando el envío del formulario de ajuste de inventario...');
        
        // Simular usuario autenticado
        $user = User::first();
        if (!$user) {
            $this->error('No hay usuarios en la base de datos');
            return 1;
        }
        
        Auth::login($user);
        $this->info("Usuario autenticado: {$user->email}");
        
        // Simular el request como si viniera del formulario
        $request = new Request([
            'product_id' => '1',
            'adjustment_type' => 'add',
            'quantity' => '10',
            'reason' => 'Prueba desde comando con usuario autenticado'
        ]);
        
        $controller = new InventoryController();
        
        try {
            $this->info('Ejecutando storeAdjustment...');
            $response = $controller->storeAdjustment($request);
            $this->info('Respuesta recibida correctamente');
            
            // Verificar el stock después del ajuste
            $product = \App\Models\Product::find(1);
            $this->info("Stock después del ajuste: {$product->stock}");
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
        
        return 0;
    }
}