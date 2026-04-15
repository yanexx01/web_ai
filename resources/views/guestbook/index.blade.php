@extends('layouts.main')

@section('content')
<div class="guestbook-page">
    <h1>Гостевая книга</h1>
    
    {{-- Форма добавления отзыва --}}
    <div class="guestbook-form">
        <h2>Оставить отзыв</h2>
        <form action="/guestbook" method="POST">
            @csrf
            <div class="form-group">
                <label for="lastname">Фамилия *</label>
                <input type="text" name="lastname" id="lastname" required value="{{ old('lastname') }}">
            </div>
            
            <div class="form-group">
                <label for="firstname">Имя *</label>
                <input type="text" name="firstname" id="firstname" required value="{{ old('firstname') }}">
            </div>
            
            <div class="form-group">
                <label for="middlename">Отчество</label>
                <input type="text" name="middlename" id="middlename" value="{{ old('middlename') }}">
            </div>
            
            <div class="form-group">
                <label for="email">E-mail *</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}">
            </div>
            
            <div class="form-group">
                <label for="message">Текст отзыва *</label>
                <textarea name="message" id="message" rows="5" required>{{ old('message') }}</textarea>
            </div>
            
            <button type="submit">Отправить</button>
        </form>
        
        @if($errors->any())
            <div class="error-messages">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    
    {{-- Таблица сообщений --}}
    <div class="guestbook-messages">
        <h2>Сообщения пользователей</h2>
        @if(count($messages) > 0)
            <table class="guestbook-table">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>ФИО</th>
                        <th>E-mail</th>
                        <th>Сообщение</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $msg)
                        <tr>
                            <td>{{ $msg->created_at }}</td>
                            <td>{{ $msg->lastname }} {{ $msg->firstname }} {{ $msg->middlename }}</td>
                            <td>{{ $msg->email }}</td>
                            <td>{{ $msg->message }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Сообщений пока нет. Будьте первым!</p>
        @endif
    </div>
</div>
@endsection
