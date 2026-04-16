# Инструкция по переносу вопросов и ответов тестов в базу данных

## Обзор изменений

Вопросы и ответы на тесты были вынесены из кода в базу данных. Теперь они хранятся в двух новых таблицах:
- `questions` - вопросы теста
- `answers` - варианты ответов на вопросы

## Созданные файлы

### Миграции
1. `database/migrations/2026_04_15_000006_create_answers_table.php` - создание таблицы ответов
2. `database/migrations/2026_04_15_000007_create_questions_table.php` - создание таблицы вопросов
3. `database/migrations/2026_04_15_000008_add_answers_to_test_results_table.php` - добавление полей для хранения ответов в JSON формате

### Модели
1. `app/models/Question.php` - модель вопроса
2. `app/models/Answer.php` - модель ответа
3. `app/models/TestResult.php` - обновлена модель результата теста

### Контроллер
1. `app/Http/Controllers/TestController.php` - обновлен контроллер для работы с БД

### Представление
1. `resources/views/test/index.blade.php` - обновлен view для динамического отображения вопросов

### Сидер
1. `database/seeders/QuestionsSeeder.php` - сидер для заполнения начальными данными
2. `database/seeders/DatabaseSeeder.php` - обновлен для вызова QuestionsSeeder

## Структура базы данных

### Таблица `questions`
- `id` - первичный ключ
- `question_text` - текст вопроса
- `question_type` - тип вопроса ('radio' или 'textarea')
- `is_active` - активен ли вопрос (boolean)
- `order` - порядок отображения
- `created_at`, `updated_at` - временные метки

### Таблица `answers`
- `id` - первичный ключ
- `question_id` - внешний ключ к таблице questions
- `answer_text` - текст ответа
- `is_correct` - является ли ответ правильным (boolean)
- `order` - порядок отображения
- `created_at`, `updated_at` - временные метки

### Таблица `test_results` (обновлена)
- Добавлено поле `answers` (JSON) - хранение ответов пользователя
- Добавлено поле `total_questions` - общее количество вопросов
- Удалены поля `q1`, `q2`, `q3`

## Установка и запуск

### 1. Запуск миграций
```bash
php artisan migrate
```

### 2. Заполнение базы данных вопросами и ответами
```bash
php artisan db:seed --class=QuestionsSeeder
```

Или запустите все сидеры:
```bash
php artisan db:seed
```

## Как добавить новые вопросы

### Через сидер
Откройте `database/seeders/QuestionsSeeder.php` и добавьте новые вопросы:

```php
// Текстовый вопрос
$questionId = DB::table('questions')->insertGetId([
    'question_text' => 'Ваш вопрос',
    'question_type' => 'textarea', // или 'radio'
    'is_active' => true,
    'order' => 4, // порядковый номер
    'created_at' => now(),
    'updated_at' => now(),
]);

// Если вопрос с выбором ответа, добавьте варианты:
DB::table('answers')->insert([
    [
        'question_id' => $questionId,
        'answer_text' => 'Вариант ответа 1',
        'is_correct' => false,
        'order' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'question_id' => $questionId,
        'answer_text' => 'Правильный ответ',
        'is_correct' => true,
        'order' => 2,
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);
```

Затем запустите сидер:
```bash
php artisan db:seed --class=QuestionsSeeder
```

### Через SQL напрямую
```sql
-- Добавить вопрос
INSERT INTO questions (question_text, question_type, is_active, `order`, created_at, updated_at)
VALUES ('Ваш вопрос', 'radio', 1, 4, NOW(), NOW());

-- Получить ID последнего вопроса
SET @question_id = LAST_INSERT_ID();

-- Добавить ответы
INSERT INTO answers (question_id, answer_text, is_correct, `order`, created_at, updated_at)
VALUES 
    (@question_id, 'Неправильный ответ', 0, 1, NOW(), NOW()),
    (@question_id, 'Правильный ответ', 1, 2, NOW(), NOW());
```

## Типы вопросов

1. **textarea** - текстовый вопрос, требует развернутого ответа
   - Ответ засчитывается если длина текста > 10 символов
   
2. **radio** - вопрос с одним правильным вариантом ответа
   - Пользователь выбирает один вариант из нескольких
   - Правильность определяется полем `is_correct` в таблице answers

## Как это работает

1. При загрузке страницы теста контроллер получает все активные вопросы из БД с помощью `Question::getActiveQuestionsWithAnswers()`
2. Для каждого вопроса загружаются связанные ответы через `Answer::getByQuestionId()`
3. Валидация формируется динамически на основе типов вопросов
4. При проверке результатов:
   - Для текстовых вопросов проверяется длина ответа
   - Для вопросов с выбором сравнивается ID выбранного ответа с правильным
5. Результаты сохраняются в JSON формате в поле `answers`

## Преимущества новой структуры

1. **Гибкость** - можно добавлять/удалять/изменять вопросы без изменения кода
2. **Масштабируемость** - неограниченное количество вопросов и ответов
3. **Администрирование** - возможность управлять вопросами через админ-панель (можно добавить)
4. **Поддержка разных типов** - текстовые вопросы и вопросы с выбором ответа
