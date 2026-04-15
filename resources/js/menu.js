$(function () {
    const menuItems = $(".menu-item");

    let currentPage = window.location.pathname.replace(/^\/|\/$/g, '');

    if (currentPage === '') {
        currentPage = 'home';
    }

    menuItems.each(function () {
        const $this = $(this);
        const href = $this.attr("href");
        
        let isActive = false;

        if (href === '/' && (currentPage === '' || currentPage === 'index.php')) {
            isActive = true;
        } else if ('/' + currentPage === href) {
            isActive = true;
        }

        if (isActive) {
            menuItems.removeClass("active");
            $this.addClass("active");
        }
    });

    // === Управление боковым меню ===
    const menuToggle = $('#menuToggle');
    const sidebarMenu = $('#sidebarMenu');
    const sidebarClose = $('#sidebarClose');
    const menuOverlay = $('#menuOverlay');
    const body = $('body');

    function openMenu() {
        sidebarMenu.addClass('open');
        menuOverlay.addClass('show');
        body.addClass('menu-open');
    }

    function closeMenu() {
        sidebarMenu.removeClass('open');
        menuOverlay.removeClass('show');
        body.removeClass('menu-open');
    }

    menuToggle.on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        openMenu();
    });

    sidebarClose.on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        closeMenu();
    });

    menuOverlay.on('click', function(e) {
        e.preventDefault();
        closeMenu();
    });

    // Закрытие меню при клике на пункт меню
    $('.sidebar-item, .dropdown-link').on('click', function() {
        closeMenu();
    });

    // Выпадающее меню в сайдбаре
    $('.sidebar-dropdown > .sidebar-item').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const parent = $(this).parent('.sidebar-dropdown');
        parent.toggleClass('expanded');
    });

    // Закрытие меню по Escape
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && sidebarMenu.hasClass('open')) {
            closeMenu();
        }
    });
});