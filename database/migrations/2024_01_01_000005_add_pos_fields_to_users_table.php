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
        Schema::table('users', function (Blueprint $table) {
            $table->after('email_verified_at', function (Blueprint $table) {
                $table->foreignId('company_id')->constrained()->onDelete('cascade');
                $table->foreignId('role_id')->constrained()->onDelete('restrict');
                $table->string('employee_code', 10)->unique()->nullable();
                $table->string('phone', 20)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamp('last_login_at')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['role_id']);
            $table->dropColumn([
                'company_id',
                'role_id',
                'employee_code',
                'phone',
                'is_active',
                'last_login_at'
            ]);
        });
    }
};