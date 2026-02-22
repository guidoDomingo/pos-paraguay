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
            // Campos de facturación electrónica FacturaSend
            $table->boolean('is_electronic')->default(false)->comment('Indica si es factura electrónica');
            $table->string('facturasend_id')->nullable()->comment('ID en FacturaSend');
            $table->string('cdc', 44)->nullable()->unique()->comment('Código de Control del Documento (CDC) - 44 dígitos únicos');
            $table->string('electronic_status')->default('pending')->comment('Estado: pending, generated, approved, rejected, error');
            $table->timestamp('electronic_sent_at')->nullable()->comment('Fecha de envío a FacturaSend');
            $table->timestamp('electronic_approved_at')->nullable()->comment('Fecha de aprobación por SET/SIFEN');
            $table->longText('xml_data')->nullable()->comment('XML generado por FacturaSend');
            $table->text('qr_data')->nullable()->comment('Datos del código QR generado');
            $table->string('lote_id')->nullable()->comment('ID del lote en FacturaSend');
            $table->text('electronic_error')->nullable()->comment('Mensaje de error en caso de fallo');
            $table->string('numero_electronico', 15)->nullable()->comment('Número electrónico formato 001-001-0000001');
            
            // Índices para consultas rápidas
            $table->index('is_electronic');
            $table->index('electronic_status');
            $table->index('cdc');
            $table->index('lote_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'is_electronic',
                'facturasend_id',
                'cdc', 
                'electronic_status',
                'electronic_sent_at',
                'electronic_approved_at',
                'xml_data',
                'qr_data',
                'lote_id',
                'electronic_error',
                'numero_electronico'
            ]);
        });
    }
};
