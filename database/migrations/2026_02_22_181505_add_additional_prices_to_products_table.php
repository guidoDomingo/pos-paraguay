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
        Schema::table('products', function (Blueprint $table) {
            // Nuevos precios específicos
            $table->decimal('check_price', 10, 2)->nullable()->after('wholesale_price');
            $table->string('check_price_description', 255)->nullable()->after('check_price');
            
            $table->decimal('credit_price', 10, 2)->nullable()->after('check_price_description');
            $table->string('credit_price_description', 255)->nullable()->after('credit_price');
            
            $table->decimal('special_price', 10, 2)->nullable()->after('credit_price_description');
            $table->string('special_price_description', 255)->nullable()->after('special_price');
            
            // Columna JSON para precios adicionales personalizados
            $table->json('custom_prices')->nullable()->after('special_price_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'check_price', 
                'check_price_description',
                'credit_price', 
                'credit_price_description',
                'special_price', 
                'special_price_description',
                'custom_prices'
            ]);
        });
    }
};
