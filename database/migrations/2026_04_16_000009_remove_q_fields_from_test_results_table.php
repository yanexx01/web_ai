<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('test_results', function (Blueprint $table) {
            // Проверяем существование колонок перед удалением
            if (Schema::hasColumn('test_results', 'q1')) {
                $table->dropColumn('q1');
            }
            if (Schema::hasColumn('test_results', 'q2')) {
                $table->dropColumn('q2');
            }
            if (Schema::hasColumn('test_results', 'q3')) {
                $table->dropColumn('q3');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_results', function (Blueprint $table) {
            $table->text('q1')->nullable();
            $table->text('q2')->nullable();
            $table->text('q3')->nullable();
        });
    }
};
