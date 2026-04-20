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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text'); // Текст вопроса
            $table->string('question_type')->default('radio'); // Тип вопроса: radio, textarea
            $table->boolean('is_active')->default(true); // Активен ли вопрос
            $table->integer('order')->default(0); // Порядок отображения
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
