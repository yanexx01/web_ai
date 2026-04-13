<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <meta name="author" content="Lucky"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Личный сайт' }}</title>
    
    <link rel="stylesheet" href="/resources/css/style.css"/>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <script src="/resources/js/menu.js"></script>
    <script src="/resources/js/clock.js"></script>
    <script src="/resources/js/tracking.js"></script>
    <script src="/resources/js/popover.js"></script>
    <script src="/resources/js/modal.js"></script>

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
        <nav class="main-menu">
            <ul>
                <li><a href="/" class="menu-item" data-page="home">Главная</a></li>
                <li><a href="/about" class="menu-item" data-page="about">Обо мне</a></li>
                
                <li class="dropdown">
                    <a href="/interests" class="menu-item" data-page="interests">Мои интересы</a>
                    <ul class="dropdown-menu">
                        <li><a href="/interests#hobby" class="dropdown-link">Хобби</a></li>
                        <li><a href="/interests#books" class="dropdown-link">Книги</a></li>
                        <li><a href="/interests#music" class="dropdown-link">Музыка</a></li>
                        <li><a href="/interests#games" class="dropdown-link">Игры</a></li>
                    </ul>
                </li>

                <li><a href="/study" class="menu-item" data-page="study">Учеба</a></li>
                <li><a href="/photos" class="menu-item" data-page="photos">Фотоальбом</a></li>
                <li><a href="/history" class="menu-item" data-page="history">История просмотра</a></li>
                <li><a href="/contacts" class="menu-item" data-page="contacts">Обратная связь</a></li>
                <li><a href="/test" class="menu-item" data-page="test">Тест</a></li>
            </ul>
            
            <div id="clock" class="clock-display">
                <p>Загрузка...</p>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>
    
    <script src="/resources/js/photos.js"></script>
    <script src="/resources/js/contacts.js"></script>
    <script src="/resources/js/interests.js"></script>
</body>
</html>
