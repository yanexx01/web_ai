<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Количество записей на странице (константа)
     */
    private const PER_PAGE = 5;

    /**
     * Отображение страницы "Мой Блог"
     */
    public function index(Request $request)
    {
        // Получаем номер страницы
        $page = $request->input('page', 1);
        
        // Получаем общее количество записей
        $totalItems = Blog::count();
        
        // Рассчитываем общее количество страниц
        $totalPages = $totalItems > 0 ? ceil($totalItems / self::PER_PAGE) : 1;
        
        // Ограничиваем номер страницы допустимым диапазоном
        $page = max(1, min($page, $totalPages));
        
        // Получаем записи из БД с пагинацией и сортировкой по убыванию даты
        $blogs = Blog::orderBy('created_at', 'desc')
            ->offset(($page - 1) * self::PER_PAGE)
            ->limit(self::PER_PAGE)
            ->get();
        
        return view('blog.index', [
            'pageTitle' => 'Мой Блог',
            'pageName' => 'blog',
            'blogs' => $blogs,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems
        ]);
    }

    /**
     * Отображение формы добавления записи блога
     */
    public function create()
    {
        return view('blog.create', [
            'pageTitle' => 'Добавить запись в блог',
            'pageName' => 'blog'
        ]);
    }

    /**
     * Обработка формы добавления записи блога
     */
    public function store(Request $request)
    {
        // Валидация данных
        $validator = $request->validate([
            'topic' => 'required|string|max:255',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'message' => 'required|string',
        ], [
            'image.max' => 'Размер изображения не должен превышать 10MB. Пожалуйста, выберите изображение меньшего размера.',
            'image.mimes' => 'Неверный формат изображения. Допустимые форматы: jpg, jpeg, png, gif, webp.',
            'image.file' => 'Файл изображения поврежден или не может быть загружен.',
        ]);

        // Создаем новую запись в БД
        $blogData = [
            'topic' => $request->input('topic'),
            'message' => $request->input('message'),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        
        // Обработка загрузки изображения
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            if (!$image->isValid()) {
                return redirect('/blog/create')
                    ->withErrors(['image' => 'Ошибка при загрузке файла. Попробуйте еще раз.'])
                    ->withInput();
            }
            
            // Генерируем уникальное имя файла
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            try {
                $imagePath = $image->storeAs('blog_images', $imageName, 'public');
                $blogData['image'] = $imagePath;
            } catch (\Exception $e) {
                return redirect('/blog/create')
                    ->withErrors(['image' => 'Не удалось сохранить изображение: ' . $e->getMessage()])
                    ->withInput();
            }
        }
        
        Blog::create($blogData);

        return redirect('/blog')->with('success', 'Запись успешно добавлена!');
    }

    /**
     * Отображение страницы "Загрузка сообщений блога" (CSV импорт)
     */
    public function showUploadForm()
    {
        return view('blog.upload', [
            'pageTitle' => 'Загрузка сообщений блога',
            'pageName' => 'blog'
        ]);
    }

    /**
     * Обработка загрузки CSV файла с сообщениями блога
     */
    public function uploadCsv(Request $request)
    {
        // Валидация файла CSV
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        // Открываем файл для чтения
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Пропускаем заголовок, если он есть
            $header = fgetcsv($handle);
            
            // Проверяем, является ли первая строка заголовком
            $isHeader = false;
            if ($header && (strtolower($header[0]) === 'title' || strtolower($header[0]) === 'тема')) {
                $isHeader = true;
            } else {
                rewind($handle);
            }

            $lineNumber = $isHeader ? 1 : 0;

            while (($row = fgetcsv($handle)) !== false) {
                $lineNumber++;

                // Пропускаем пустые строки
                if (empty($row) || (count($row) === 1 && empty($row[0]))) {
                    continue;
                }

                // Проверяем минимальное количество полей
                if (count($row) < 4) {
                    $errors[] = "Строка {$lineNumber}: Недостаточно полей (ожидается минимум 4)";
                    $errorCount++;
                    continue;
                }

                $data = [
                    'topic' => trim($row[0] ?? ''),
                    'message' => trim($row[1] ?? ''),
                    'author' => trim($row[2] ?? ''),
                    'created_at' => trim($row[3] ?? ''),
                ];

                // Валидация данных строки
                $validator = \Illuminate\Support\Facades\Validator::make($data, [
                    'topic' => 'required|string|max:255',
                    'message' => 'required|string',
                    'created_at' => 'required|date_format:Y-m-d H:i:s',
                ]);

                if ($validator->fails()) {
                    $errorMessages = implode(', ', $validator->errors()->all());
                    $errors[] = "Строка {$lineNumber}: {$errorMessages}";
                    $errorCount++;
                    continue;
                }

                // Вставляем запись через Eloquent
                try {
                    Blog::create([
                        'topic' => $data['topic'],
                        'message' => $data['message'],
                        'created_at' => $data['created_at'],
                    ]);
                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Строка {$lineNumber}: Ошибка при вставке в БД - " . $e->getMessage();
                    $errorCount++;
                }
            }

            fclose($handle);
        } else {
            return redirect()->back()
                ->withErrors(['csv_file' => 'Не удалось открыть файл CSV'])
                ->withInput();
        }

        return redirect('/blog')
            ->with('success', "Успешно загружено записей: {$successCount}")
            ->with('errors', $errors);
    }
}
