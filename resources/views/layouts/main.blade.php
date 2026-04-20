<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="author" content="Lucky"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</header>

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
