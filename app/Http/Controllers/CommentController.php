<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Сохранение комментария к записи блога
     * Требует авторизации пользователя
     */
    public function store(Request $request)
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return response('Unauthorized', 401);
        }

        // Валидация данных
        $validator = $request->validate([
            'blog_id' => 'required|exists:blogs,id',
            'content' => 'required|string|min:1|max:1000',
        ], [
            'content.required' => 'Введите текст комментария',
            'content.min' => 'Комментарий должен содержать хотя бы 1 символ',
            'content.max' => 'Комментарий не должен превышать 1000 символов',
            'blog_id.exists' => 'Запись блога не найдена',
        ]);

        // Создаем комментарий
        $comment = Comment::create([
            'blog_id' => $request->input('blog_id'),
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Получаем данные для отображения
        $comment->load('user');
        $commentDate = \Carbon\Carbon::parse($comment->created_at)->format('d.m.Y H:i');

        // Возвращаем HTML-код нового комментария (требование задания - формат HTML)
        $html = view('blog._comment_single', [
            'comment' => $comment,
            'commentDate' => $commentDate
        ])->render();

        return response($html, 200)
            ->header('Content-Type', 'text/html');
    }

    /**
     * Получение комментариев для записи блога
     */
    public function index($blogId)
    {
        $comments = Comment::where('blog_id', $blogId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        $html = '';
        foreach ($comments as $comment) {
            $commentDate = \Carbon\Carbon::parse($comment->created_at)->format('d.m.Y H:i');
            $html .= view('blog._comment_single', [
                'comment' => $comment,
                'commentDate' => $commentDate
            ])->render();
        }

        return response($html, 200)
            ->header('Content-Type', 'text/html');
    }
/**
     * Обновление комментария (только автор может редактировать свой комментарий)
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Требуется авторизация'], 401);
        }

        $validator = $request->validate([
            'content' => 'required|string|min:1|max:1000',
        ], [
            'content.required' => 'Введите текст комментария',
            'content.min' => 'Комментарий должен содержать хотя бы 1 символ',
            'content.max' => 'Комментарий не должен превышать 1000 символов',
        ]);

        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Комментарий не найден'], 404);
        }

        // Проверка: только автор может редактировать свой комментарий
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'У вас нет прав для редактирования этого комментария'], 403);
        }

        $comment->content = $request->input('content');
        $comment->save();

        $comment->load('user');
        $commentDate = \Carbon\Carbon::parse($comment->created_at)->format('d.m.Y H:i');

        $html = view('blog._comment_single', [
            'comment' => $comment,
            'commentDate' => $commentDate
        ])->render();

        return response($html, 200)
            ->header('Content-Type', 'text/html');
    }

    /**
     * Удаление комментария (только автор может удалить свой комментарий)
     */
    public function destroy($id)
    {
        if (!Auth::check()) {
            return response('Unauthorized', 401);
        }

        $comment = Comment::find($id);

        if (!$comment) {
            return response('Comment not found', 404);
        }

        // Проверка: только автор может удалить свой комментарий
        if ($comment->user_id !== Auth::id()) {
            return response('Forbidden', 403);
        }

        $blogId = $comment->blog_id;
        $comment->delete();

        return response('Deleted', 200);
    }
}