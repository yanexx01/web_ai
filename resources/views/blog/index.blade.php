@extends('layouts.main')

@section('content')
<div class="blog-page-wrapper">
    <h1 class="blog-title">Мой Блог</h1>
    <p class="blog-subtitle">Последние новости и заметки</p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('errors') && is_array(session('errors')))
        <div class="alert alert-error">
            <h5 style="margin-top: 0;">Ошибки при загрузке:</h5>
            <ul style="margin-bottom: 0; padding-left: 20px;">
                @foreach(session('errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(!empty($blogs))
        <div class="blog-list">
            @foreach($blogs as $post)
                <article class="blog-post">
                    @if(!empty($post->image))
                        <!-- Добавлен класс для курсора и обработчик клика -->
                        <div class="blog-image-wrapper" onclick="openBlogModal('{{ asset('storage/' . $post->image) }}', '{{ addslashes($post->topic ?? 'Без заголовка') }}')">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ htmlspecialchars($post->topic ?? '') }}" loading="lazy">
                            <span class="zoom-hint">🔍 Нажмите для увеличения</span>
                        </div>
                    @endif
                    
                    <div class="blog-content">
                        <h2 class="blog-topic">{{ htmlspecialchars($post->topic ?? 'Без заголовка') }}</h2>
                        <time class="blog-date" datetime="{{ $post->created_at ?? now()->toISOString() }}">
                            {{ \Carbon\Carbon::parse($post->created_at ?? now())->format('d.m.Y H:i') }}
                        </time>
                        <div class="blog-message">
                            {!! nl2br(e($post->message ?? '')) !!}
                        </div>
                        
                        <!-- Кнопка добавления комментария (только для авторизированных) -->
                        @auth
                            <button type="button" class="btn-add-comment" onclick="openCommentModal({{ $post->id }})">
                                💬 Добавить комментарий
                            </button>


                        @else
                            <p class="login-prompt">
                                <a href="{{ route('login') }}">Войдите</a>, чтобы оставить комментарий.
                            </p>
                        @endauth
                        
                        <!-- Контейнер для комментариев (виден всем) -->
                        <div class="comments-section" id="comments-{{ $post->id }}">
                            <h3 class="comments-title">Комментарии</h3>
                            <div class="comments-list" id="comments-list-{{ $post->id }}">
                                <!-- Комментарии будут загружены здесь -->
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Пагинация --}}
        <div class="pagination-wrapper">
            @if($totalPages > 1)
                <nav>
                    @php
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);
                    @endphp

                    @if($currentPage > 1)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}" class="page-link prev">&laquo; Назад</a>
                    @endif

                    @if($startPage > 1)
                        <a href="{{ request()->fullUrlWithQuery(['page' => 1]) }}" class="page-link">1</a>
                        @if($startPage > 2) <span class="page-ellipsis">…</span> @endif
                    @endif

                    @for($i = $startPage; $i <= $endPage; $i++)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" class="page-link {{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
                    @endfor

                    @if($endPage < $totalPages)
                        @if($endPage < $totalPages - 1) <span class="page-ellipsis">…</span> @endif
                        <a href="{{ request()->fullUrlWithQuery(['page' => $totalPages]) }}" class="page-link">{{ $totalPages }}</a>
                    @endif

                    @if($currentPage < $totalPages)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}" class="page-link next">Далее &raquo;</a>
                    @endif
                </nav>
                {{-- <p class="pagination-info">Страница {{ $currentPage }} из {{ $totalPages }} (всего {{ $totalItems }} записей)</p>
            </div>
        @else
            <p class="pagination-info">Показано {{ count((array) $blogs) }} из {{ $totalItems }} записей</p>
        @endif --}}
            @endif
            <p class="pagination-info">Страница {{ $currentPage }} из {{ $totalPages }} (всего {{ $totalItems }} записей)</p>
        </div>
    @else
        <div class="empty-state">
            <p>Записей в блоге пока нет. Добавьте первую запись!</p>
        </div>
    @endif

    <div class="button-group" style="margin-top: 40px;">
        <button type="button" class="btn-submit" onclick="window.location.href='/blog/create'">+ Добавить запись</button>
        <button type="button" class="btn-reset" onclick="window.location.href='/blog/upload'">📥 Загрузить из CSV</button>
    </div>
</div>

{{-- Модальное окно для просмотра изображений --}}
<div id="blogImageModal" class="modal-overlay" onclick="closeBlogModal()">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 id="blogModalTitle"></h3>
            <button class="modal-close-btn" onclick="closeBlogModal()">&times;</button>
        </div>
        <div class="modal-body">
            <img id="blogModalImage" src="" alt="">
        </div>
    </div>
</div>

{{-- Модальное окно для добавления комментария --}}
<div id="commentModal" class="modal-overlay comment-modal-overlay" onclick="closeCommentModal(event)">
    <div class="modal-container comment-modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>Добавить комментарий</h3>
            <button class="modal-close-btn" type="button" onclick="closeCommentModal()">&times;</button>
        </div>
        <div class="modal-body comment-modal-body">
            <form id="commentForm" onsubmit="submitComment(event)">
                <input type="hidden" id="commentBlogId" name="blog_id" value="">
                <textarea
                    id="commentContent"
                    name="content"
                    placeholder="Введите ваш комментарий..."
                    rows="4"
                    required
                    minlength="1"
                    maxlength="1000"
                ></textarea>
                <div class="comment-form-actions">
                    <span id="commentError" class="comment-error"></span>
                    <button type="submit" class="btn-submit btn-comment-submit">Отправить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Основные стили страницы */
    .blog-page-wrapper { max-width: 900px; margin: 80px auto 40px; padding: 0 20px; }
    .blog-title { text-align: center; color: #222; margin-bottom: 10px; font-size: 2rem; }
    .blog-subtitle { text-align: center; color: #555; margin-bottom: 30px; font-size: 1.1rem; }
    
    /* Уведомления */
    .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; }
    .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    
    .blog-list { display: flex; flex-direction: column; gap: 30px; }
    .blog-post { background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .blog-post:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.12); }
    
    .blog-image-wrapper { width: 100%; height: 350px; overflow: hidden; position: relative; cursor: pointer; background-color: #f0f0f0; }
    .blog-image-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .blog-post:hover .blog-image-wrapper img { transform: scale(1.05); }
    
    .zoom-hint { 
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); 
        background: rgba(0, 0, 0, 0.6); color: white; padding: 8px 16px; 
        border-radius: 20px; opacity: 0; transition: opacity 0.3s; 
        pointer-events: none; font-size: 0.9rem; 
    }
    .blog-image-wrapper:hover .zoom-hint { opacity: 1; }
    
    .blog-content { padding: 25px; }
    .blog-topic { margin: 0 0 10px 0; color: #222; font-size: 1.5rem; }
    .blog-date { color: #777; font-size: 0.9rem; margin: 0 0 15px 0; font-style: italic; display: block; }
    .blog-message { color: #444; line-height: 1.6; font-size: 1.05rem; }
    .empty-state { text-align: center; padding: 40px; background: #f9f9f9; border-radius: 12px; color: #555; }
    
    /* Пагинация */
    .pagination-wrapper { margin-top: 30px; }
    .pagination-wrapper nav { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; align-items: center; }
    .page-link { padding: 8px 14px; background: #f0f0f0; color: #333; text-decoration: none; border-radius: 6px; transition: all 0.3s; font-weight: 500; }
    .page-link:hover { background: #e0e0e0; color: #000; }
    .page-link.active { background: #333; color: white; }
    .page-ellipsis { color: #777; padding: 0 5px; }
    .pagination-info { text-align: center; color: #666; margin-top: 15px; font-size: 0.9rem; }

    @media (max-width: 600px) {
        .blog-image-wrapper { height: 200px; }
        .blog-topic { font-size: 1.3rem; }
    }

    /* --- ИСПРАВЛЕННЫЕ СТИЛИ МОДАЛЬНОГО ОКНА --- */
    .modal-overlay {
        display: none; /* Скрыто по умолчанию */
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        backdrop-filter: blur(5px);
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    /* Класс для плавного появления */
    .modal-overlay.show {
        opacity: 1;
    }

    .modal-container {
        background: #222;
        border-radius: 8px;
        max-width: 95vw;
        max-height: 95vh; /* Ограничиваем высоту контейнера */
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        position: relative;
    }

    .modal-header {
        padding: 15px 20px;
        border-bottom: 1px solid #444;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0; /* Заголовок не сжимается */
    }

    .modal-header h3 {
        margin: 0;
        color: #fff;
        font-size: 1.1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 80%;
    }

    .modal-close-btn {
        background: none;
        border: none;
        color: #aaa;
        font-size: 2rem;
        line-height: 1;
        cursor: pointer;
        padding: 0;
        margin-left: 10px;
        transition: color 0.2s;
    }

    .modal-close-btn:hover {
        color: #fff;
    }

    .modal-body {
        padding: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden; /* Важно: скрываем все, что вылезает */
        flex-grow: 1;
        min-height: 0; /* Для корректной работы flexbox сжатия */
    }

    .modal-body img {
        max-width: 100%;
        max-height: calc(95vh - 60px); /* Высота экрана минус примерная высота заголовка */
        width: auto;
        height: auto;
        object-fit: contain; /* Ключевое свойство: картинка вписывается полностью */
        display: block;
    }

    /* Стили для модального окна комментариев */
    .comment-modal-overlay {
        z-index: 10000;
    }

    .comment-modal-container {
        max-width: 500px;
        max-height: 80vh;
    }

    .comment-modal-body {
        padding: 20px;
        display: block;
    }

    #commentForm textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #444;
        border-radius: 6px;
        background: #333;
        color: #fff;
        font-size: 1rem;
        resize: vertical;
        box-sizing: border-box;
    }

    #commentForm textarea:focus {
        outline: none;
        border-color: #666;
    }

    .comment-form-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 15px;
        align-items: flex-end;
    }

    .comment-error {
        color: #ff6b6b;
        font-size: 0.9rem;
        min-height: 20px;
    }

    .btn-comment-submit {
        min-width: 120px;
    }

    /* Стили для кнопки добавления комментария */
    .btn-add-comment {
        display: inline-block;
        margin-top: 15px;
        padding: 10px 20px;
        background: #4a90d9;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.95rem;
        transition: background 0.3s;
    }

    .btn-add-comment:hover {
        background: #357abd;
    }

    /* Секция комментариев */
    .comments-section {
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .comments-title {
        font-size: 1.2rem;
        color: #333;
        margin-bottom: 15px;
    }

    .comments-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .comment-item {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 15px;
        border-left: 3px solid #4a90d9;
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 0.9rem;
    }

    .comment-author {
        font-weight: 600;
        color: #333;
    }

    .comment-date {
        color: #888;
        font-style: italic;
    }

    .comment-content {
        color: #444;
        line-height: 1.5;
        word-wrap: break-word;
    }

    .login-prompt {
        margin-top: 15px;
        color: #666;
        font-style: italic;
    }

    .login-prompt a {
        color: #4a90d9;
        text-decoration: none;
    }

    .login-prompt a:hover {
        text-decoration: underline;
    }

    /* Кнопки действий для комментариев */
    .comment-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #e0e0e0;
    }

    .btn-comment-edit,
    .btn-comment-delete {
        padding: 5px 12px;
        font-size: 0.85rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-comment-edit {
        background: #ffc107;
        color: #333;
    }

    .btn-comment-edit:hover {
        background: #ffca2c;
    }

    .btn-comment-delete {
        background: #dc3545;
        color: white;
    }

    .btn-comment-delete:hover {
        background: #bb2d3b;
    }


    @media (max-width: 600px) {
        .modal-container {
            max-width: 100vw;
            max-height: 100vh;
            border-radius: 0;
        }
        .modal-body img {
            max-height: calc(100vh - 50px);
        }
    }
</style>

<script>
    function openBlogModal(src, title) {
        const modal = document.getElementById('blogImageModal');
        const img = document.getElementById('blogModalImage');
        const titleEl = document.getElementById('blogModalTitle');
        
        // Сначала устанавливаем данные
        img.src = src;
        img.alt = title;
        titleEl.textContent = title;
        
        // Показываем модалку (flex для центрирования)
        modal.style.display = 'flex';
        
        // Небольшая задержка для срабатывания CSS transition (opacity)
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
        
        document.body.style.overflow = 'hidden'; // Блокируем прокрутку фона
    }

    function closeBlogModal() {
        const modal = document.getElementById('blogImageModal');

        
        // Убираем класс прозрачности
        modal.classList.remove('show');
        
        // Ждем окончания анимации перед скрытием
        setTimeout(() => {
            modal.style.display = 'none';
            // Очищаем src, чтобы не мелькало старое изображение при следующем открытии
            document.getElementById('blogModalImage').src = '';
        }, 300); // Время должно совпадать с transition в CSS
        
        document.body.style.overflow = ''; // Возвращаем прокрутку
    }

    // Закрытие по Escape
    document.addEventListener('keydown', (e) => { 
        if (e.key === 'Escape') closeBlogModal(); 
    });

    
    // Функции для работы с комментариями (Fetch API + HTML)

    // Открытие модального окна для добавления комментария
    function openCommentModal(blogId) {
        document.getElementById('commentBlogId').value = blogId;
        document.getElementById('commentContent').value = '';
        document.getElementById('commentError').textContent = '';

        const modal = document.getElementById('commentModal');
        modal.style.display = 'flex';

        setTimeout(() => {
            modal.classList.add('show');
        }, 10);

        document.body.style.overflow = 'hidden';
        document.getElementById('commentContent').focus();
    }

    // Закрытие модального окна комментариев
    function closeCommentModal(event) {
        if (event && event.target !== event.currentTarget) return;

        const modal = document.getElementById('commentModal');
        modal.classList.remove('show');
        const titleEl = modal.querySelector('h3');

        // Сбрасываем заголовок и состояние редактирования при закрытии
        titleEl.textContent = 'Добавить комментарий';
        editingCommentId = null;


        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);

        document.body.style.overflow = '';
    }

    // Отправка комментария через Fetch API
    function submitComment(event) {
        event.preventDefault();

        const blogId = document.getElementById('commentBlogId').value;
        const content = document.getElementById('commentContent').value.trim();
        const errorEl = document.getElementById('commentError');

        if (!content) {
            errorEl.textContent = 'Введите текст комментария';
            return;
        }

        // Получаем CSRF-токен из meta-тега
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        // Отправляем данные через Fetch API (требование задания)
        fetch('/comments', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'text/html'
            },
            body: JSON.stringify({
                blog_id: blogId,
                content: content
            })
        })
        .then(response => {
            if (response.status === 401) {
                throw new Error('Требуется авторизация');
            }
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text || 'Ошибка отправки'); });
            }
            return response.text();
        })
        .then(html => {
            // Вместо добавления HTML вручную, полностью перезагружаем список комментариев
            // Это гарантирует, что надпись "Комментариев пока нет" исчезнет
            const blogId = document.getElementById('commentBlogId').value;
            loadComments(blogId);

            // Закрываем модальное окно
            closeCommentModal();

            // Очищаем поле ввода
            document.getElementById('commentContent').value = '';
            errorEl.textContent = '';
        })
        .catch(error => {
            console.error('Ошибка:', error);
            errorEl.textContent = error.message || 'Ошибка при отправке комментария';
        });
    }

    // Загрузка существующих комментариев при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
        const commentSections = document.querySelectorAll('.comments-section');
        commentSections.forEach(section => {
            const blogId = section.id.replace('comments-', '');
            loadComments(blogId);
        });

    });

    // Загрузка комментариев для записи блога
    function loadComments(blogId) {
        fetch('/blog/' + blogId + '/comments', {
            method: 'GET',
            headers: {
                'Accept': 'text/html'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Ошибка загрузки комментариев');
            return response.text();
        })
        .then(html => {
            const commentsList = document.getElementById('comments-list-' + blogId);
                // Проверяем, есть ли реальный контент (после удаления пробелов)
                const trimmedHtml = html.trim();
                commentsList.innerHTML = trimmedHtml ? trimmedHtml : '<p style="color:#888;font-style:italic;">Комментариев пока нет</p>';
        })
        .catch(error => {
            console.error('Ошибка загрузки комментариев:', error);
        });
    }
    
    // Модальное окно для редактирования комментария
    let editingCommentId = null;

    function editComment(commentId, content) {
        editingCommentId = commentId;

        const modal = document.getElementById('commentModal');
        const titleEl = modal.querySelector('h3');
        const blogIdInput = document.getElementById('commentBlogId');
        const contentInput = document.getElementById('commentContent');
        const errorEl = document.getElementById('commentError');

        // Меняем заголовок модального окна
        titleEl.textContent = 'Редактировать комментарий';

        // Устанавливаем текущий текст комментария
        contentInput.value = content;
        errorEl.textContent = '';

        // Показываем модалку
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);

        document.body.style.overflow = 'hidden';
    }

    function deleteComment(commentId) {
        if (!confirm('Вы уверены, что хотите удалить этот комментарий?')) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        fetch('/comments/' + commentId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'text/html'
            }
        })
        .then(response => {
            if (response.status === 401) {
                throw new Error('Требуется авторизация');
            }
            if (response.status === 403) {
                throw new Error('У вас нет прав для удаления этого комментария');
            }
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text || 'Ошибка удаления'); });
            }
            return response.text();
        })
        .then(() => {
            // Находим blog_id из родительского элемента и перезагружаем комментарии
            const commentElement = document.getElementById('comment-' + commentId);
            if (commentElement) {
                const commentsSection = commentElement.closest('.comments-section');
                if (commentsSection) {
                    const blogId = commentsSection.id.replace('comments-', '');
                    loadComments(blogId);
                }
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert(error.message || 'Ошибка при удалении комментария');
        });
    }

    // Переопределяем функцию отправки для поддержки редактирования
    const originalSubmitComment = submitComment;
    submitComment = function(event) {
        event.preventDefault();

        const blogId = document.getElementById('commentBlogId').value;
        const content = document.getElementById('commentContent').value.trim();
        const errorEl = document.getElementById('commentError');

        if (!content) {
            errorEl.textContent = 'Введите текст комментария';
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        // Если мы редактируем комментарий
        if (editingCommentId !== null) {
            fetch('/comments/' + editingCommentId, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'text/html'
                },
                body: JSON.stringify({
                    content: content
                })
            })
            .then(response => {
                if (response.status === 401) {
                    throw new Error('Требуется авторизация');
                }
                if (response.status === 403) {
                    throw new Error('У вас нет прав для редактирования этого комментария');
                }
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text || 'Ошибка обновления'); });
                }
                return response.text();
            })
            .then(() => {
                // Перезагружаем список комментариев
                loadComments(blogId);

                // Закрываем модальное окно
                closeCommentModal();

                // Очищаем состояние редактирования
                editingCommentId = null;

                // Сбрасываем заголовок модального окна
                const modal = document.getElementById('commentModal');
                const titleEl = modal.querySelector('h3');
                titleEl.textContent = 'Добавить комментарий';

                // Очищаем поле ввода
                document.getElementById('commentContent').value = '';
                errorEl.textContent = '';
            })
            .catch(error => {
                console.error('Ошибка:', error);
                errorEl.textContent = error.message || 'Ошибка при обновлении комментария';
            });
        } else {
            // Обычная отправка нового комментария
            originalSubmitComment(event);
        }
    };
</script>
@endsection