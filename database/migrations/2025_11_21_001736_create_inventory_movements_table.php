<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['in', 'out', 'adjustment', 'sale', 'purchase', 'return']);
            $table->integer('quantity');
            $table->integer('previous_stock')->default(0);
            $table->integer('new_stock')->default(0);
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->text('reason')->nullable();
            $table->json('metadata')->nullable(); // Para datos adicionales como número de factura, etc.
            $table->timestamps();
            
            $table->index(['company_id', 'product_id']);
            $table->index(['company_id', 'type']);
            $table->index(['company_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
