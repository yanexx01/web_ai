@extends('layouts.main')

@section('content')
<div class="blog-page">
    <h1>Мой Блог</h1>
    
    {{-- Список записей блога с пагинацией --}}
    <div class="blog-list">
        @if(count($blogs) > 0)
            <div class="blog-posts">
                @foreach($blogs as $post)
                    <article class="blog-post">
                        @if($post->image)
                            <div class="blog-image">
                                <img src="/storage/{{ $post->image }}" alt="{{ htmlspecialchars($post->topic) }}">
                            </div>
                        @endif
                        <div class="blog-content">
                            <h3 class="blog-topic">{{ htmlspecialchars($post->topic) }}</h3>
                            <p class="blog-date">{{ date('d.m.Y H:i', strtotime($post->created_at)) }}</p>
                            <p class="blog-message">{{ nl2br(htmlspecialchars($post->message)) }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
            
            {{-- Пагинация в формате: Страницы: 1 … 5 6 7 … 24 --}}
            @if($totalPages > 1)
                <div class="pagination">
                    <span class="pagination-label">Страницы:</span>
                    
                    @php
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);
                        
                        // Показываем первую страницу всегда, если не входим в диапазон
                        $showFirst = $startPage > 1;
                        
                        // Показываем последнюю страницу всегда, если не входим в диапазон
                        $showLast = $endPage < $totalPages;
                    @endphp
                    
                    @if($showFirst)
                        <a href="/blog?page=1" class="pagination-link {{ 1 === $currentPage ? 'active' : '' }}">1</a>
                        @if($startPage > 2)
                            <span class="pagination-ellipsis">…</span>
                        @endif
                    @endif
                    
                    @for($i = $startPage; $i <= $endPage; $i++)
                        <a href="/blog?page={{ $i }}" class="pagination-link {{ $i === $currentPage ? 'active' : '' }}">{{ $i }}</a>
                    @endfor
                    
                    @if($showLast)
                        @if($endPage < $totalPages - 1)
                            <span class="pagination-ellipsis">…</span>
                        @endif
                        <a href="/blog?page={{ $totalPages }}" class="pagination-link {{ $totalPages === $currentPage ? 'active' : '' }}">{{ $totalPages }}</a>
                    @endif
                </div>
            @endif
            
            <p class="blog-info">Показано {{ count($blogs) }} из {{ $totalItems }} записей</p>
            
            {{-- Кнопки добавления новой записи и загрузки CSV --}}
            <div class="add-post-link">
                <a href="/blog/create" class="btn-add-post">+ Добавить запись в блог</a>
                <a href="/blog/upload" class="btn-upload-csv">📥 Загрузить из CSV</a>
            </div>
        @else
            <p>Записей в блоге пока нет. Будьте первым!</p>
            <div class="add-post-link">
                <a href="/blog/create" class="btn-add-post">+ Добавить первую запись в блог</a>
                <a href="/blog/upload" class="btn-upload-csv">📥 Загрузить из CSV</a>
            </div>
        @endif
    </div>
</div>
@endsection
