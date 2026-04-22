@extends('layouts.main')

@section('content')
<div class="contact-page">
    <h1>Загрузка сообщений гостевой книги</h1>
    
    @if(session('success'))
        <div class="alert alert-success" style="color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('guestbook.upload') }}" method="post" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label for="messages_file">Выберите файл messages.inc:</label>
            <input type="file" name="messages_file" id="messages_file" accept=".inc,.txt" required>
            <p style="font-size: 0.9em; color: #666; margin-top: 10px;">
                Файл должен содержать строки в формате: Дата;ФИО;E-mail;Текст отзыва<br>
                Пример: 14.04.26;Иванов Иван Иванович;ivan@example.com;Отличный сайт!
            </p>
        </div>

        <div class="button-group">
            <input type="submit" value="Загрузить файл" class="btn-submit">
            <a href="{{ route('guestbook.index') }}" class="btn-back" style="margin-left: 10px; padding: 10px 20px; text-decoration: none; background-color: #6c757d; color: white; border-radius: 5px;">Назад к гостевой книге</a>
        </div>
    </form>
</div>
@endsection
