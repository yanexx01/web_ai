@extends('layouts.main')

@section('content')
<div class="blog-page-wrapper">
    <h1 class="blog-title">{{ $pageTitle ?? 'Загрузка CSV' }}</h1>
    <p class="blog-subtitle">Импорт записей блога из файла</p>

    {{-- 1. Стандартные ошибки валидации (используем $errors, а не session('errors')) --}}
    @if ($errors->any())
        <div class="alert alert-error">
            <h5 style="margin-top: 0;">Ошибки при загрузке:</h5>
            <ul style="margin-bottom: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 2. Успешное сообщение --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="upload-card">
        <div class="upload-header">
            <h2 class="upload-title">Выберите файл для загрузки</h2>
        </div>
        
        <div class="upload-body">
            <div class="info-block">
                <p class="text-muted">
                    <strong>Требования к файлу:</strong><br>
                    Формат: <code>.csv</code> или <code>.txt</code><br>
                    Кодировка: UTF-8<br>
                    Структура: <code>title,message,author,created_at</code>
                </p>
                <div class="csv-example">
                    <h6>Пример содержимого:</h6>
                    <pre>"Моя первая запись", "Текст сообщения...", "Admin", "2024-01-01 12:00"
"Вторая запись", "Еще один текст", "User", "2024-01-02 14:30"</pre>
                </div>
            </div>

            <form action="{{ route('blog.upload') }}" method="POST" enctype="multipart/form-data" class="upload-form">
                @csrf

                <div class="form-group">
                    <label for="csv_file" class="form-label">Файл CSV</label>
                    <input type="file" 
                          class="form-control @error('csv_file') is-invalid @enderror" 
                          id="csv_file" 
                          name="csv_file" 
                          accept=".csv,.txt"
                          required>
                    @error('csv_file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="button-group" style="margin-top: 25px; justify-content: flex-start;">
                    <button type="submit" class="btn-submit">
                        📥 Загрузить записи
                    </button>
                    <a href="{{ route('blog.index') }}" class="btn-reset">
                        ← Назад к блогу
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Наследуем основные стили из index.blade.php */
    .blog-page-wrapper { max-width: 900px; margin: 80px auto 40px; padding: 0 20px; }
    .blog-title { text-align: center; color: #222; margin-bottom: 10px; font-size: 2rem; }
    .blog-subtitle { text-align: center; color: #555; margin-bottom: 30px; font-size: 1.1rem; }
    
    /* Стили карточки загрузки (аналогично blog-post) */
    .upload-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 30px;
    }
    
    .upload-header {
        background: #f8f9fa;
        padding: 20px 25px;
        border-bottom: 1px solid #eee;
    }
    
    .upload-title {
        margin: 0;
        font-size: 1.3rem;
        color: #333;
    }
    
    .upload-body {
        padding: 25px;
    }
    
    /* Информационный блок */
    .info-block {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 25px;
        border: 1px solid #eee;
    }
    
    .text-muted {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 15px;
    }
    
    .text-muted code {
        background: #e9ecef;
        padding: 2px 6px;
        border-radius: 4px;
        color: #d63384;
        font-family: monospace;
    }
    
    .csv-example pre {
        background: #2d2d2d;
        color: #ccc;
        padding: 15px;
        border-radius: 6px;
        font-size: 0.85rem;
        overflow-x: auto;
        margin: 10px 0 0 0;
    }
    
    .csv-example h6 {
        margin: 0 0 5px 0;
        color: #444;
        font-size: 0.9rem;
    }

    /* Стили формы */
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
    }
    
    .form-control {
        display: block;
        width: 100%;
        padding: 10px 12px;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 6px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .form-control:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    
    .is-invalid {
        border-color: #dc3545;
    }
    
    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
    }

    /* Кнопки (такие же как в index) */
    .button-group {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .btn-submit, .btn-reset {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-submit {
        background-color: #333;
        color: white;
    }

    .btn-submit:hover {
        background-color: #555;
        transform: translateY(-2px);
    }

    .btn-reset {
        background-color: #f0f0f0;
        color: #333;
    }

    .btn-reset:hover {
        background-color: #e0e0e0;
        color: #000;
    }

    /* Алерты */
    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 500;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>
@endsection