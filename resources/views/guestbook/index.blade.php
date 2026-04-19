@extends('layouts.main')

@section('content')

<div class="contact-page">
    <h1>Гостевая книга</h1>
    <p>Оставьте свой отзыв или сообщение!</p>

    {{-- Форма добавления отзыва --}}
    <div class="guestbook-form-wrapper" style="background: #f9f9f9; padding: 30px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 40px;">
        <h2 style="text-align: center; margin-bottom: 20px; color: #333;">Оставить отзыв</h2>
        
        <form action="{{ route('guestbook.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="lastname">Фамилия *</label>
                <input type="text" name="lastname" id="lastname" required value="{{ old('lastname') }}" placeholder="Иванов">
            </div>

            <div class="form-group">
                <label for="firstname">Имя *</label>
                <input type="text" name="firstname" id="firstname" required value="{{ old('firstname') }}" placeholder="Иван">
            </div>

            <div class="form-group">
                <label for="middlename">Отчество</label>
                <input type="text" name="middlename" id="middlename" value="{{ old('middlename') }}" placeholder="Иванович">
            </div>

            <div class="form-group">
                <label for="email">E-mail *</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="example@mail.ru">
            </div>

            <div class="form-group">
                <label for="message">Текст отзыва *</label>
                <textarea name="message" id="message" rows="5" required placeholder="Ваше сообщение...">{{ old('message') }}</textarea>
            </div>

            <div class="button-group">
                <button type="submit" class="btn-submit">Отправить</button>
                <button type="reset" class="btn-reset">Очистить</button>
            </div>
        </form>

        @if($errors->any())
            <div class="error-messages" style="margin-top: 20px; padding: 15px; background: #ffe6e6; border: 1px solid #ffcccc; border-radius: 6px; color: #cc0000;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    {{-- Таблица сообщений --}}
    <h2 style="text-align: center; margin-bottom: 20px; color: #333;">Сообщения пользователей</h2>

    @if(count($messages) > 0)
        <div class="main-table" style="width: 100%; max-width: 100%;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%;">Дата</th>
                        <th style="width: 25%;">ФИО</th>
                        <th style="width: 25%;">E-mail</th>
                        <th>Сообщение</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $msg)
                        <tr>
                            <td style="text-align: center; vertical-align: top;">
                                {{ \Carbon\Carbon::parse($msg->created_at)->format('d.m.Y H:i') }}
                            </td>
                            <td style="vertical-align: top;">
                                {{ $msg->lastname }} {{ $msg->firstname }} 
                                @if($msg->middlename) {{ $msg->middlename }} @endif
                            </td>
                            <td style="vertical-align: top; word-break: break-all;">
                                {{ $msg->email }}
                            </td>
                            <td style="vertical-align: top;">
                                {{ $msg->message }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align: center; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <p style="font-size: 1.2rem; color: #777;">Сообщений пока нет. Будьте первым!</p>
        </div>
    @endif
</div>

@endsection