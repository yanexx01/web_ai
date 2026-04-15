<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    /**
     * Количество записей на странице
     */
    private int $perPage = 5;

    /**
     * Отображение страницы редактора блога
     */
    public function index(Request $request)
    {
        // Получаем номер страницы
        $page = $request->input('page', 1);
        
        // Получаем все записи из БД, отсортированные по убыванию даты
        $allBlogs = Blog::findAll();
        
        // Сортируем по убыванию даты
        usort($allBlogs, function($a, $b) {
            $dateA = strtotime($a->created_at ?? '0000-00-00 00:00:00');
            $dateB = strtotime($b->created_at ?? '0000-00-00 00:00:00');
            return $dateB - $dateA;
        });

        // Пагинация
        $totalItems = count($allBlogs);
        $totalPages = ceil($totalItems / $this->perPage);
        $page = max(1, min($page, $totalPages > 0 ? $totalPages : 1));
        $offset = ($page - 1) * $this->perPage;
        $blogs = array_slice($allBlogs, $offset, $this->perPage);

        return view('blog.index', [
            'pageTitle' => 'Редактор Блога',
            'pageName' => 'blog',
            'blogs' => $blogs,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems
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
