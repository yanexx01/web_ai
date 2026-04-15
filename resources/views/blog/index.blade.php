@extends('layouts.main')

@section('content')
<h1>Редактор Блога</h1>

{{-- Форма добавления записи блога --}}
<div class="blog-form">
    <h2>Добавить запись</h2>
    <form action="/blog" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="topic">Тема сообщения *</label>
            <input type="text" name="topic" id="topic" required value="{{ old('topic') }}">
        </div>
        
        <div class="form-group">
            <label for="image">Изображение</label>
            <input type="file" name="image" id="image" accept="image/*">
        </div>
        
        <div class="form-group">
            <label for="message">Текст сообщения *</label>
            <textarea name="message" id="message" rows="5" required>{{ old('message') }}</textarea>
        </div>
        
        <button type="submit">Добавить запись</button>
    </form>
    
    @if($errors->any())
        <div class="error-messages">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

{{-- Список записей блога с пагинацией --}}
<div class="blog-list">
    <h2>Записи блога</h2>
    
    @if(count($blogs) > 0)
        <div class="blog-posts">
            @foreach($blogs as $post)
                <article class="blog-post">
                    @if($post->image)
                        <div class="blog-image">
                            <img src="/storage/{{ $post->image }}" alt="{{ $post->topic }}">
                        </div>
                    @endif
                    <div class="blog-content">
                        <h3 class="blog-topic">{{ $post->topic }}</h3>
                        <p class="blog-date">{{ $post->created_at }}</p>
                        <p class="blog-message">{{ $post->message }}</p>
                    </div>
                </article>
            @endforeach
        </div>
        
        {{-- Пагинация --}}
        @if($totalPages > 1)
            <div class="pagination">
                @if($currentPage > 1)
                    <a href="/blog?page={{ $currentPage - 1 }}" class="pagination-link">&laquo; Назад</a>
                @endif
                
                @for($i = 1; $i <= $totalPages; $i++)
                    <a href="/blog?page={{ $i }}" class="pagination-link {{ $i === $currentPage ? 'active' : '' }}">{{ $i }}</a>
                @endfor
                
                @if($currentPage < $totalPages)
                    <a href="/blog?page={{ $currentPage + 1 }}" class="pagination-link">Вперед &raquo;</a>
                @endif
            </div>
        @endif
        
        <p class="blog-info">Показано {{ count($blogs) }} из {{ $totalItems }} записей</p>
    @else
        <p>Записей в блоге пока нет. Будьте первым!</p>
    @endif
</div>

<style>
.blog-form {
    margin-bottom: 30px;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 8px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input[type="text"],
.form-group textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

.form-group input[type="file"] {
    padding: 8px 0;
}

button[type="submit"] {
    padding: 10px 20px;
    background: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button[type="submit"]:hover {
    background: #218838;
}

.error-messages {
    margin-top: 15px;
    padding: 10px;
    background: #ffe6e6;
    border: 1px solid #ffcccc;
    border-radius: 4px;
    color: #cc0000;
}

.error-messages ul {
    margin: 0;
    padding-left: 20px;
}

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
    gap: 10px;
    flex-wrap: wrap;
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

.blog-info {
    margin-top: 15px;
    text-align: center;
    color: #666;
}
</style>
@endsection
