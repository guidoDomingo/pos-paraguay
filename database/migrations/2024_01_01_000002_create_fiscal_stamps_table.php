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
        Schema::create('fiscal_stamps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('stamp_number', 8); // Número del timbrado
            $table->date('valid_from');
            $table->date('valid_until');
            $table->string('establishment', 3); // Establecimiento (001)
            $table->string('point_of_sale', 3); // Punto de expedición (002)
            $table->integer('current_invoice_number')->default(0);
            $table->integer('max_invoice_number')->default(999999);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['company_id', 'establishment', 'point_of_sale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fiscal_stamps');
    }
};