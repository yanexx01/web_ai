@extends('layouts.main')

@section('content')
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
    @else
        <p>Записей в блоге пока нет. Будьте первым!</p>
    @endif
</div>

<style>
.blog-list {
    margin-top: 30px;
}

.blog-posts {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.blog-post {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
}

.blog-image {
    width: 100%;
    max-height: 400px;
    overflow: hidden;
}

.blog-image img {
    width: 100%;
    height: auto;
    object-fit: cover;
}

.blog-content {
    padding: 20px;
}

.blog-topic {
    margin: 0 0 10px 0;
    color: #333;
}

.blog-date {
    color: #666;
    font-size: 0.9em;
    margin: 0 0 15px 0;
}

.blog-message {
    color: #444;
    line-height: 1.6;
    margin: 0;
}

.pagination {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.pagination-label {
    font-weight: bold;
    margin-right: 10px;
}

.pagination-link {
    padding: 8px 16px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background 0.3s;
}

.pagination-link:hover {
    background: #0056b3;
}

.pagination-link.active {
    background: #0056b3;
    font-weight: bold;
}

.pagination-ellipsis {
    padding: 8px;
    color: #666;
}

.blog-info {
    margin-top: 15px;
    text-align: center;
    color: #666;
}
</style>
@endsection
