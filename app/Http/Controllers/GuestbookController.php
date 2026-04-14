<?php

namespace App\Http\Controllers;

use App\models\Guestbook;
use App\models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GuestbookController extends Controller
{
    /**
     * Путь к файлу для хранения сообщений (messages.inc)
     */
    private string $messagesFile;

    public function __construct()
    {
        $this->messagesFile = storage_path('app/messages.inc');
    }

    /**
     * Отображение страницы гостевой книги
     */
    public function index()
    {
        // Получаем все сообщения из БД, отсортированные по убыванию даты
        $messages = Guestbook::findAll();
        
        // Сортируем по убыванию даты (если created_at есть в атрибутах)
        usort($messages, function($a, $b) {
            $dateA = strtotime($a->created_at ?? '0000-00-00 00:00:00');
            $dateB = strtotime($b->created_at ?? '0000-00-00 00:00:00');
            return $dateB - $dateA;
        });

        return view('guestbook.index', [
            'pageTitle' => 'Гостевая книга',
            'pageName' => 'guestbook',
            'messages' => $messages
        ]);
    }

    /**
     * Обработка формы добавления отзыва
     */
    public function store(Request $request)
    {
        // Валидация данных
        $validator = Validator::make($request->all(), [
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect('/guestbook')
                ->withErrors($validator)
                ->withInput();
        }

        // Создаем новую запись в БД
        $guestbook = new Guestbook();
        $guestbook->lastname = $request->input('lastname');
        $guestbook->firstname = $request->input('firstname');
        $guestbook->middlename = $request->input('middlename');
        $guestbook->email = $request->input('email');
        $guestbook->message = $request->input('message');
        $guestbook->created_at = date('Y-m-d H:i:s');
        $guestbook->save();

        // Сохраняем данные в текстовый файл messages.inc
        $this->saveToFile($guestbook);

        return redirect('/guestbook');
    }

    /**
     * Страница загрузки файла messages.inc
     */
    public function uploadForm()
    {
        return view('guestbook.upload', [
            'pageTitle' => 'Загрузка сообщений гостевой книги',
            'pageName' => 'guestbook-upload'
        ]);
    }

    /**
     * Обработка загрузки файла
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'messages_file' => 'required|file|mimes:inc,txt',
        ]);

        if ($validator->fails()) {
            return redirect('/guestbook/upload')
                ->withErrors($validator);
        }

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

                    $guestbook = new Guestbook();
                    $guestbook->lastname = $lastname;
                    $guestbook->firstname = $firstname;
                    $guestbook->middlename = $middlename;
                    $guestbook->email = $email;
                    $guestbook->message = $message;
                    $guestbook->created_at = $createdAt;
                    $guestbook->save();
                }
            }
        }

        return redirect('/guestbook')->with('success', 'Файл успешно загружен и данные импортированы!');
    }

    /**
     * Сохранение записи в текстовый файл
     * Формат: Дата;ФИО;E-mail;Текст отзыва
     */
    private function saveToFile(Guestbook $guestbook): void
    {
        $fio = trim($guestbook->lastname . ' ' . $guestbook->firstname . ' ' . $guestbook->middlename);
        $date = date('d.m.y');
        $line = "{$date};{$fio};{$guestbook->email};{$guestbook->message}\n";

        file_put_contents($this->messagesFile, $line, FILE_APPEND | LOCK_EX);
    }
}
