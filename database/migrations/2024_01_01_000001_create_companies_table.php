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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('trade_name')->nullable();
            $table->string('ruc', 20)->unique();
            $table->string('dv', 1); // Dígito verificador
            $table->text('address');
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('activity_description')->nullable();
            $table->enum('taxpayer_type', ['CONTRIBUYENTE', 'NO_CONTRIBUYENTE'])->default('CONTRIBUYENTE');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};