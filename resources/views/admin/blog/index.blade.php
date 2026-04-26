@extends('admin.layouts.app')

@section('content')
<div class="admin-card">
    <h2 style="margin-top: 0;">📝 Управление блогом</h2>
    
    @if(session('success'))
        <div class="admin-success">{{ session('success') }}</div>
    @endif
    
    @if(session('errors') && is_array(session('errors')))
        <div class="admin-error">
            <strong>Ошибки при загрузке:</strong>
            <ul style="margin: 10px 0 0 20px; padding: 0;">
                @foreach(session('errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.blog.create') }}" class="admin-btn">➕ Добавить запись</a>
        <a href="{{ route('admin.blog.upload.form') }}" class="admin-btn" style="background-color: #27ae60; margin-left: 10px;">📤 Загрузка CSV</a>
    </div>
    
    @if($blogs->isEmpty())
        <p style="color: #7f8c8d;">Записей в блоге пока нет.</p>
    @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Тема</th>
                    <th>Дата создания</th>
                    <th>Изображение</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($blogs as $blog)
                <tr id="blog-row-{{ $blog->id }}">
                    <td>{{ $blog->id }}</td>
                    <td id="blog-topic-{{ $blog->id }}">{{ $blog->topic }}</td>
                    <td>{{ is_string($blog->created_at) ? \Carbon\Carbon::parse($blog->created_at)->format('d.m.Y H:i') : $blog->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        @if($blog->image)
                            <img src="/storage/{{ $blog->image }}" alt="Изображение" style="max-width: 100px; max-height: 50px; object-fit: cover;">
                        @else
                            <span style="color: #95a5a6;">Нет</span>
                        @endif
                    </td>
                    <td>
                        <!-- Кнопка "Изменить" (требование задания 3) -->
                        <button onclick="openEditWindow({{ $blog->id }})" class="admin-btn" style="padding: 6px 12px; font-size: 13px;">✏️ Изменить</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($totalPages > 1)
        <div style="margin-top: 20px; display: flex; gap: 10px; align-items: center;">
            @if($currentPage > 1)
                <a href="?page={{ $currentPage - 1 }}" class="admin-btn" style="padding: 8px 15px;">← Назад</a>
            @endif
            
            <span>Страница {{ $currentPage }} из {{ $totalPages }}</span>
            
            @if($currentPage < $totalPages)
                <a href="?page={{ $currentPage + 1 }}" class="admin-btn" style="padding: 8px 15px;">Вперед →</a>
            @endif
        </div>
        @endif
    @endif
</div>

<!-- Модальное окно (div) для редактирования записей блога (требование задания 3) -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 8px; max-width: 700px; width: 90%; max-height: 90vh; overflow-y: auto; position: relative;">
        <button onclick="closeEditWindow()" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 24px; cursor: pointer; color: #95a5a6;">&times;</button>
        <div id="editModalContent">
            <!-- Сюда будет загружаться форма редактирования -->
        </div>
    </div>
</div>

<script>
// Функция открытия окна редактирования
function openEditWindow(blogId) {
    const modal = document.getElementById('editModal');
    const content = document.getElementById('editModalContent');

    // Показываем модальное окно
    modal.style.display = 'flex';
    content.innerHTML = '<p style="text-align: center; color: #3498db;">⏳ Загрузка формы...</p>';

    // Загружаем форму редактирования через fetch (для отображения в модальном окне)
    fetch(`/admin/blog/${blogId}/edit`)
        .then(response => response.text())
        .then(html => {
            // Извлекаем только содержимое формы из ответа
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const formContent = doc.querySelector('.admin-card');

            if (formContent) {
                content.innerHTML = formContent.innerHTML;

                // Добавляем скрипты из загруженного контента
                const scripts = doc.querySelectorAll('script');
                scripts.forEach(script => {
                    const newScript = document.createElement('script');
                    newScript.textContent = script.textContent;
                    document.body.appendChild(newScript);
                });
            } else {
                content.innerHTML = '<p style="color: #e74c3c;">Ошибка загрузки формы</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = '<p style="color: #e74c3c;">Ошибка загрузки формы</p>';
        });
}

// Функция закрытия окна редактирования
function closeEditWindow() {
    document.getElementById('editModal').style.display = 'none';
}

// Функция обновления строки в таблице после успешного редактирования
function updateBlogRow(data) {
    if (data.success && data.id) {
        const topicCell = document.getElementById(`blog-topic-${data.id}`);
        if (topicCell) {
            topicCell.textContent = data.topic;
        }

        // Подсветка обновленной строки
        const row = document.getElementById(`blog-row-${data.id}`);
        if (row) {
            row.style.backgroundColor = '#d4edda';
            setTimeout(() => {
                row.style.backgroundColor = '';
            }, 2000);
        }
    }
}

// Закрытие модального окна при клике вне его области
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditWindow();
    }
});

// Закрытие по Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditWindow();
    }
});
</script>
@endsection
