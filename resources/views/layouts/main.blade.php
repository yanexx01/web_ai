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

<body>
    <header id="top">
        <button class="menu-toggle" id="menuToggle" aria-label="Открыть меню">
            <span></span>
            <span></span>
            <span></span>
        </button>
        
        <nav class="sidebar-menu" id="sidebarMenu">
            <div class="sidebar-header">
                <h2>Меню</h2>
                <button class="sidebar-close" id="sidebarClose" aria-label="Закрыть меню">&times;</button>
            </div>
            <ul class="sidebar-list">
                <li><a href="/" class="sidebar-item" data-page="home">Главная</a></li>
                <li><a href="/about" class="sidebar-item" data-page="about">Обо мне</a></li>
                
                <li class="sidebar-dropdown">
                    <a href="/interests" class="sidebar-item" data-page="interests">Мои интересы <span class="dropdown-arrow">▼</span></a>
                    <ul class="sidebar-dropdown-menu">
                        <li><a href="/interests#hobby" class="dropdown-link">Хобби</a></li>
                        <li><a href="/interests#books" class="dropdown-link">Книги</a></li>
                        <li><a href="/interests#music" class="dropdown-link">Музыка</a></li>
                        <li><a href="/interests#games" class="dropdown-link">Игры</a></li>
                    </ul>
                </li>

                <li><a href="/study" class="sidebar-item" data-page="study">Учеба</a></li>
                <li><a href="/photos" class="sidebar-item" data-page="photos">Фотоальбом</a></li>
                <li><a href="/history" class="sidebar-item" data-page="history">История просмотра</a></li>
                <li><a href="/guestbook" class="sidebar-item" data-page="guestbook">Гостевая книга</a></li>
                <li><a href="/contacts" class="sidebar-item" data-page="contacts">Обратная связь</a></li>
                <li><a href="/test" class="sidebar-item" data-page="test">Тест</a></li>
            </ul>
            
            <div id="clock" class="clock-display-sidebar">
                <p>Загрузка...</p>
            </div>
        </nav>
        
        <div class="menu-overlay" id="menuOverlay"></div>
    </header>

    <main>
        @yield('content')
    </main>
    
    <script src="/js/photos.js"></script>
    <script src="/js/contacts.js"></script>
    <script src="/js/interests.js"></script>
</body>
</html>
