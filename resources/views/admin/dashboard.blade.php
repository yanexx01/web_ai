@extends('admin.layouts.app')

@section('content')
<div class="admin-card">
    <h2 style="margin-top: 0;">👋 Панель администратора</h2>
    
    <p>Добро пожаловать в панель управления сайтом!</p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
        <a href="{{ route('admin.blog.index') }}" class="admin-card" style="text-decoration: none; color: inherit; display: block;">
            <h3 style="margin: 0 0 10px 0; color: #3498db;">📝 Блог</h3>
            <p style="color: #7f8c8d; margin: 0;">Управление записями блога</p>
        </a>
        
        <a href="{{ route('admin.guestbook.index') }}" class="admin-card" style="text-decoration: none; color: inherit; display: block;">
            <h3 style="margin: 0 0 10px 0; color: #27ae60;">💬 Гостевая книга</h3>
            <p style="color: #7f8c8d; margin: 0;">Управление сообщениями</p>
        </a>
    </div>
</div>
@endsection
