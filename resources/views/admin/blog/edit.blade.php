@extends('admin.layouts.app')

@section('content')
<div class="admin-card">
    <h2 style="margin-top: 0;">✏️ Редактирование записи блога</h2>

    <!-- Форма отправляется на скрытый iFrame -->
    <form id="editForm" action="{{ route('admin.blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data" target="editFrame">        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="topic">Тема записи:</label>
            <input type="text"
                   id="topic"
                   name="topic"
                   value="{{ old('topic', $blog->topic) }}"
                   required
                   class="admin-input">
        </div>

        <div class="form-group">
            <label for="message">Текст записи:</label>
            <textarea id="message"
                      name="message"
                      rows="10"
                      required
                      class="admin-input">{{ old('message', $blog->message) }}</textarea>
        </div>

        
        <div class="form-group">
            <label for="image">Изображение:</label>
            @if($blog->image)
                <div style="margin-bottom: 10px;">
                    <img src="/storage/{{ $blog->image }}" alt="Текущее изображение" style="max-width: 300px; max-height: 200px; object-fit: cover; border-radius: 6px;">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="remove_image" value="1" id="removeImage" style="width: auto;">
                        <span>Удалить текущее изображение</span>
                    </label>
                </div>
            @endif
            <input type="file"
                   id="image"
                   name="image"
                   accept="image/*"
                   class="admin-input"
                   onchange="previewImage(this)">
            <small style="color: #7f8c8d;">Допустимые форматы: jpg, jpeg, png, gif, webp. Максимальный размер: 10MB</small>
            <div id="imagePreview" style="margin-top: 10px;"></div>
        </div>

        <div class="form-actions">
            <button type="submit" class="admin-btn">💾 Сохранить изменения</button>
            <a href="{{ route('admin.blog.index') }}" class="admin-btn" style="background-color: #95a5a6;">Отмена</a>
        </div>

        <div id="loadingIndicator" style="display: none; margin-top: 15px; color: #3498db;">
            ⏳ Сохранение...
        </div>

        <div id="resultMessage" style="margin-top: 15px;"></div>
    </form>

    <!-- Скрытый iFrame для обработки ответа (требование задания: iFrame + JSON) -->
    <iframe name="editFrame"
            id="editFrame"
            style="display: none;"
            onload="handleFrameLoad()">
    </iframe>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const removeCheckbox = document.getElementById('removeImage');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Предпросмотр" style="max-width: 300px; max-height: 200px; object-fit: cover; border-radius: 6px;">';
        };
        reader.readAsDataURL(input.files[0]);

        // Снимаем галочку удаления при выборе нового файла
        if (removeCheckbox) {
            removeCheckbox.checked = false;
        }
    } else {
        preview.innerHTML = '';
    }
}
let isSubmitting = false;

// Функция, вызываемая из родительского окна после получения ответа от сервера
function handleEditResponse(data) {
    const loadingIndicator = document.getElementById('loadingIndicator');
    const resultMessage = document.getElementById('resultMessage');

    loadingIndicator.style.display = 'none';
    isSubmitting = false;

    if (data.success) {
        // Успешное обновление
        resultMessage.innerHTML = '<div class="admin-success">✅ Запись успешно обновлена!</div>';

        // Обновляем данные в таблице на родительской странице
        if (window.opener && !window.opener.closed) {
            window.opener.updateBlogRow(data);
        }

        // Через 2 секунды возвращаемся к списку блогов
        setTimeout(function() {
            if (window.opener && !window.opener.closed) {
                window.close();
            } else {
                window.location.href = '{{ route("admin.blog.index") }}';
            }
        }, 1500);
    } else {
        // Ошибка валидации или другая ошибка
        let errorsHtml = '<div class="admin-error"><strong>Ошибки:</strong><ul>';
        if (data.errors) {
            for (let key in data.errors) {
                if (Array.isArray(data.errors[key])) {
                    data.errors[key].forEach(function(error) {
                        errorsHtml += '<li>' + error + '</li>';
                    });
                }
            }
        }
        errorsHtml += '</ul></div>';
        resultMessage.innerHTML = errorsHtml;
    }
}

// Обработчик загрузки iFrame (для обработки случаев, когда ответ не пришел)
function handleFrameLoad() {
    if (isSubmitting) {
        // Если форма была отправлена, но ответ еще не обработан
        // Ждем вызова handleEditResponse из скрипта в ответе сервера
        setTimeout(function() {
            if (isSubmitting) {
                document.getElementById('loadingIndicator').style.display = 'none';
                document.getElementById('resultMessage').innerHTML =
                    '<div class="admin-error">⚠️ Произошла ошибка при сохранении. Попробуйте еще раз.</div>';
                isSubmitting = false;
            }
        }, 5000); // Таймаут 5 секунд
    }
}

// Отправка формы с показом индикатора загрузки
document.getElementById('editForm').addEventListener('submit', function(e) {
    if (isSubmitting) {
        e.preventDefault();
        return false;
    }

    isSubmitting = true;
    document.getElementById('loadingIndicator').style.display = 'block';
    document.getElementById('resultMessage').innerHTML = '';

    // Форма отправляется автоматически через target="editFrame"
});
</script>

<style>
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
}

.admin-input {
    width: 100%;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s;
    box-sizing: border-box;
}

.admin-input:focus {
    outline: none;
    border-color: #3498db;
}

textarea.admin-input {
    resize: vertical;
    min-height: 200px;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 25px;
}

.admin-btn {
    display: inline-block;
    padding: 12px 24px;
    background-color: #3498db;
    color: white;
    text-decoration: none;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: background-color 0.3s;
}

.admin-btn:hover {
    background-color: #2980b9;
}

.admin-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
    padding: 15px;
    border-radius: 6px;
    margin-top: 15px;
}

.admin-error {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
    padding: 15px;
    border-radius: 6px;
    margin-top: 15px;
}

.admin-error ul {
    margin: 10px 0 0 20px;
    padding: 0;
}
</style>
@endsection