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
        Schema::create('company_config', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('default_fiscal_stamp_id')->nullable()->constrained('fiscal_stamps');
            $table->decimal('default_iva_rate', 5, 2)->default(10.00);
            $table->string('invoice_footer_text')->nullable();
            $table->string('ticket_footer_text')->nullable();
            $table->boolean('print_after_sale')->default(true);
            $table->enum('default_print_type', ['TICKET', 'INVOICE'])->default('TICKET');
            $table->json('printer_config')->nullable();
            $table->timestamps();

            $table->unique('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_config');
    }
};