@extends('admin.layouts.app')

@section('content')
<div class="admin-card">
    <h2 style="margin-top: 0;">💬 Гостевая книга</h2>
    
    @if(session('success'))
        <div class="admin-success">{{ session('success') }}</div>
    @endif
    
    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.guestbook.upload.form') }}" class="admin-btn" style="background-color: #27ae60;">📤 Загрузка сообщений</a>
    </div>
    
    @if($messages->isEmpty())
        <p style="color: #7f8c8d;">Сообщений в гостевой книге пока нет.</p>
    @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Сообщение</th>
                    <th>Дата</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $message)
                <tr>
                    <td>{{ $message->id }}</td>
                    <td>{{ $message->name }}</td>
                    <td>{{ $message->email }}</td>
                    <td>{{ Str::limit($message->message, 50) }}</td>
                    <td>{{ $message->created_at->format('d.m.Y H:i') }}</td>
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
