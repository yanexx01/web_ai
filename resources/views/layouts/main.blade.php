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
    <!-- Кнопка открытия бокового меню -->
    <button class="menu-toggle" id="menuToggle" aria-label="Открыть меню">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <!-- Боковое меню -->
    <aside class="sidebar-menu" id="sidebarMenu">
        <div class="sidebar-header">
            <h2>Меню</h2>
            <button class="sidebar-close" id="sidebarClose" aria-label="Закрыть меню">&times;</button>
        </div>
        
        <nav class="sidebar-nav">
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
                <li><a href="/guestbook" class="menu-item" data-page="guestbook">Гостевая книга</a></li>
                <li><a href="/contacts" class="menu-item" data-page="contacts">Обратная связь</a></li>
                <li><a href="/test" class="menu-item" data-page="test">Тест</a></li>
                <li><a href="/blog" class="menu-item" data-page="blog">Блог</a></li>
            </ul>
        </nav>
        
        <div id="clock" class="clock-display">
            <p>Загрузка...</p>
        </div>
    </aside>

    <!-- Затемнение фона при открытом меню -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <main>
        @yield('content')
    </main>
    
    <script src="/js/photos.js"></script>
    <script src="/js/contacts.js"></script>
    <script src="/js/interests.js"></script>
    <script>
    // Скрипт управления боковым меню
    $(function() {
        const $toggle = $('#menuToggle');
        const $sidebar = $('#sidebarMenu');
        const $close = $('#sidebarClose');
        const $overlay = $('#sidebarOverlay');
        
        function openMenu() {
            $sidebar.addClass('open');
            $overlay.addClass('active');
            $('body').addClass('menu-open');
        }
        
        function closeMenu() {
            $sidebar.removeClass('open');
            $overlay.removeClass('active');
            $('body').removeClass('menu-open');
        }
        
        $toggle.on('click', openMenu);
        $close.on('click', closeMenu);
        $overlay.on('click', closeMenu);
        
        // Закрытие по ESC
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $sidebar.hasClass('open')) {
                closeMenu();
            }
        });
        
        // Закрытие при клике на ссылку меню (для мобильных)
        $sidebar.find('a.menu-item').on('click', function() {
            if (window.innerWidth <= 768) {
                closeMenu();
            }
        });
    });
    </script>
</body>
</html>
