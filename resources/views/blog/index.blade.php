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
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Пагинация --}}
        @if($totalPages > 1)
            <div class="pagination-wrapper">
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
                <p class="pagination-info">Страница {{ $currentPage }} из {{ $totalPages }} (всего {{ $totalItems }} записей)</p>
            </div>
        @else
            <p class="pagination-info">Показано {{ count((array) $blogs) }} из {{ $totalItems }} записей</p>
        @endif
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
</script>
@endsection