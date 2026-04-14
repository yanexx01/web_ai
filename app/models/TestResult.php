<?php

namespace App\models;

/**
 * Модель TestResult для работы с таблицей test_results.
 * Представляет результат прохождения теста пользователем.
 */
class TestResult extends BaseActiveRecord
{
    /**
     * @var string Имя таблицы в БД
     */
    protected static string $table = 'test_results';

    /**
     * Поля модели (для документации)
     * 
     * @var int|null $id ID записи
     * @var string $fio ФИО студента
     * @var string $group Учебная группа
     * @var string $q1 Ответ на вопрос 1
     * @var string $q2 Ответ на вопрос 2
     * @var string $q3 Ответ на вопрос 3
     * @var int $score Количество правильных ответов
     * @var string $is_correct Верно/неверно (passed/failed)
     * @var string $created_at Дата прохождения теста
     */
}
