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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('fiscal_stamp_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number', 15); // 001-002-0000001
            $table->string('stamp_number', 8);
            $table->string('establishment', 3);
            $table->string('point_of_sale', 3);
            $table->string('sequential_number', 7);
            $table->decimal('subtotal_exento', 12, 2)->default(0);
            $table->decimal('subtotal_iva_5', 12, 2)->default(0);
            $table->decimal('subtotal_iva_10', 12, 2)->default(0);
            $table->decimal('total_iva_5', 12, 2)->default(0);
            $table->decimal('total_iva_10', 12, 2)->default(0);
            $table->decimal('total_iva', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->string('customer_name');
            $table->string('customer_ruc', 20)->nullable();
            $table->text('customer_address')->nullable();
            $table->enum('condition', ['CONTADO', 'CREDITO'])->default('CONTADO');
            $table->date('invoice_date');
            $table->text('observations')->nullable();
            $table->boolean('is_printed')->default(false);
            $table->timestamp('printed_at')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'invoice_number']);
            $table->index(['fiscal_stamp_id', 'sequential_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};