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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->string('code', 50)->unique(); // Código interno/barras
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('cost_price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2);
            $table->decimal('wholesale_price', 10, 2)->nullable();
            $table->enum('iva_type', ['EXENTO', 'IVA_5', 'IVA_10'])->default('IVA_10');
            $table->string('unit', 20)->default('UNIDAD'); // UNIDAD, KG, LT, etc.
            $table->decimal('min_stock', 8, 2)->default(0);
            $table->decimal('max_stock', 8, 2)->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('track_stock')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['company_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};