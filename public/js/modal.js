$(function() {
    // Конфигурация
    const MODAL_CONFIG = {
        blurAmount: '5px',
        animationDuration: 300,
        closeOnOverlayClick: true,
        closeOnEsc: true
    };

    let currentModal = null;
    let isModalOpen = false;

    // Инициализация модальных окон
    function initModals() {
        // Обработка кнопок открытия модальных окон
        $('[data-modal-target]').on('click', function(e) {
            e.preventDefault();
            const targetModal = $(this).data('modal-target');
            openModal(targetModal);
        });

        // Обработка кнопок закрытия
        $(document).on('click', '[data-modal-close]', function(e) {
            e.preventDefault();
            closeCurrentModal();
        });

        // Закрытие по клику на overlay
        $(document).on('click', '.modal-overlay', function(e) {
            if (MODAL_CONFIG.closeOnOverlayClick && $(e.target).hasClass('modal-overlay')) {
                closeCurrentModal();
            }
        });

        // Закрытие по ESC
        if (MODAL_CONFIG.closeOnEsc) {
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && isModalOpen) {
                    closeCurrentModal();
                }
            });
        }
    }

    // Открытие модального окна
    function openModal(modalId) {
        const $modal = $('#' + modalId);
        if (!$modal.length || isModalOpen) return;

        currentModal = $modal;
        isModalOpen = true;

        // Создаем overlay с размытым фоном
        const $overlay = $('<div>')
            .addClass('modal-overlay')
            .css({
                'backdrop-filter': `blur(${MODAL_CONFIG.blurAmount})`,
                '-webkit-backdrop-filter': `blur(${MODAL_CONFIG.blurAmount})`
            });

        // Добавляем overlay в тело модального окна
        $modal.prepend($overlay);

        // Блокируем скролл страницы
        $('body').css('overflow', 'hidden');

        // Показываем модальное окно с анимацией
        $modal.show();
        setTimeout(() => {
            $modal.addClass('modal-active');
            $overlay.addClass('overlay-active');
        }, 10);

        // Фокусируемся на первой интерактивной кнопке
        setTimeout(() => {
            const $firstButton = $modal.find('.modal-button:first');
            if ($firstButton.length) {
                $firstButton.focus();
            }
        }, MODAL_CONFIG.animationDuration);
    }

    // Закрытие текущего модального окна
    function closeCurrentModal() {
        if (!currentModal || !isModalOpen) return;

        const $modal = currentModal;
        const $overlay = $modal.find('.modal-overlay');

        // Анимация закрытия
        $modal.removeClass('modal-active');
        $overlay.removeClass('overlay-active');

        setTimeout(() => {
            $modal.hide();
            $overlay.remove();
            $('body').css('overflow', '');
            currentModal = null;
            isModalOpen = false;
        }, MODAL_CONFIG.animationDuration);
    }

    // Программное открытие модального окна
    window.openModal = function(modalId, options = {}) {
        const config = { ...MODAL_CONFIG, ...options };
        openModal(modalId);
    };

    // Программное закрытие модального окна
    window.closeModal = function() {
        closeCurrentModal();
    };

    // Создание модального окна на лету
    window.createModal = function(options) {
        const {
            title = 'Подтверждение',
            content = 'Вы действительно хотите это сделать?',
            buttons = [
                { text: 'Да', action: 'confirm', class: 'btn-confirm' },
                { text: 'Нет', action: 'cancel', class: 'btn-cancel' }
            ],
            onConfirm = null,
            onCancel = null,
            id = 'dynamic-modal-' + Math.random().toString(36).substr(2, 9)
        } = options;

        // Создаем HTML модального окна
        const modalHtml = `
            <div id="${id}" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">${title}</h3>
                    </div>
                    <div class="modal-body">
                        <p>${content}</p>
                    </div>
                    <div class="modal-footer">
                        ${buttons.map(btn => 
                            `<button type="button" 
                                     class="modal-button ${btn.class || ''}" 
                                     data-action="${btn.action}">
                                ${btn.text}
                            </button>`
                        ).join('')}
                    </div>
                </div>
            </div>
        `;

        // Добавляем в DOM
        $('body').append(modalHtml);

        // Назначаем обработчики для кнопок
        const $modal = $('#' + id);
        $modal.on('click', '.modal-button', function() {
            const action = $(this).data('action');
            if (action === 'confirm' && onConfirm) {
                onConfirm();
            } else if (action === 'cancel' && onCancel) {
                onCancel();
            }
            closeCurrentModal();
            
            // Удаляем динамически созданное модальное окно
            setTimeout(() => {
                $modal.remove();
            }, MODAL_CONFIG.animationDuration);
        });

        return id;
    };

    // Инициализация при загрузке
    initModals();
});