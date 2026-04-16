<?php

namespace App\Models;

/**
 * Модель Question для работы с таблицей questions.
 * Представляет вопрос теста.
 */
class Question extends BaseActiveRecord
{
    /**
     * @var string Имя таблицы в БД
     */
    protected static string $table = 'questions';

    /**
     * Получить все активные вопросы с ответами, отсортированные по порядку.
     * 
     * @return array<Question> Массив объектов Question
     */
    public static function getActiveQuestionsWithAnswers(): array
    {
        $table = static::getTable();
        $db = \Illuminate\Support\Facades\DB::connection()->getPdo();
        
        $sql = "SELECT * FROM {$table} WHERE is_active = 1 ORDER BY `order` ASC, id ASC";
        $stmt = $db->query($sql);
        
        $questions = [];
        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $question = new static($data);
            // Загружаем ответы для вопроса
            $question->answers = Answer::getByQuestionId($data['id']);
            $questions[] = $question;
        }
        
        return $questions;
    }

    /**
     * Проверить, является ли вопрос текстовым (textarea).
     * 
     * @return bool
     */
    public function isTextareaType(): bool
    {
        return $this->question_type === 'textarea';
    }

    /**
     * Получить правильный ответ для вопроса (для вопросов с выбором).
     * 
     * @return Answer|null
     */
    public function getCorrectAnswer(): ?Answer
    {
        if (!isset($this->answers) || empty($this->answers)) {
            return null;
        }
        
        foreach ($this->answers as $answer) {
            if ($answer->is_correct) {
                return $answer;
            }
        }
        
        return null;
    }
}
