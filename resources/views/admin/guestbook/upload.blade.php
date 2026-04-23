@extends('admin.layouts.app')

@section('content')
<div class="admin-card">
    <h2 style="margin-top: 0;">📤 Загрузка сообщений гостевой книги</h2>
    
    @if($errors->any())
        <div class="admin-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('admin.guestbook.upload') }}" method="POST" enctype="multipart/form-data" style="max-width: 500px;">
        @csrf
        
        <div style="margin-bottom: 20px;">
            <label for="messages_file" style="display: block; margin-bottom: 8px; font-weight: 600;">Файл messages.inc:</label>
            <input type="file" name="messages_file" id="messages_file" accept=".inc,.txt" required 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
            <small style="color: #7f8c8d; display: block; margin-top: 5px;">
                Формат файла: каждая строка содержит данные в формате: date;FIO;email;message
            </small>
        </div>
        
        <button type="submit" class="admin-btn" style="background-color: #27ae60;">📤 Загрузить</button>
        <a href="{{ route('admin.guestbook.index') }}" class="admin-btn" style="background-color: #95a5a6; margin-left: 10px;">Отмена</a>
    </form>
</div>
@endsection
