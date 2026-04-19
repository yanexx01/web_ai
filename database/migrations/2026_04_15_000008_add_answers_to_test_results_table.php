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
        Schema::table('test_results', function (Blueprint $table) {
            // Добавляем поле для хранения ответов в формате JSON
            $table->json('answers')->nullable()->after('user_group');
            // Добавляем поле для общего количества вопросов
            $table->integer('total_questions')->default(0)->after('score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_results', function (Blueprint $table) {
            $table->dropColumn('answers');
            $table->dropColumn('total_questions');
        });
    }
};
