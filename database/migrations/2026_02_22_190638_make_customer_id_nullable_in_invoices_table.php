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
        Schema::table('invoices', function (Blueprint $table) {
            // Eliminar la foreign key constraint
            $table->dropForeign(['customer_id']);
            
            // Hacer la columna nullable
            $table->foreignId('customer_id')->nullable()->change();
            
            // Recrear la foreign key con SET NULL en lugar de CASCADE
            $table->foreign('customer_id')
                  ->references('id')
                  ->on('customers')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Eliminar la nueva foreign key
            $table->dropForeign(['customer_id']);
            
            // Hacer la columna NOT NULL nuevamente
            $table->foreignId('customer_id')->nullable(false)->change();
            
            // Recrear la foreign key original con CASCADE
            $table->foreign('customer_id')
                  ->references('id')
                  ->on('customers')
                  ->onDelete('cascade');
        });
    }
};
