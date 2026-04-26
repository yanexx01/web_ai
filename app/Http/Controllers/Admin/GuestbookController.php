<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guestbook;
use Illuminate\Http\Request;

class GuestbookController extends Controller
{
    /**
     * Отображение страницы гостевой книги в админке
     */
    public function index(Request $request)
    {
        // Получаем номер страницы
        $page = $request->input('page', 1);
        
        // Получаем общее количество записей
        $totalItems = Guestbook::count();
        
        // Рассчитываем общее количество страниц
        $totalPages = $totalItems > 0 ? ceil($totalItems / 10) : 1;
        
        // Ограничиваем номер страницы допустимым диапазоном
        $page = max(1, min($page, $totalPages));
        
        // Получаем сообщения из БД с пагинацией и сортировкой по убыванию даты
        $messages = Guestbook::orderBy('created_at', 'desc')
            ->offset(($page - 1) * 10)
            ->limit(10)
            ->get();

        return view('admin.guestbook.index', [
            'pageTitle' => 'Управление гостевой книгой',
            'pageName' => 'admin-guestbook',
            'messages' => $messages,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems
        ]);
    }

    /**
     * Обработка формы добавления отзыва
     */
    public function store(Request $request)
    {
        // Валидация данных
        $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // Создаем новую запись в БД
        Guestbook::create([
            'lastname' => $request->input('lastname'),
            'firstname' => $request->input('firstname'),
            'middlename' => $request->input('middlename'),
            'email' => $request->input('email'),
            'message' => $request->input('message'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect('/admin/guestbook');
    }

    /**
     * Страница загрузки файла messages.inc
     */
    public function uploadForm()
    {
        return view('admin.guestbook.upload', [
            'pageTitle' => 'Загрузка сообщений гостевой книги',
            'pageName' => 'admin-guestbook-upload'
        ]);
    }

    /**
     * Обработка загрузки файла
     */
    public function upload(Request $request)
    {
        $request->validate([
            'messages_file' => 'required|file|mimes:inc,txt',
        ]);

        $file = $request->file('messages_file');
        $filePath = $file->storeAs('uploads', 'messages.inc', 'public');
        
        // Читаем файл и импортируем данные в БД
        $fullPath = storage_path('app/public/' . $filePath);
        if (file_exists($fullPath)) {
            $lines = file($fullPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $parts = explode(';', $line, 4);
                if (count($parts) >= 4) {
                    $dateStr = trim($parts[0]);
                    $fio = trim($parts[1]);
                    $email = trim($parts[2]);
                    $message = trim($parts[3]);

                    // Разбираем ФИО
                    $fioParts = preg_split('/\s+/', $fio, 3);
                    $lastname = $fioParts[0] ?? '';
                    $firstname = $fioParts[1] ?? '';
                    $middlename = $fioParts[2] ?? '';

                    // Конвертируем дату из формата d.m.y в Y-m-d H:i:s
                    $dateObj = \DateTime::createFromFormat('d.m.y', $dateStr);
                    $createdAt = $dateObj ? $dateObj->format('Y-m-d H:i:s') : date('Y-m-d H:i:s');

                    Guestbook::create([
                        'lastname' => $lastname,
                        'firstname' => $firstname,
                        'middlename' => $middlename,
                        'email' => $email,
                        'message' => $message,
                        'created_at' => $createdAt,
                    ]);
                }
            }
        }

        return redirect('/admin/guestbook')->with('success', 'Файл успешно загружен и данные импортированы!');
    }
}
