$(function() {
    let currentPhotosData = [];

    $(document).on('click', '.photo-item img', function() {
        let index = $(this).data('index');

        if (index === undefined) {
            const $parent = $(this).closest('.photo-item');
            index = $parent.index();
        }

        openModal(index);
    });

    function openModal(index) {
        const domPhotos = [];

        $('.photo-item').each(function(i) {
            const $img = $(this).find('img');
            const $caption = $(this).find('.photo-caption');
            
            domPhotos.push({
                src: $img.attr('src'),
                title: $caption.text().trim()
            });
        });

        if (domPhotos.length === 0) {
            console.error("Фотографии не найдены в галерее");
            return;
        }

        currentPhotosData = domPhotos;

        let $modal = $('#photoModal');

        if (!$modal.length) {
            createModal();
            $modal = $('#photoModal');
        } else {

            if ($modal.find('.modal-content').length === 0) {
                $modal.empty();
                createModalContentInside($modal);
            }
        }

        updateModalContent(index, currentPhotosData);

        $modal.fadeIn(300);
        $('body').css('overflow', 'hidden');
    }

    function createModal() {
        const $modal = $('<div>')
            .attr('id', 'photoModal')
            .addClass('photo-modal')
            .hide()
            .appendTo('body');
        
        createModalContentInside($modal);
    }

    function createModalContentInside($container) {
        const $modalOverlay = $('<div>')
            .addClass('modal-overlay')
            .on('click', closeModal)
            .appendTo($container);

        const $modalContent = $('<div>')
            .addClass('modal-content')
            .appendTo($container);

        const $closeBtn = $('<button>')
            .addClass('modal-close')
            .html('&times;')
            .on('click', function(e) {
                e.stopPropagation();
                closeModal();
            })
            .appendTo($modalContent);

        const $imageContainer = $('<div>')
            .addClass('modal-image-container')
            .appendTo($modalContent);

        const $modalImage = $('<img>')
            .addClass('modal-image')
            .appendTo($imageContainer);

        const $navPanel = $('<div>')
            .addClass('modal-nav-panel')
            .appendTo($modalContent);

        const $prevBtn = $('<button>')
            .addClass('nav-btn prev-btn')
            .html('&#10094;')
            .on('click', function(e) {
                e.stopPropagation();
                navigate(-1);
            })
            .appendTo($navPanel);

        const $photoInfo = $('<div>')
            .addClass('photo-info')
            .appendTo($navPanel);

        const $photoCounter = $('<div>')
            .addClass('photo-counter')
            .appendTo($photoInfo);

        const $photoTitle = $('<div>')
            .addClass('photo-title')
            .appendTo($photoInfo);

        const $nextBtn = $('<button>')
            .addClass('nav-btn next-btn')
            .html('&#10095;')
            .on('click', function(e) {
                e.stopPropagation();
                navigate(1);
            })
            .appendTo($navPanel);

        // Обработчик клавиш клавиатуры
        $(document).on('keydown.modal', function(e) {
            if (!$container.is(':visible')) return;
            
            switch(e.key) {
                case 'Escape':
                    closeModal();
                    break;
                case 'ArrowLeft':
                    navigate(-1);
                    break;
                case 'ArrowRight':
                    navigate(1);
                    break;
            }
        });
    }

    function updateModalContent(index, dataArr) {
        const $modal = $('#photoModal');
        const $modalImage = $modal.find('.modal-image');
        // ИСПРАВЛЕНО: убран пробел в селекторе
        const $photoCounter = $modal.find('.photo-counter');
        const $photoTitle = $modal.find('.photo-title');

        if (!dataArr[index]) return;

        $modalImage
            .attr('src', dataArr[index].src)
            .attr('alt', dataArr[index].title)
            .data('current-index', index);
            
        $photoCounter.text(`Фото ${index + 1} из ${dataArr.length}`);
        $photoTitle.text(dataArr[index].title);
        
        updateNavButtons(index, dataArr.length);
    }

    function updateNavButtons(index, total) {
        const $prevBtn = $('.prev-btn');
        const $nextBtn = $('.next-btn');

        $prevBtn.prop('disabled', index === 0);
        $nextBtn.prop('disabled', index === total - 1);
    }

    function navigate(direction) {
        const $modalImage = $('.modal-image');
        const currentIndex = parseInt($modalImage.data('current-index'));

        let newIndex = currentIndex + direction;

        if (newIndex < 0) newIndex = 0;
        if (newIndex >= currentPhotosData.length) newIndex = currentPhotosData.length - 1;

        if (newIndex !== currentIndex) {
            $modalImage.fadeOut(200, function() {
                updateModalContent(newIndex, currentPhotosData);
                $modalImage.fadeIn(200);
            });
        }
    }

    function closeModal() {
        const $modal = $('#photoModal');
        $modal.fadeOut(300, function() {
            $('body').css('overflow', ''); 
        });

        $(document).off('keydown.modal');
    }

    $(document).on('click', '.modal-overlay', closeModal);
});