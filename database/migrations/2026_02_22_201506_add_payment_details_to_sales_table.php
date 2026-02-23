<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Modificar payment_method para incluir CHEQUE
            DB::statement("ALTER TABLE sales MODIFY COLUMN payment_method ENUM('CASH', 'CARD', 'CHEQUE', 'TRANSFER', 'CREDIT') NOT NULL");
            
            // Agregar condición de venta (CONTADO/CREDITO)
            $table->enum('sale_condition', ['CONTADO', 'CREDITO'])->default('CONTADO')->after('payment_method');
            
            // Agregar saldo pendiente (para ventas a crédito) - amount_paid ya existe
            $table->decimal('balance_due', 12, 2)->default(0)->after('change_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Revertir payment_method al estado anterior
            DB::statement("ALTER TABLE sales MODIFY COLUMN payment_method ENUM('CASH', 'CARD', 'TRANSFER', 'CREDIT') NOT NULL");
            
            // Eliminar columnas agregadas
            $table->dropColumn(['sale_condition', 'balance_due']);
        });
    }
};
