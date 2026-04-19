<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Http\Validators\FormValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

        $blogs = Blog::findPaginated(self::PER_PAGE, ($page - 1) * self::PER_PAGE);
        
        return view('blog.index', [
            'pageTitle' => 'Мой Блог',
            'pageName' => 'blog',
            'blogs' => $blogs,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems
        ]);
        // $blogs = Post::latest()->paginate(10); 
        // return view('blog.index', compact('blogs'));
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
        // Валидация данных с использованием FormValidation (через Validator)
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string|max:255',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect('/blog')
                ->withErrors($validator)
                ->withInput();
        }

        // Создаем новую запись в БД
        $blog = new Blog();
        $blog->topic = $request->input('topic');
        $blog->message = $request->input('message');
        
        // Обработка загрузки изображения
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('blog_images', $imageName, 'public');
            $blog->image = $imagePath;
        } else {
            $blog->image = null;
        }
        
        $blog->created_at = date('Y-m-d H:i:s');
        $blog->save();

        return redirect('/blog');
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
        // Валидация файла CSV с использованием FormValidation
        $validator = FormValidation::validateCsvFile($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

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
                // Если нет заголовка, возвращаем указатель на начало
                rewind($handle);
            }

            $db = DB::connection()->getPdo();
            
            // Подготавливаем SQL запрос с использованием подготовленных выражений
            $sql = "INSERT INTO blogs (topic, message, created_at) VALUES (?, ?, ?)";
            $stmt = $db->prepare($sql);

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
                    'title' => trim($row[0] ?? ''),
                    'message' => trim($row[1] ?? ''),
                    'author' => trim($row[2] ?? ''),
                    'created_at' => trim($row[3] ?? ''),
                ];

                // Валидация данных строки с использованием FormValidation
                $rowValidator = FormValidation::validateCsvImport($data);

                if ($rowValidator->fails()) {
                    $errorMessages = implode(', ', $rowValidator->errors()->all());
                    $errors[] = "Строка {$lineNumber}: {$errorMessages}";
                    $errorCount++;
                    continue;
                }

                // Выполняем подготовленный запрос для вставки записи
                try {
                    $stmt->execute([
                        $data['title'],
                        $data['message'],
                        $data['created_at']
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
