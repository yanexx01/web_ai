@extends('admin.layouts.app')

@section('content')
<div class="admin-card">
    <h2 style="margin-top: 0;">📤 Загрузка сообщений блога (CSV)</h2>
    
    @if($errors->any())
        <div class="admin-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <p>Загрузите CSV файл с сообщениями блога. Формат файла:</p>
    <pre style="background: #f8f9fa; padding: 15px; border-radius: 4px; overflow-x: auto;">
Тема;Сообщение;Автор;Дата создания (Y-m-d H:i:s)
    </pre>
    
    <form method="POST" action="{{ route('admin.blog.upload') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="admin-form-group">
            <label for="csv_file">CSV файл *</label>
            <input type="file" id="csv_file" name="csv_file" accept=".csv,.txt" required>
            <small style="color: #7f8c8d;">Максимальный размер: 10MB</small>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="admin-btn">📤 Загрузить</button>
            <a href="{{ route('admin.blog.index') }}" class="admin-btn" style="background-color: #95a5a6;">Отмена</a>
        </div>
    </form>
</div>
@endsection
