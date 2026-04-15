$(function () {
    const menuItems = $(".sidebar-item");
    const menuToggle = $("#menuToggle");
    const sidebarMenu = $("#sidebarMenu");
    const sidebarClose = $("#sidebarClose");
    const menuOverlay = $("#menuOverlay");
    const sidebarDropdowns = $(".sidebar-dropdown > .sidebar-item");
    
    let currentPage = window.location.pathname.replace(/^\/|\/$/g, '');

    if (currentPage === '') {
        currentPage = 'home';
    }

    // Активный пункт меню
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
    
    // Открытие/закрытие меню
    function openMenu() {
        sidebarMenu.addClass('open');
        menuOverlay.addClass('show');
        $('body').addClass('menu-open');
    }
    
    function closeMenu() {
        sidebarMenu.removeClass('open');
        menuOverlay.removeClass('show');
        $('body').removeClass('menu-open');
    }
    
    menuToggle.on('click', openMenu);
    sidebarClose.on('click', closeMenu);
    menuOverlay.on('click', closeMenu);
    
    // Закрытие по ESC
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && sidebarMenu.hasClass('open')) {
            closeMenu();
        }
    });
    
    // Выпадающее меню в сайдбаре
    sidebarDropdowns.on('click', function(e) {
        e.preventDefault();
        const $parent = $(this).parent('.sidebar-dropdown');
        const $dropdownMenu = $parent.find('.sidebar-dropdown-menu');
        
        $parent.toggleClass('expanded');
        $dropdownMenu.slideToggle(200);
    });
});
