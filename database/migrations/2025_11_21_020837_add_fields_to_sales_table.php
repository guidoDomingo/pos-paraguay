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
            $table->string('invoice_number', 50)->nullable()->after('sale_number');
            $table->string('customer_name')->nullable()->after('customer_id');
            $table->string('customer_document', 50)->nullable()->after('customer_name');
            $table->decimal('subtotal_amount', 12, 2)->nullable()->after('subtotal');
            $table->decimal('amount_paid', 12, 2)->nullable()->after('payment_method');
            $table->decimal('change_amount', 12, 2)->default(0)->after('amount_paid');
            
            $table->index(['invoice_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['invoice_number']);
            $table->dropColumn([
                'invoice_number',
                'customer_name',
                'customer_document',
                'subtotal_amount',
                'amount_paid',
                'change_amount'
            ]);
        });
    }
};
