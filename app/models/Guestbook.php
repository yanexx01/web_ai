<?php

namespace App\models;

/**
 * Модель Guestbook для работы с таблицей guestbooks.
 * Представляет запись в гостевой книге.
 */
class Guestbook extends BaseActiveRecord
{
    /**
     * @var string Имя таблицы в БД
     */
    protected static string $table = 'guestbooks';

    /**
     * Поля модели (для документации)
     * 
     * @var int|null $id ID записи
     * @var string $lastname Фамилия
     * @var string $firstname Имя
     * @var string $middlename Отчество
     * @var string $email E-mail
     * @var string $message Текст отзыва
     * @var string $created_at Дата создания записи
     */
}
