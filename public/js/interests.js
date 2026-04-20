$(function() {
    // Функция создания списка
    function createList(items) {
        const $ul = $("<ul>");
        items.forEach(itemText => {
            $ul.append($("<li>").text(itemText));
        });
        return $ul;
    }

    // Проходимся по всем контейнерам списков
    $('.list-container').each(function() {
        const $container = $(this);
        // Получаем ID контейнера (например, "books-list")
        const containerId = $container.attr('id'); 
        
        // Извлекаем ключ категории (убираем "-list")
        const categoryKey = containerId.replace('-list', '');
        
        // Берем данные из глобального объекта, который мы создали в PHP
        if (window.interestsData && window.interestsData[categoryKey]) {
            const items = window.interestsData[categoryKey].items;
            
            if (items && items.length > 0) {
                $container.append(createList(items));
            }
        }
    });
});