@extends('layouts.main')

@section('content')
<div class="upload-page">
    <h1>Загрузка сообщений гостевой книги</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('guestbook.upload') }}" method="post" enctype="multipart/form-data" class="upload-form">
        @csrf
        
        <div class="form-group">
            <label for="messages_file" class="form-label">Выберите файл messages.inc:</label>
            <input type="file" 
                   class="form-control" 
                   name="messages_file" 
                   id="messages_file" 
                   accept=".inc,.txt" 
                   required>
            <p class="form-text">
                Файл должен содержать строки в формате: Дата;ФИО;E-mail;Текст отзыва<br>
                Пример: 14.04.26;Иванов Иван Иванович;ivan@example.com;Отличный сайт!
            </p>
        </div>

        <div class="button-group">
            <button type="submit" class="btn-submit">Загрузить файл</button>
            <a href="{{ route('guestbook.index') }}" class="btn-back">Назад к гостевой книге</a>
        </div>
    </form>
</div>
@endsection
