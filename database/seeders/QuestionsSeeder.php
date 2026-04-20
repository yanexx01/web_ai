<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Очищаем существующие данные
        DB::table('answers')->delete();
        DB::table('questions')->delete();

        // Вопрос 1 - текстовый (textarea)
        $question1Id = DB::table('questions')->insertGetId([
            'question_text' => 'Опишите основные принципы обеспечения безопасности жизнедеятельности.',
            'question_type' => 'textarea',
            'keywords' => 'безопасность, воздействие, среда, защита, опасность',
            'is_active' => true,
            'order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Вопрос 2 - с выбором ответа (radio)
        $question2Id = DB::table('questions')->insertGetId([
            'question_text' => 'Что является основной целью БЖД?',
            'question_type' => 'radio',
            'is_active' => true,
            'order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Ответы на вопрос 2
        DB::table('answers')->insert([
            [
                'question_id' => $question2Id,
                'answer_text' => 'Обеспечение комфортных условий труда',
                'is_correct' => false,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => $question2Id,
                'answer_text' => 'Защита человека от опасностей',
                'is_correct' => true,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => $question2Id,
                'answer_text' => 'Повышение производительности',
                'is_correct' => false,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Вопрос 3 - с выбором ответа (radio)
        $question3Id = DB::table('questions')->insertGetId([
            'question_text' => 'Какие факторы относятся к опасным?',
            'question_type' => 'radio',
            'is_active' => true,
            'order' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Ответы на вопрос 3
        DB::table('answers')->insert([
            [
                'question_id' => $question3Id,
                'answer_text' => 'Физические и химические',
                'is_correct' => true,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => $question3Id,
                'answer_text' => 'Социальные и экономические',
                'is_correct' => false,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
