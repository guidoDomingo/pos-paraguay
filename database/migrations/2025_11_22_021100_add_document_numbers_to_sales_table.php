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
        Schema::table('sales', function (Blueprint $table) {
            // Solo agregar columnas que no existan
            if (!Schema::hasColumn('sales', 'ticket_number')) {
                $table->string('ticket_number')->nullable()->after('invoice_number');
            }
            if (!Schema::hasColumn('sales', 'document_type')) {
                $table->enum('document_type', ['ticket', 'factura'])->default('ticket')->after('ticket_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'ticket_number')) {
                $table->dropColumn('ticket_number');
            }
            if (Schema::hasColumn('sales', 'document_type')) {
                $table->dropColumn('document_type');
            }
        });
    }
};
