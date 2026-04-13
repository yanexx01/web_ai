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
});