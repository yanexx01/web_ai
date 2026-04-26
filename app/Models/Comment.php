<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель Comment для работы с таблицей comments.
 * Представляет комментарий к записи блога.
 */
class Comment extends Model
{
    /**
     * @var string Имя таблицы в БД
     */
    protected $table = 'comments';

    /**
     * Атрибуты, которые можно массово назначать
     */
    protected $fillable = [
        'blog_id',
        'user_id',
        'content',
        'created_at'
    ];

    /**
     * Отключаем автоматическое обновление updated_at
     */
    public $timestamps = false;

    /**
     * Получение пользователя, оставившего комментарий
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получение записи блога, к которой относится комментарий
     */
    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    /**
     * Поля модели (для документации)
     *
     * @var int|null $id ID комментария
     * @var int $blog_id ID записи блога
     * @var int $user_id ID пользователя
     * @var string $content Текст комментария
     * @var string $created_at Дата создания комментария
     */
}