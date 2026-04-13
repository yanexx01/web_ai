@extends('layouts.main')

@section('content')
<div class="contact-page">
    <h1>Свяжитесь со мной</h1>
    <p>Заполните форму ниже, чтобы отправить мне сообщение.</p>

    @if (!empty($errorsHtml))
        {!! $errorsHtml !!}
    @endif

    @if (!empty($successMessage))
        <div style="color: green; background: #e6ffe6; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ $successMessage }}
        </div>
    @endif

    <form id="contactForm" action="/contacts" method="post">
        @csrf
        
        <div class="form-group">
            <label for="fullName">Фамилия Имя Отчество *</label>
            <input type="text" id="fullName" name="ФИО" 
                   value="{{ old('ФИО', $oldInput['ФИО'] ?? '') }}" 
                   placeholder="Введите полностью"
                   data-popover="<h4>Формат ФИО</h4><p>Введите полностью через пробел...</p>">
        </div>

        <div class="form-group">
            <label>Пол *</label>
            <div class="radio-group">
                <label>
                    <input type="radio" name="Пол" value="Мужской" 
                           {{ (isset($oldInput['Пол']) && $oldInput['Пол'] === 'Мужской') ? 'checked' : '' }}> 
                    Мужской
                </label>
                <label>
                    <input type="radio" name="Пол" value="Женский"
                           {{ (isset($oldInput['Пол']) && $oldInput['Пол'] === 'Женский') ? 'checked' : '' }}> 
                    Женский
                </label>
            </div>
        </div>

        <div class="form-group">
            <label for="birthdate">Дата рождения *</label>
            <div class="date-input-wrapper">
                <input type="text" id="birthdate" name="Дата рождения" 
                       value="{{ old('Дата рождения', $oldInput['Дата рождения'] ?? '') }}" 
                       placeholder="ДД.ММ.ГГГГ" readonly
                       data-popover="<h4>Формат даты</h4><p>Используйте формат ДД.ММ.ГГГГ</p><div class='example'>Пример: 15.05.1990</div>"
                       >
                <div id="calendar" class="calendar-popup">
                    <div class="calendar-header">
                        <select id="monthSelect" class="month-select"></select>
                        <select id="yearSelect" class="year-select"></select>
                    </div>
                    
                    <div class="days-of-week">
                        <div>Пн</div>
                        <div>Вт</div>
                        <div>Ср</div>
                        <div>Чт</div>
                        <div>Пт</div>
                        <div>Сб</div>
                        <div>Вс</div>
                    </div>
                    <div id="daysGrid" class="days-grid"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="message">Сообщение *</label>
            <textarea id="message" name="Сообщение" rows="6" 
                      placeholder="Напишите ваше сообщение..."
                      data-popover="<h4>Требования к сообщению</h4><p>Сообщение должно содержать не менее 10 символов и быть информативным.</p>">{{ old('Сообщение', $oldInput['Сообщение'] ?? '') }}</textarea>
        </div>

        <div class="form-group">
            <label for="email">E-mail *</label>
            <input type="email" id="email" name="Email" 
                   value="{{ old('Email', $oldInput['Email'] ?? '') }}" 
                   placeholder="example@mail.ru"
                   data-popover="<h4>Формат email</h4><p>Должен содержать:</p><ul><li>Логин</li><li>Символ @</li><li>Доменное имя</li></ul><div class='example'>Пример: user@example.com</div>">
        </div>

        <div class="form-group">
            <label for="phone">Телефон</label>
            <input type="tel" id="phone" name="Контактный телефон" 
                   value="{{ old('Контактный телефон', $oldInput['Контактный телефон'] ?? '') }}" 
                   placeholder="+71234567890"
                   data-popover="<h4>Формат телефона</h4><p>Начинается с +7 или +3, затем 9-11 цифр</p><div class='example'>Пример: +79123456789</div>">
        </div>

        <div class="button-group">
            <input type="submit" value="Отправить" class="btn-submit">
            <input type="reset" value="Очистить форму" class="btn-reset">
        </div>
    </form>
</div>
<div id="modalOverlay" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <h3>Подтверждение отправки</h3>
        <p>Вы уверены, что хотите отправить форму?</p>
        <div class="modal-buttons">
            <button id="confirmYes" class="btn-confirm yes">Да, отправить</button>
            <button id="confirmNo" class="btn-confirm no">Нет, отмена</button>
        </div>
    </div>
</div>
@endsection
