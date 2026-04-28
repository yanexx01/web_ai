<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель Guestbook для работы с таблицей guestbooks.
 * Представляет запись в гостевой книге.
 */
class Guestbook extends Model
{
    /**
     * @var string Имя таблицы в БД
     */
    protected $table = 'guestbooks';

    /**
     * Атрибуты, которые можно массово назначать
     */
    protected $fillable = [
        'lastname',
        'firstname',
        'middlename',
        'email',
        'message',
        'created_at'
    ];

    /**
     * Отключаем автоматическое обновление updated_at
     */
    public $timestamps = false;
    /**
     * Преобразование типов атрибутов
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];
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
