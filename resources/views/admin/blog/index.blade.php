@extends('admin.layouts.app')

@section('content')
<div class="admin-card">
    <h2 style="margin-top: 0;">📝 Управление блогом</h2>
    
    @if(session('success'))
        <div class="admin-success">{{ session('success') }}</div>
    @endif
    
    @if(session('errors') && is_array(session('errors')))
        <div class="admin-error">
            <strong>Ошибки при загрузке:</strong>
            <ul style="margin: 10px 0 0 20px; padding: 0;">
                @foreach(session('errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.blog.create') }}" class="admin-btn">➕ Добавить запись</a>
        <a href="{{ route('admin.blog.upload.form') }}" class="admin-btn" style="background-color: #27ae60; margin-left: 10px;">📤 Загрузка CSV</a>
    </div>
    
    @if($blogs->isEmpty())
        <p style="color: #7f8c8d;">Записей в блоге пока нет.</p>
    @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Тема</th>
                    <th>Дата создания</th>
                    <th>Изображение</th>
                </tr>
            </thead>
            <tbody>
                @foreach($blogs as $blog)
                <tr>
                    <td>{{ $blog->id }}</td>
                    <td>{{ $blog->topic }}</td>
                    <td>{{ $blog->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        @if($blog->image)
                            <img src="/storage/{{ $blog->image }}" alt="Изображение" style="max-width: 100px; max-height: 50px; object-fit: cover;">
                        @else
                            <span style="color: #95a5a6;">Нет</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($totalPages > 1)
        <div style="margin-top: 20px; display: flex; gap: 10px; align-items: center;">
            @if($currentPage > 1)
                <a href="?page={{ $currentPage - 1 }}" class="admin-btn" style="padding: 8px 15px;">← Назад</a>
            @endif
            
            <span>Страница {{ $currentPage }} из {{ $totalPages }}</span>
            
            @if($currentPage < $totalPages)
                <a href="?page={{ $currentPage + 1 }}" class="admin-btn" style="padding: 8px 15px;">Вперед →</a>
            @endif
        </div>
        @endif
    @endif
</div>
@endsection
