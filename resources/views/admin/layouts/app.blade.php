<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="author" content="Lucky"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Админ-панель' }}</title>
    
    <link rel="stylesheet" href="/css/style.css"/>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <script src="/js/menu.js"></script>
    <script src="/js/clock.js"></script>
    <script src="/js/tracking.js"></script>
    <script src="/js/popover.js"></script>
    <script src="/js/modal.js"></script>

    <script>
    const pageName = "{{ $pageName ?? 'admin' }}";
    
    $(function() {
        if (typeof trackPageView === 'function') {
            trackPageView(pageName);
        }
    });
    </script>
    
    <style>
        /* Специфичные стили для админки */
        .admin-header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 20px;
        }
        
        .admin-nav {
            background-color: #34495e;
            min-height: 100vh;
        }
        
        .admin-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .admin-nav li {
            border-bottom: 1px solid #4a6278;
        }
        
        .admin-nav a {
            display: block;
            padding: 12px 20px;
            color: #ecf0f1;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .admin-nav a:hover {
            background-color: #4a6278;
        }
        
        .admin-nav a.active {
            background-color: #3498db;
        }
        
        .admin-content {
            padding: 20px;
            background-color: #f5f6fa;
            min-height: calc(100vh - 60px);
        }
        
        .admin-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .admin-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        
        .admin-btn:hover {
            background-color: #2980b9;
        }
        
        .admin-btn-danger {
            background-color: #e74c3c;
        }
        
        .admin-btn-danger:hover {
            background-color: #c0392b;
        }
        
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .admin-table th,
        .admin-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .admin-table th {
            background-color: #34495e;
            color: white;
        }
        
        .admin-table tr:hover {
            background-color: #f5f5f5;
        }
        
        .admin-success {
            color: #27ae60;
            padding: 10px;
            background-color: #d4edda;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .admin-error {
            color: #e74c3c;
            padding: 10px;
            background-color: #f8d7da;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .admin-form-group {
            margin-bottom: 15px;
        }
        
        .admin-form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .admin-form-group input,
        .admin-form-group textarea,
        .admin-form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .admin-form-group textarea {
            min-height: 150px;
            resize: vertical;
        }
    </style>
</head>

<body>
    <header class="admin-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1 style="margin: 0; font-size: 24px;">🔧 Админ-панель</h1>
            <div style="display: flex; align-items: center; gap: 15px;">
                @auth
                    <span>{{ Auth::user()->name }} ({{ Auth::user()->email }})</span>
                    <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="admin-btn admin-btn-danger" style="padding: 8px 15px;">Выйти</button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <div style="display: flex;">
        <!-- Боковое меню админки -->
        <nav class="admin-nav" style="width: 250px; flex-shrink: 0;">
            <ul>
                <li><a href="{{ route('admin.blog.index') }}" class="{{ $pageName === 'admin-blog' ? 'active' : '' }}">📝 Блог</a></li>
                <li><a href="{{ route('admin.blog.create') }}" class="{{ $pageName === 'admin-blog-create' ? 'active' : '' }}">➕ Добавить запись</a></li>
                <li><a href="{{ route('admin.blog.upload.form') }}" class="{{ $pageName === 'admin-blog-upload' ? 'active' : '' }}">📤 Загрузка CSV</a></li>
                <li><a href="{{ route('admin.guestbook.index') }}" class="{{ $pageName === 'admin-guestbook' ? 'active' : '' }}">📖 Гостевая книга</a></li>
                <li><a href="{{ route('admin.guestbook.upload.form') }}" class="{{ $pageName === 'admin-guestbook-upload' ? 'active' : '' }}">📥 Импорт сообщений</a></li>
                <li><a href="{{ route('admin.stats.index') }}" class="{{ $pageName === 'admin-stats' ? 'active' : '' }}">📊 Статистика посещений</a></li>
                <li><a href="/">🏠 На сайт</a></li>
            </ul>
        </nav>

        <!-- Основной контент -->
        <main class="admin-content" style="flex-grow: 1;">
            @yield('content')
        </main>
    </div>
</body>
</html>
