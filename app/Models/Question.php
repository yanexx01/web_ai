<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель Question для работы с таблицей questions.
 * Представляет вопрос теста.
 */
class Question extends Model
{
    /**
     * @var string Имя таблицы в БД
     */
    protected $table = 'questions';

    /**
     * Атрибуты, которые можно массово назначать
     */
    protected $fillable = [
        'question_text',
        'question_type',
        'is_active',
        'order',
        'keywords'
    ];

    /**
     * Отключаем автоматическое обновление updated_at
     */
    public $timestamps = false;

    /**
     * Получить все активные вопросы с ответами, отсортированные по порядку.
     * 
     * @return array<Question> Массив объектов Question
     */
    public static function getActiveQuestionsWithAnswers(): array
    {
        return self::where('is_active', 1)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->with('answers')
            ->get()
            ->toArray();
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


    public function getKeywords(): array
    {
        if (empty($this->keywords)) {
            return [];
        }

        // Разделяем по запятой или новой строке, убираем пробелы и пустые значения
        $keywords = preg_split('/[,\n]+/', $this->keywords);
        $keywords = array_map('trim', $keywords);
        $keywords = array_filter($keywords, function($kw) {
            return mb_strlen($kw) > 0;
        });

        return array_values($keywords);
    }

    /**
     * Проверить, содержит ли ответ хотя бы одно ключевое слово.
     *
     * @param string $answer Ответ пользователя
     * @return bool
     */
    public function checkAnswerContainsKeyword(string $answer): bool
    {
        $keywords = $this->getKeywords();

        // Если ключевые слова не заданы, используем старую логику (длина > 10)
        if (empty($keywords)) {
            return mb_strlen($answer) > 10;
        }

        $answerLower = mb_strtolower($answer);

        foreach ($keywords as $keyword) {
            $keywordLower = mb_strtolower($keyword);
            if (mb_strpos($answerLower, $keywordLower) !== false) {
                return true;
            }
        }

        return false;
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

    /**
     * Связь с ответами
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
