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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('cash_register_id')->nullable()->constrained()->onDelete('set null');
            $table->string('sale_number', 20)->unique();
            $table->enum('sale_type', ['TICKET', 'INVOICE']);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->enum('payment_method', ['CASH', 'CARD', 'TRANSFER', 'CREDIT']);
            $table->enum('status', ['COMPLETED', 'CANCELLED', 'REFUNDED'])->default('COMPLETED');
            $table->text('notes')->nullable();
            $table->timestamp('sale_date');
            $table->timestamps();

            $table->index(['company_id', 'sale_date']);
            $table->index(['warehouse_id', 'sale_date']);
            $table->index(['user_id', 'sale_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};