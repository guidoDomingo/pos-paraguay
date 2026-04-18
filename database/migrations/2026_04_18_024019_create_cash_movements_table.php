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
        Schema::create('cash_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_register_id')->constrained('cash_registers')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['INCOME', 'EXPENSE', 'REFUND']);
            $table->decimal('amount', 12, 2);
            $table->text('description');
            $table->foreignId('sale_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_movements');
    }
};
