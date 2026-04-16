<?php

namespace App\Models;

/**
 * Модель Answer для работы с таблицей answers.
 * Представляет вариант ответа на вопрос теста.
 */
class Answer extends BaseActiveRecord
{
    /**
     * @var string Имя таблицы в БД
     */
    protected static string $table = 'answers';

    /**
     * Получить все ответы для указанного вопроса.
     * 
     * @param int $questionId ID вопроса
     * @return array<Answer> Массив объектов Answer
     */
    public static function getByQuestionId(int $questionId): array
    {
        $table = static::getTable();
        $db = \Illuminate\Support\Facades\DB::connection()->getPdo();
        
        $sql = "SELECT * FROM {$table} WHERE question_id = ? ORDER BY `order` ASC, id ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$questionId]);
        
        $answers = [];
        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $answers[] = new static($data);
        }
        
        return $answers;
    }

    /**
     * Получить правильный ответ для указанного вопроса.
     * 
     * @param int $questionId ID вопроса
     * @return Answer|null
     */
    public static function getCorrectByQuestionId(int $questionId): ?self
    {
        $table = static::getTable();
        $db = \Illuminate\Support\Facades\DB::connection()->getPdo();
        
        $sql = "SELECT * FROM {$table} WHERE question_id = ? AND is_correct = 1 LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([$questionId]);
        
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($data === false) {
            return null;
        }
        
        return new static($data);
    }
}
