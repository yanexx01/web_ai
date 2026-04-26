<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="author" content="Lucky"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Личный сайт' }}</title>
    
    <link rel="stylesheet" href="/css/style.css"/>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <script src="/js/menu.js"></script>
    <script src="/js/clock.js"></script>
    <script src="/js/tracking.js"></script>
    <script src="/js/popover.js"></script>
    <script src="/js/modal.js"></script>

    <script>
    const pageName = "{{ $pageName ?? 'unknown' }}";
    
    $(function() {
        if (typeof trackPageView === 'function') {
            trackPageView(pageName);
        }
    });
    </script>
</head>

<header class="header">
    <button id="menu-toggle" class="menu-toggle-btn">☰</button>
    <div id="clock" class="clock-display"></div>
    
    <!-- Отображение информации о пользователе -->
    @auth
        <div class="user-info">
            {{ Auth::user()->name }}: {{ Auth::user()->login }}
        </div>
    @endauth
</header>

<!-- Боковое меню -->
<nav id="sidebar" class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="/" class="menu-item" data-page="home">Главная</a></li>
        <li><a href="/about" class="menu-item" data-page="about">Обо мне</a></li>
        <li><a href="/study" class="menu-item" data-page="study">Учеба</a></li>
        <li><a href="/interests" class="menu-item" data-page="interests">Интересы</a></li>
        <li><a href="/photos" class="menu-item" data-page="photos">Фотоальбом</a></li>
        <li><a href="/contacts" class="menu-item" data-page="contacts">Обратная связь</a></li>
        <li><a href="/test" class="menu-item" data-page="test">Тест</a></li>
        <li><a href="/guestbook" class="menu-item" data-page="guestbook">Гостевая книга</a></li>
        <li><a href="/blog" class="menu-item" data-page="blog">Блог</a></li>
        
        <!-- Ссылки авторизации -->
        @guest
            <li><a href="{{ route('login') }}" class="menu-item" data-page="login">Войти</a></li>
            <li><a href="{{ route('register') }}" class="menu-item" data-page="register">Регистрация</a></li>
        @else
            <li>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="menu-item logout-btn">Выйти</button>
                </form>
            </li>
        @endguest
    </ul>
</nav>

<!-- Затемняющий фон при открытом меню -->
<div id="sidebar-overlay" class="sidebar-overlay"></div>
<body>
    <main>
        @yield('content')
    </main>
    
    <script src="/js/photos.js"></script>
    <script src="/js/contacts.js"></script>
    <script src="/js/interests.js"></script>
</html>
