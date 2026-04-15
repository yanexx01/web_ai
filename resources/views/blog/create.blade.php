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
            <label for="image">Изображение:</label>
            <input type="file" 
                   id="image" 
                   name="image" 
                   accept="image/*"
                   onchange="previewImage(this)">
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
    
    if (input.files && input.files[0]) {
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
