$(function() {
    // Задержка перед показом осталась (чтобы подсказка не мелькала при случайном движении)
    const POPOVER_SHOW_DELAY = 300;    
    const POPOVER_WIDTH = 280;         
    
    let popoverTimer = null;
    let currentPopover = null;

    function initPopovers() {
        $('[data-popover]').each(function() {
            const $element = $(this);

            // Создаем уникальный ID и сам элемент подсказки
            const popoverId = 'popover-' + Math.random().toString(36).substr(2, 9);
            const $popover = $('<div>')
                .attr('id', popoverId)
                .addClass('popover')
                .html($element.data('popover'))
                .hide()
                .appendTo('body');

            $element.data('popover-element', $popover);

            // При наведении на поле — показываем подсказку
            $element.on('mouseenter', function() {
                showPopover($element, $popover);
            });
            
            // ИСПРАВЛЕНО: При уходе с поля — скрываем подсказку СРАЗУ
            $element.on('mouseleave', function() {
                hidePopover($popover);
            });
            
            // Если курсор переходит с поля на подсказку, она должна остаться видимой.
            // Поэтому на самой подсказке тоже обрабатываем уход курсора.
            $popover.on('mouseenter', function() {
                // Если вдруг сработал таймер скрытия (хотя мы его убрали из element mouseleave), отменяем его
                clearTimeout(popoverTimer);
            });
            
            // ИСПРАВЛЕНО: При уходе с самой подсказки — скрываем её СРАЗУ
            $popover.on('mouseleave', function() {
                hidePopover($popover);
            });
        });
    }

    function showPopover($element, $popover) {
        // Отменяем любые предыдущие таймеры (на случай быстрого движения мыши)
        clearTimeout(popoverTimer);

        const elementRect = $element[0].getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
        
        let top = elementRect.top + scrollTop;
        let left = elementRect.right + scrollLeft + 10;

        // Проверка, чтобы подсказка не ушла за правый край экрана
        if (left + POPOVER_WIDTH > window.innerWidth) {
            left = elementRect.left + scrollLeft - POPOVER_WIDTH - 10;
            $popover.addClass('left-side').removeClass('right-side');
        } else {
            $popover.addClass('right-side').removeClass('left-side');
        }

        // Проверка по вертикали (низ экрана)
        if (top + $popover.outerHeight() > window.innerHeight + scrollTop) {
            top = window.innerHeight + scrollTop - $popover.outerHeight() - 10;
        }
        
        // Проверка по вертикали (верх экрана)
        if (top < scrollTop) {
            top = scrollTop + 10;
        }
        
        $popover.css({
            top: top + 'px',
            left: left + 'px',
            width: POPOVER_WIDTH + 'px'
        });
        
        $popover.stop(true, true).fadeIn(200); // stop() предотвращает накопление анимаций
        currentPopover = $popover;
    }

    function hidePopover($popover) {
        clearTimeout(popoverTimer); // На всякий случай чистим таймер
        $popover.stop(true, true).fadeOut(200);
        currentPopover = null;
    }

    // Скрытие при клике в любое другое место страницы
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.popover').length && !$(e.target).is('[data-popover]')) {
            $('.popover').stop(true, true).fadeOut(200);
        }
    });

    initPopovers();
});