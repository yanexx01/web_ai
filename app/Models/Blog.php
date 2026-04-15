<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

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
    
    /**
     * Получить количество записей в таблице
     * 
     * @return int Количество записей
     */
    public static function count(): int
    {
        $table = static::getTable();
        $db = DB::connection()->getPdo();
        
        $sql = "SELECT COUNT(*) as count FROM {$table}";
        $stmt = $db->query($sql);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return (int) ($result['count'] ?? 0);
    }

    /**
     * Найти записи с пагинацией и сортировкой по убыванию даты создания
     * 
     * @param int $limit Количество записей на странице
     * @param int $offset Смещение
     * @return array<static> Массив AR объектов
     */
    public static function findPaginated(int $limit, int $offset): array
    {
        $table = static::getTable();
        $db = DB::connection()->getPdo();
        
        $sql = "SELECT * FROM {$table} ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$limit, $offset]);
        
        $results = [];
        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $results[] = new static($data);
        }
        
        return $results;
    }
}
