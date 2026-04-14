<?php

namespace App\models;

use Illuminate\Support\Facades\DB;
use PDOException;

/**
 * Базовый класс ActiveRecord для работы с базой данных.
 * Реализует паттерн ActiveRecord.
 */
abstract class BaseActiveRecord
{
    /**
     * @var int|null ID записи
     */
    public ?int $id = null;

    /**
     * @var string Имя таблицы в БД
     */
    protected static string $table = '';

    /**
     * @var array Атрибуты модели (заполняются из БД)
     */
    protected array $attributes = [];

    /**
     * @var bool Флаг: является ли запись новой (еще не сохранена в БД)
     */
    private bool $isNew = true;

    /**
     * Конструктор
     * 
     * @param array $data Данные для инициализации модели
     */
    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->populate($data);
        }
    }

    /**
     * Получить имя таблицы
     * 
     * @return string
     */
    public static function getTable(): string
    {
        if (static::$table === '') {
            // Автоматическое определение имени таблицы из имени класса
            $className = basename(str_replace('\\', '/', static::class));
            static::$table = strtolower($className) . 's';
        }
        return static::$table;
    }

    /**
     * Заполнить атрибуты модели данными
     * 
     * @param array $data
     * @return void
     */
    protected function populate(array $data): void
    {
        foreach ($data as $key => $value) {
            if ($key === 'id') {
                $this->id = $value !== null ? (int)$value : null;
            } else {
                $this->attributes[$key] = $value;
            }
        }
        
        // Если есть id, значит запись уже существует в БД
        if ($this->id !== null) {
            $this->isNew = false;
        }
    }

    /**
     * Получить значение атрибута
     * 
     * @param string $key
     * @return mixed
     */
    public function __get(string $key): mixed
    {
        if ($key === 'id') {
            return $this->id;
        }
        return $this->attributes[$key] ?? null;
    }

    /**
     * Установить значение атрибута
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set(string $key, mixed $value): void
    {
        if ($key === 'id') {
            $this->id = $value !== null ? (int)$value : null;
        } else {
            $this->attributes[$key] = $value;
        }
    }

    /**
     * Проверить существование атрибута
     * 
     * @param string $key
     * @return bool
     */
    public function __isset(string $key): bool
    {
        if ($key === 'id') {
            return $this->id !== null;
        }
        return isset($this->attributes[$key]);
    }

    /**
     * Сохранить запись в базу данных.
     * Создает новую запись (INSERT), если это новая модель.
     * Обновляет существующую запись (UPDATE), если у модели есть id.
     * 
     * @return static Возвращает текущий AR объект (this)
     * @throws PDOException
     */
    public function save(): static
    {
        $table = static::getTable();
        $db = DB::connection()->getPdo();

        if ($this->isNew || $this->id === null) {
            // INSERT - создание новой записи
            $columns = array_keys($this->attributes);
            $placeholders = implode(', ', array_fill(0, count($columns), '?'));
            $columnNames = implode(', ', $columns);
            
            $sql = "INSERT INTO {$table} ({$columnNames}) VALUES ({$placeholders})";
            $stmt = $db->prepare($sql);
            
            $values = array_values($this->attributes);
            $stmt->execute($values);
            
            // Получаем ID последней вставленной записи
            $this->id = (int) $db->lastInsertId();
            $this->isNew = false;
        } else {
            // UPDATE - обновление существующей записи
            $columns = array_keys($this->attributes);
            $setClause = implode(' = ?, ', $columns) . ' = ?';
            
            $sql = "UPDATE {$table} SET {$setClause} WHERE id = ?";
            $stmt = $db->prepare($sql);
            
            $values = array_values($this->attributes);
            $values[] = $this->id;
            
            $stmt->execute($values);
        }

        return $this;
    }

    /**
     * Удалить запись из базы данных.
     * 
     * @return bool true при успешном удалении
     * @throws PDOException
     */
    public function delete(): bool
    {
        if ($this->id === null) {
            return false;
        }

        $table = static::getTable();
        $db = DB::connection()->getPdo();
        
        $sql = "DELETE FROM {$table} WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$this->id]);
        
        return true;
    }

    /**
     * Найти запись по ID.
     * 
     * @param int $id ID записи
     * @return static|null AR объект или null, если запись не найдена
     * @throws PDOException
     */
    public static function find(int $id): ?static
    {
        $table = static::getTable();
        $db = DB::connection()->getPdo();
        
        $sql = "SELECT * FROM {$table} WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($data === false) {
            return null;
        }
        
        return new static($data);
    }

    /**
     * Найти все записи в таблице.
     * 
     * @return array<static> Массив AR объектов
     * @throws PDOException
     */
    public static function findAll(): array
    {
        $table = static::getTable();
        $db = DB::connection()->getPdo();
        
        $sql = "SELECT * FROM {$table}";
        $stmt = $db->query($sql);
        
        $results = [];
        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $results[] = new static($data);
        }
        
        return $results;
    }
}
