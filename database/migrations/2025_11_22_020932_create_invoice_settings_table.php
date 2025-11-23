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
        Schema::create('invoice_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('Mi Empresa');
            $table->string('company_ruc')->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_logo')->nullable();
            
            // Configuración de factura
            $table->string('invoice_prefix')->default('FACT-');
            $table->string('invoice_suffix')->default('');
            $table->integer('invoice_counter')->default(1);
            $table->boolean('invoice_auto_increment')->default(true);
            
            // Configuración de ticket
            $table->string('ticket_prefix')->default('TKT-');
            $table->string('ticket_suffix')->default('');
            $table->integer('ticket_counter')->default(1);
            $table->boolean('ticket_auto_increment')->default(true);
            
            // Configuración de impresión
            $table->enum('paper_size', ['A4', 'Letter', 'Ticket'])->default('A4');
            $table->enum('orientation', ['portrait', 'landscape'])->default('portrait');
            $table->text('footer_text')->nullable();
            $table->text('terms_conditions')->nullable();
            
            // IVA por defecto
            $table->decimal('default_iva_rate', 5, 2)->default(10.00);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_settings');
    }
};
