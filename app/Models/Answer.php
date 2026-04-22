<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Модель Answer для работы с таблицей answers.
 * Представляет вариант ответа на вопрос теста.
 */
class Answer extends Model
{
    /**
     * @var string Имя таблицы в БД
     */
    protected $table = 'answers';

    /**
     * Атрибуты, которые можно массово назначать
     */
    protected $fillable = [
        'question_id',
        'answer_text',
        'is_correct',
        'order'
    ];

    /**
     * Отключаем автоматическое обновление updated_at
     */
    public $timestamps = false;

    /**
     * Получить все ответы для указанного вопроса.
     * 
     * @param int $questionId ID вопроса
     * @return array<Answer> Массив объектов Answer
     */
    public static function getByQuestionId(int $questionId): array
    {
        return self::where('question_id', $questionId)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
    }

    /**
     * Получить правильный ответ для указанного вопроса.
     * 
     * @param int $questionId ID вопроса
     * @return Answer|null
     */
    public static function getCorrectByQuestionId(int $questionId): ?self
    {
        return self::where('question_id', $questionId)
            ->where('is_correct', 1)
            ->first();
    }
}
