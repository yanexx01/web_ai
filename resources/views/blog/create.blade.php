@extends('layouts.main')

@section('content')
<h1>Добавить запись в блог</h1>

<div class="blog-form-container">
    @if($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ htmlspecialchars($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/blog/store" method="POST" enctype="multipart/form-data" class="blog-form">
        @csrf
        
        <div class="form-group">
            <label for="topic">Тема сообщения:</label>
            <input type="text" 
                   id="topic" 
                   name="topic" 
                   value="{{ old('topic') }}" 
                   required 
                   maxlength="255"
                   placeholder="Введите тему сообщения">
        </div>

        <div class="form-group">
            <label for="image">Изображение (макс. 10MB):</label>
            <input type="file" 
                   id="image" 
                   name="image" 
                   accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                   onchange="previewImage(this)">
            <small class="form-hint">Допустимые форматы: JPG, JPEG, PNG, GIF, WEBP. Максимальный размер: 10MB</small>
            <div id="image-preview" class="image-preview"></div>
        </div>

        <div class="form-group">
            <label for="message">Текст сообщения:</label>
            <textarea id="message" 
                      name="message" 
                      rows="8" 
                      required
                      placeholder="Введите текст сообщения">{{ old('message') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Опубликовать</button>
            <a href="/blog" class="btn-cancel">Отмена</a>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    
    // Проверка размера файла (10MB = 10 * 1024 * 1024 байт)
    const maxSize = 10 * 1024 * 1024;
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        if (file.size > maxSize) {
            alert('Размер файла превышает 10MB. Пожалуйста, выберите изображение меньшего размера.');
            input.value = ''; // Очищаем input
            return;
        }
        
        // Проверка типа файла
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            alert('Неверный формат файла. Допустимые форматы: JPG, JPEG, PNG, GIF, WEBP.');
            input.value = ''; // Очищаем input
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = 'Предпросмотр изображения';
            img.className = 'preview-img';
            preview.appendChild(img);
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<style>
.blog-form-container {
    max-width: 800px;
    margin: 30px auto;
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
}

.alert-error {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert-error ul {
    margin: 0;
    padding-left: 20px;
}

.blog-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: bold;
    margin-bottom: 8px;
    color: #333;
}

.form-group input[type="text"],
.form-group textarea {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1em;
    transition: border-color 0.3s;
}

.form-group input[type="text"]:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #007bff;
}

.form-group textarea {
    resize: vertical;
    min-height: 150px;
}

.form-group input[type="file"] {
    padding: 10px 0;
}

.form-hint {
    display: block;
    margin-top: 5px;
    font-size: 0.85em;
    color: #666;
    font-style: italic;
}

.image-preview {
    margin-top: 10px;
}

.preview-img {
    max-width: 100%;
    max-height: 300px;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 20px;
}

.btn-submit {
    padding: 12px 30px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 1em;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-submit:hover {
    background: #0056b3;
}

.btn-cancel {
    padding: 12px 30px;
    background: #6c757d;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 1em;
    transition: background 0.3s;
}

.btn-cancel:hover {
    background: #545b62;
}
</style>
@endsection
