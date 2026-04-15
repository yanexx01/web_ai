<?php

namespace App\Models;

/**
 * Модель Blog для работы с таблицей blogs.
 * Представляет запись в блоге.
 */
class Blog extends BaseActiveRecord
{
    /**
     * @var string Имя таблицы в БД
     */
    protected static string $table = 'blogs';

    /**
     * Поля модели (для документации)
     * 
     * @var int|null $id ID записи
     * @var string $topic Тема сообщения
     * @var string|null $image Изображение
     * @var string $message Текст сообщения
     * @var string $created_at Дата создания записи
     */
}
