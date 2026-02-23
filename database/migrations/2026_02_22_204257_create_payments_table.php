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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario que registra el pago
            $table->decimal('amount', 12, 2); // Monto del pago
            $table->enum('payment_method', ['CASH', 'CARD', 'CHEQUE', 'TRANSFER']);
            $table->text('notes')->nullable(); // Notas del pago
            $table->timestamp('payment_date'); // Fecha del pago
            $table->timestamps();
            
            $table->index(['sale_id', 'payment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
