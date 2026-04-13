document.addEventListener('DOMContentLoaded', function () {
    // 1. Получаем элементы
    const fullName = document.getElementById('fullName');
    if (!fullName) return; // Если формы нет, выходим

    const phone = document.getElementById('phone');
    const email = document.getElementById('email');
    const message = document.getElementById('message');
    const birthdate = document.getElementById('birthdate');
    const genderInputs = document.querySelectorAll('input[name="Пол"]');
    const submitBtn = document.querySelector('.btn-submit');

    // Состояние "касания" полей для валидации
    const touched = {
        fullName: false,
        phone: false,
        email: false,
        message: false,
        birthdate: false,
        gender: false
    };

    // === ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ===

    function showError(input, messageText) {
        if (!input) return;
        input.style.borderColor = '#e74c3c';
        const existing = input.parentNode.querySelector('.error-message');
        if (existing) existing.remove();

        const err = document.createElement('div');
        err.className = 'error-message';
        err.style.color = '#e74c3c';
        err.style.fontSize = '0.85rem';
        err.style.marginTop = '5px';
        err.textContent = messageText;
        input.parentNode.appendChild(err);
    }

    function hideError(input) {
        if (!input) return;
        input.style.borderColor = '#28a745'; // Зеленый при успехе
        const existing = input.parentNode.querySelector('.error-message');
        if (existing) existing.remove();
    }

    function parseDate(str) {
        if (!str) return null;
        const parts = str.split('.');
        if (parts.length !== 3) return null;
        const day = parseInt(parts[0], 10);
        const month = parseInt(parts[1], 10) - 1;
        const year = parseInt(parts[2], 10);
        
        if (isNaN(day) || isNaN(month) || isNaN(year)) return null;
        if (month < 0 || month > 11) return null;
        
        const date = new Date(year, month, day);
        return (date.getDate() === day && date.getMonth() === month) ? date : null;
    }

    function formatDate(date) {
        return `${String(date.getDate()).padStart(2, '0')}.${String(date.getMonth() + 1).padStart(2, '0')}.${date.getFullYear()}`;
    }

    // === ВАЛИДАЦИЯ ПОЛЕЙ ===

    function isValidFullName() {
        const v = fullName.value.trim();
        return v && v.split(/\s+/).length === 3;
    }

    function isValidGender() {
        return Array.from(genderInputs).some(r => r.checked);
    }

    function isValidBirthdate() {
        if (!birthdate) return false;
        const v = birthdate.value.trim();
        if (!v) return false;
        const date = parseDate(v);
        if (!date) return false;
        
        // Проверка возраста (опционально, минимум 16 лет)
        const today = new Date();
        let age = today.getFullYear() - date.getFullYear();
        const m = today.getMonth() - date.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < date.getDate())) {
            age--;
        }
        return age >= 16; 
    }

    function isValidMessage() {
        if (!message) return false;
        return message.value.trim().length >= 10; // Минимум 10 символов как в popover
    }

    function isValidEmail() {
        if (!email) return false;
        const v = email.value.trim();
        return v && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
    }

    function isValidPhone() {
        if (!phone) return true; 
        const v = phone.value.trim();
        if (!v) return true; // Необязательное поле
        
        if (!v.startsWith('+7') && !v.startsWith('+3')) return false;
        const digits = v.substring(1);
        return /^\d+$/.test(digits) && digits.length >= 9 && digits.length <= 11;
    }

    // === ОТРИСОВКА ОШИБОК ===

    function validateField(name, isValidFn, showErrorFn) {
        if (!touched[name]) return;

        if (isValidFn()) {
            if (name === 'fullName' && fullName) hideError(fullName);
            else if (name === 'phone' && phone) hideError(phone);
            else if (name === 'email' && email) hideError(email);
            else if (name === 'message' && message) hideError(message);
            else if (name === 'birthdate' && birthdate) hideError(birthdate);
            else if (name === 'gender') {
                const radioGroup = document.querySelector('.radio-group');
                if(radioGroup) {
                    const err = radioGroup.parentNode.querySelector('.error-message');
                    if (err) err.remove();
                }
            }
        } else {
            showErrorFn();
        } 
    }

    function showFullNameError() {
        if(!fullName) return;
        showError(fullName, fullName.value.trim() ? 'ФИО должно состоять из трёх слов.' : 'Поле "ФИО" обязательно.');
    }

    function showGenderError() {
        const container = document.querySelector('.radio-group');
        if(!container) return;
        const err = container.parentNode.querySelector('.error-message');
        if (!err) {
            const el = document.createElement('div');
            el.className = 'error-message';
            el.style.color = '#e74c3c';
            el.style.fontSize = '0.85rem';
            el.style.marginTop = '8px';
            el.textContent = 'Укажите пол.';
            container.parentNode.appendChild(el);
        }
    }

    function showBirthdateError() {
        if(!birthdate) return;
        const v = birthdate.value.trim();
        if (!v) {
            showError(birthdate, 'Укажите дату рождения.');
        } else if (!parseDate(v)) { 
            showError(birthdate, 'Неверный формат. Используйте дд.мм.гггг');
        } else {
            showError(birthdate, 'Возраст должен быть не менее 16 лет.');
        }
    }

    function showMessageError() {
        if(!message) return;
        const len = message.value.trim().length;
        showError(message, len < 10 ? 'Сообщение должно быть не менее 10 символов.' : 'Сообщение обязательно.');
    }

    function showEmailError() {
        if(!email) return;
        showError(email, email.value.trim() ? 'Некорректный E-mail.' : 'Укажите E-mail.');
    }

    function showPhoneError() {
        if(!phone) return;
        const v = phone.value.trim();
        if (!v) {
            hideError(phone); 
            return;
        } 
        if (!v.startsWith('+7') && !v.startsWith('+3')) {
            showError(phone, 'Телефон должен начинаться с +7 или +3.');
        } else {
            showError(phone, 'После + должно быть 9–11 цифр.');
        }
    }

    // === ОБЩАЯ ПРОВЕРКА ФОРМЫ ===

    function validateForm() {
        const checks = [
            isValidFullName(),
            isValidGender(),
            isValidBirthdate(),
            isValidMessage(),
            isValidEmail(),
            isValidPhone()
        ];

        const allValid = checks.every(Boolean);

        if (submitBtn) {
            submitBtn.disabled = !allValid;
            submitBtn.style.opacity = allValid ? 1 : 0.6;
            submitBtn.style.cursor = allValid ? 'pointer' : 'not-allowed';
        }
    }

    function updateAndValidate(name, isValidFn, showErrorFn) {
        validateField(name, isValidFn, showErrorFn);
        validateForm();
    }

    // === КАЛЕНДАРЬ (ИСПРАВЛЕННАЯ ВЕРСИЯ НА JQUERY) ===
    // Используем $, так как у вас подключен jQuery и popover на нем
    if (typeof $ !== 'undefined') {
        $(function() {
            const $dateInput = $('#birthdate');
            const $calendar = $('#calendar');
            const $monthSelect = $('#monthSelect');
            const $yearSelect = $('#yearSelect');
            const $daysGrid = $('#daysGrid');

            if ($dateInput.length && $calendar.length) {
                const monthNames = [
                    "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь",
                    "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"
                ];

                // 1. Заполняем селекторы
                $monthSelect.empty();
                monthNames.forEach((name, index) => {
                    $monthSelect.append($('<option>', { value: index, text: name }));
                });

                $yearSelect.empty();
                const currentYear = new Date().getFullYear();
                for (let y = currentYear; y >= 1920; y--) {
                    $yearSelect.append($('<option>', { value: y, text: y }));
                }

                // 2. Функция отрисовки
                function renderCalendar() {
                    $daysGrid.empty();
                    const year = parseInt($yearSelect.val());
                    const month = parseInt($monthSelect.val());

                    let firstDay = new Date(year, month, 1).getDay();
                    firstDay = (firstDay === 0) ? 6 : firstDay - 1; // Пн=0

                    const daysInMonth = new Date(year, month + 1, 0).getDate();

                    for (let i = 0; i < firstDay; i++) {
                        $daysGrid.append($('<div>'));
                    }

                    for (let d = 1; d <= daysInMonth; d++) {
                        const $day = $('<div>').text(d).attr('data-day', d);
                        
                        // Подсветка выбранной даты
                        const inputValue = $dateInput.val();
                        if (inputValue) {
                            const parts = inputValue.split('.');
                            if (parts.length === 3) {
                                if (parseInt(parts[0]) === d && 
                                    parseInt(parts[1]) - 1 === month && 
                                    parseInt(parts[2]) === year) {
                                    $day.addClass('selected');
                                }
                            }
                        }

                        $day.on('click', function() {
                            const dayStr = d < 10 ? '0' + d : d;
                            const monthStr = (month + 1) < 10 ? '0' + (month + 1) : (month + 1);
                            $dateInput.val(`${dayStr}.${monthStr}.${year}`);
                            
                            // Триггерим событие input/change для валидации
                            $dateInput.trigger('input').trigger('change');
                            touched.birthdate = true;
                            updateAndValidate('birthdate', isValidBirthdate, showBirthdateError);
                            
                            $calendar.hide();
                        });

                        $daysGrid.append($day);
                    }
                }

                // 3. Обработчики событий
                $dateInput.on('click', function(e) {
                    e.stopPropagation();
                    const isVisible = $calendar.is(':visible');
                    
                    $('.calendar-popup').not($calendar).hide(); // Закрыть другие

                    if (!isVisible) {
                        const val = $(this).val();
                        if (val && /^\d{2}\.\d{2}\.\d{4}$/.test(val)) {
                            const parts = val.split('.');
                            const m = parseInt(parts[1]) - 1;
                            const y = parseInt(parts[2]);
                            if (m >= 0 && m <= 11) {
                                $monthSelect.val(m);
                                $yearSelect.val(y);
                            }
                        }
                        renderCalendar();
                        $calendar.show();
                    } else {
                        $calendar.hide();
                    }
                });

                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.date-input-wrapper').length) {
                        $calendar.hide();
                    }
                });

                $monthSelect.on('change', renderCalendar);
                $yearSelect.on('change', renderCalendar);
                
                $calendar.on('click', function(e) {
                    e.stopPropagation();
                });

                // Инициализация при загрузке
                renderCalendar();
            }
        });
    }

    // === НАВЕШИВАНИЕ СОБЫТИЙ НА ПОЛЯ ===

    if (fullName) {
        fullName.addEventListener('blur', () => {
            touched.fullName = true;
            updateAndValidate('fullName', isValidFullName, showFullNameError);
        });
    }

    if (phone) {
        phone.addEventListener('blur', () => {
            touched.phone = true;
            updateAndValidate('phone', isValidPhone, showPhoneError);
        });
    }

    if (email) {
        email.addEventListener('blur', () => {
            touched.email = true;
            updateAndValidate('email', isValidEmail, showEmailError);
        });
    }

    if (message) {
        message.addEventListener('blur', () => {
            touched.message = true;
            updateAndValidate('message', isValidMessage, showMessageError);
        });
    }

    if (birthdate) {
        birthdate.addEventListener('blur', () => {
            touched.birthdate = true;
            updateAndValidate('birthdate', isValidBirthdate, showBirthdateError);
        });
        // Также слушаем input, чтобы убирать красную рамку при исправлении
        birthdate.addEventListener('input', () => {
             if(touched.birthdate) updateAndValidate('birthdate', isValidBirthdate, showBirthdateError);
        });
    }

    genderInputs.forEach(input => {
        input.addEventListener('change', () => {
            touched.gender = true;
            updateAndValidate('gender', isValidGender, showGenderError);
        });
    });

    // Сброс стилей при вводе
    [fullName, phone, email, message].forEach(input => {
        if(input) {
            input.addEventListener('input', () => {
                input.style.borderColor = '';
            });
        }
    });

    // Инициализация кнопки
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.style.opacity = 0.6;
        submitBtn.style.cursor = 'not-allowed';
        validateForm();
    }
});

// jQuery часть для модального окна подтверждения
if (typeof $ !== 'undefined') {
    $(function () {
        let allowSubmit = false;
        
        $("#contactForm").on("submit", function (event) {
            // Проверяем валидность перед показом модалки
            // (валидация уже прошла через blur, но можно перепроверить)
            if (!allowSubmit) {
                event.preventDefault();
                $("#modalOverlay").fadeIn(200);
            }
        });

        $("#confirmYes").on("click", function () {
            allowSubmit = true;
            $("#modalOverlay").fadeOut(200);
            $("#contactForm").submit();
        });

        $("#confirmNo").on("click", function () {
            $("#modalOverlay").fadeOut(200);
        });
        
        // Закрытие модалки по клику вне окна
        $("#modalOverlay").on("click", function(e) {
            if ($(e.target).is('#modalOverlay')) {
                $(this).fadeOut(200);
            }
        });
    });
}