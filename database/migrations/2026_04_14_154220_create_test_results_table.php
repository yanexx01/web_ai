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
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->string('fio'); // ФИО студента
            $table->string('user_group'); // Учебная группа
            $table->json('answers'); // Ответы на вопросы в формате JSON
            $table->integer('score'); // Количество правильных ответов
            $table->integer('total_questions'); // Общее количество вопросов
            $table->string('is_correct'); // Верно/неверно (passed/failed)
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
