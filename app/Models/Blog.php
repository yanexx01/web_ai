<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель Blog для работы с таблицей blogs.
 * Представляет запись в блоге.
 */
class Blog extends Model
{
    /**
     * @var string Имя таблицы в БД
     */
    protected $table = 'blogs';

    /**
     * Атрибуты, которые можно массово назначать
     */
    protected $fillable = [
        'topic',
        'image',
        'message',
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
     * @var string $topic Тема сообщения
     * @var string|null $image Изображение
     * @var string $message Текст сообщения
     * @var string $created_at Дата создания записи
     */
}
