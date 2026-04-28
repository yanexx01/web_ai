@extends('layouts.main')

@section('content')
<div class="auth-container">
    <h1>Регистрация пользователя</h1>

    @if ($errors->any())
        <div class="error-messages">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <div class="form-group">
            <label for="name">ФИО</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="login">Логин</label>
            <input type="text" id="login" name="login" value="{{ old('login') }}" required onBlur="checkLogin()">
            <span id="login-status"></span>
        </div>

        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Подтверждение пароля</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
        </div>

        <p class="auth-link">
            Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a>
        </p>
    </form>
</div>

<style>
.auth-container {
    max-width: 500px;
    margin: 40px auto;
    padding: 30px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.auth-container h1 {
    text-align: center;
    margin-bottom: 30px;
    color: #333;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #555;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    box-sizing: border-box;
}

.form-group input:focus {
    outline: none;
    border-color: #4a90d9;
    box-shadow: 0 0 0 2px rgba(74, 144, 217, 0.2);
}

.error-messages {
    background: #fee;
    border: 1px solid #fcc;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 20px;
}

.error-messages ul {
    margin: 0;
    padding-left: 20px;
}

.error-messages li {
    color: #c00;
    margin-bottom: 5px;
}

.form-actions {
    margin-top: 25px;
}

.btn-primary {
    width: 100%;
    padding: 14px;
    background: #4a90d9;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-primary:hover {
    background: #357abd;
}

.auth-link {
    text-align: center;
    margin-top: 20px;
    color: #666;
}

.auth-link a {
    color: #4a90d9;
    text-decoration: none;
}

.auth-link a:hover {
    text-decoration: underline;
}

#login-status {
    display: block;
    margin-top: 5px;
    font-size: 14px;
    font-weight: 600;
}

#login-status.occupied {
    color: #c00;
}

#login-status.free {
    color: #0a0;
}

#login-status.checking {
    color: #999;
}
</style>

<script>
function checkLogin() {
    var loginInput = document.getElementById('login');
    var statusSpan = document.getElementById('login-status');
    var loginValue = loginInput.value.trim();

    if (loginValue === '') {
        statusSpan.className = '';
        statusSpan.textContent = '';
        return;
    }

    statusSpan.className = 'checking';
    statusSpan.textContent = 'Проверка...';

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '{{ route('check.login') }}', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var response = xhr.responseText;
                if (response === 'occupied') {
                    statusSpan.className = 'occupied';
                    statusSpan.textContent = 'Пользователь с таким логином уже существует';
                } else if (response === 'free') {
                    statusSpan.className = 'free';
                    statusSpan.textContent = 'Логин свободен';
                } else {
                    statusSpan.className = '';
                    statusSpan.textContent = '';
                }
            } else {
                statusSpan.className = '';
                statusSpan.textContent = 'Ошибка проверки';
            }
        }
    };

    xhr.send('login=' + encodeURIComponent(loginValue));
}
</script>

@endsection
