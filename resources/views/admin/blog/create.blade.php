@extends('admin.layouts.app')

@section('content')
<div class="admin-card">
    <h2 style="margin-top: 0;">➕ Добавить запись в блог</h2>
    
    @if($errors->any())
        <div class="admin-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="admin-form-group">
            <label for="topic">Тема записи *</label>
            <input type="text" id="topic" name="topic" value="{{ old('topic') }}" required>
        </div>
        
        <div class="admin-form-group">
            <label for="image">Изображение</label>
            <input type="file" id="image" name="image" accept="image/*">
            <small style="color: #7f8c8d;">Допустимые форматы: jpg, jpeg, png, gif, webp. Максимальный размер: 10MB</small>
        </div>
        
        <div class="admin-form-group">
            <label for="message">Сообщение *</label>
            <textarea id="message" name="message" required>{{ old('message') }}</textarea>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="admin-btn">💾 Сохранить</button>
            <a href="{{ route('admin.blog.index') }}" class="admin-btn" style="background-color: #95a5a6;">Отмена</a>
        </div>
    </form>
</div>
@endsection
