<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
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
}
