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
        Schema::table('invoice_settings', function (Blueprint $table) {
            $table->string('default_printer')->nullable()->after('default_iva_rate');
            $table->string('ticket_printer')->nullable()->after('default_printer');
            $table->string('invoice_printer')->nullable()->after('ticket_printer');
            $table->boolean('auto_print_tickets')->default(false)->after('invoice_printer');
            $table->boolean('auto_print_invoices')->default(false)->after('auto_print_tickets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            $table->dropColumn([
                'default_printer',
                'ticket_printer', 
                'invoice_printer',
                'auto_print_tickets',
                'auto_print_invoices'
            ]);
        });
    }
};
