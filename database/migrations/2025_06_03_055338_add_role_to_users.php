<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations – додаємо колонку role.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // додаємо після email, можна обрати інше місце
            $table->string('role')
                  ->default('user')
                  ->after('email');
        });
    }

    /**
     * Reverse the migrations – прибираємо колонку role.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
