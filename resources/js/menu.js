$(function () {
    const $menuToggle = $('#menu-toggle');
    const $menuClose  = $('#menu-close');
    const $sidebar    = $('#sidebar');
    const $overlay    = $('#sidebar-overlay');

    // Открытие/закрытие
    function toggleSidebar() {
        $sidebar.toggleClass('active');
        $overlay.toggleClass('active');
    }

    $menuToggle.on('click', toggleSidebar);
    $menuClose.on('click', toggleSidebar);
    $overlay.on('click', toggleSidebar);

    // Закрытие по Esc
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $sidebar.hasClass('active')) {
            toggleSidebar();
        }
    });

    // Подсветка активного пункта меню
    const menuItems = $(".menu-item");
    let currentPage = window.location.pathname.replace(/^\/|\/$/g, '');
    if (currentPage === '') currentPage = 'home';

    menuItems.each(function () {
        const $this = $(this);
        const href  = $this.attr("href");
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
});