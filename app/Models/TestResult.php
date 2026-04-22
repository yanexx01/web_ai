<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель TestResult для работы с таблицей test_results.
 * Представляет результат прохождения теста пользователем.
 */
class TestResult extends Model
{
    /**
     * @var string Имя таблицы в БД
     */
    protected $table = 'test_results';

    /**
     * Атрибуты, которые можно массово назначать
     */
    protected $fillable = [
        'fio',
        'user_group',
        'answers',
        'score',
        'total_questions',
        'is_correct',
        'created_at'
    ];

    /**
     * Отключаем автоматическое обновление updated_at
     */
    public $timestamps = false;

    /**
     * Поля модели (для документации)
     * 
     * @var int|null $id ID записи
     * @var string $fio ФИО студента
     * @var string $user_group Учебная группа
     * @var array|string $answers Ответы на вопросы (JSON или массив)
     * @var int $score Количество правильных ответов
     * @var int $total_questions Общее количество вопросов
     * @var string $is_correct Верно/неверно (passed/failed)
     * @var string $created_at Дата прохождения теста
     */
    
    /**
     * Получить ответы как массив.
     * 
     * @return array
     */
    public function getAnswersArray(): array
    {
        if (is_string($this->answers)) {
            $decoded = json_decode($this->answers, true);
            return is_array($decoded) ? $decoded : [];
        }
        return is_array($this->answers) ? $this->answers : [];
    }
}
