<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class CheckProducts extends Command
{
    protected $signature = 'check:products';
    protected $description = 'Check products and their stock';

    public function handle()
    {
        $products = Product::all(['id', 'name', 'stock']);
        
        $this->info('Estado actual de los productos:');
        $this->table(['ID', 'Nombre', 'Stock'], $products->toArray());
        
        return 0;
    }
}