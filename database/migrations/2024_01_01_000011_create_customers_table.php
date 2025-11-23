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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('ruc', 20)->nullable();
            $table->string('dv', 1)->nullable();
            $table->string('ci', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->date('birth_date')->nullable();
            $table->decimal('credit_limit', 12, 2)->default(0);
            $table->enum('customer_type', ['INDIVIDUAL', 'COMPANY'])->default('INDIVIDUAL');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['company_id', 'ruc']);
            $table->unique(['company_id', 'ci']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};