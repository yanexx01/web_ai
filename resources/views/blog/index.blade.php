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

    @if(!empty($blogs))
        <div class="blog-list">
            @foreach($blogs as $post)
                <article class="blog-post">
                    @if(!empty($post->image))
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

        {{-- Пагинация под ваш контроллер --}}
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
        <a href="/blog/create" class="btn-submit">+ Добавить запись</a>
        <a href="/blog/upload" class="btn-reset">📥 Загрузить из CSV</a>
    </div>
</div>

{{-- Модальное окно --}}
<div id="blogImageModal" class="photo-modal" style="display: none;">
    <div class="modal-overlay" onclick="closeBlogModal()"></div>
    <div class="modal-content">
        <button class="modal-close" onclick="closeBlogModal()" aria-label="Закрыть">×</button>
        <div class="modal-image-container">
            <img id="blogModalImage" class="modal-image" src="" alt="">
        </div>
        <div class="modal-nav-panel">
            <div class="photo-info">
                <h4 id="blogModalTitle" class="photo-title"></h4>
            </div>
        </div>
    </div>
</div>

{{-- Стили страницы --}}
<style>
    .blog-page-wrapper { max-width: 900px; margin: 80px auto 40px; padding: 0 20px; }
    .blog-title { text-align: center; color: #222; margin-bottom: 10px; font-size: 2rem; }
    .blog-subtitle { text-align: center; color: #555; margin-bottom: 30px; font-size: 1.1rem; }
    
    /* Уведомления */
    .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; }
    .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    
    .blog-list { display: flex; flex-direction: column; gap: 30px; }
    .blog-post { background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .blog-post:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.12); }
    .blog-image-wrapper { width: 100%; height: 350px; overflow: hidden; position: relative; cursor: pointer; background-color: #f0f0f0; }
    .blog-image-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .blog-post:hover .blog-image-wrapper img { transform: scale(1.05); }
    .zoom-hint { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0, 0, 0, 0.6); color: white; padding: 8px 16px; border-radius: 20px; opacity: 0; transition: opacity 0.3s; pointer-events: none; font-size: 0.9rem; }
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

    /* Локальные стили модального окна (не конфликтуют с style.css) */
    #blogImageModal .modal-content { background: #1a1a1a; color: white; max-width: 90vw; max-height: 90vh; }
    #blogImageModal .modal-overlay { background-color: rgba(0, 0, 0, 0.9); backdrop-filter: blur(5px); }
</style>

<script>
    function openBlogModal(src, title) {
        const modal = document.getElementById('blogImageModal');
        const img = document.getElementById('blogModalImage');
        const titleEl = document.getElementById('blogModalTitle');
        
        img.src = src;
        img.alt = title;
        titleEl.textContent = title;
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeBlogModal() {
        const modal = document.getElementById('blogImageModal');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeBlogModal(); });
</script>
@endsection