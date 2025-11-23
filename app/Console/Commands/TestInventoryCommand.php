<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestInventoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:inventory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Probando funcionalidad de inventario...');
        
        $product = \App\Models\Product::first();
        
        if (!$product) {
            $this->error('No hay productos en la base de datos');
            return;
        }
        
        $this->info('Producto: ' . $product->name);
        $this->info('Stock actual: ' . ($product->stock ?? 'NULL'));
        $this->info('Track stock: ' . ($product->track_stock ? 'Sí' : 'No'));
        
        // Intentar actualizar
        $newStock = 25;
        $this->info('Intentando actualizar stock a: ' . $newStock);
        
        $result = $product->update(['stock' => $newStock]);
        $this->info('Resultado de update(): ' . ($result ? 'true' : 'false'));
        
        $product->refresh();
        $this->info('Stock después de actualizar: ' . ($product->stock ?? 'NULL'));
        
        return 0;
    }
}
