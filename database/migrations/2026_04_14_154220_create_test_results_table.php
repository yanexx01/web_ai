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
            $table->text('q1'); // Ответ на вопрос 1
            $table->string('q2'); // Ответ на вопрос 2
            $table->string('q3'); // Ответ на вопрос 3
            $table->integer('score'); // Количество правильных ответов
            $table->string('is_correct'); // Верно/неверно (passed/failed)
            $table->timestamps();
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
