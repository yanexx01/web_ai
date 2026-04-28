@extends('layouts.main')

@section('content')
<div class="auth-container">
    <h1>Вход в систему</h1>

    @if ($errors->any())
        <div class="error-messages">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf
        <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">

        <div class="form-group">
            <label for="login">Логин</label>
            <input type="text" id="login" name="login" value="{{ old('login') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group checkbox-group">
            <label>
                <input type="checkbox" name="remember"> Запомнить меня
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Войти</button>
        </div>

        <p class="auth-link">
            Нет аккаунта? <a href="{{ route('register') }}">Регистрация</a>
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

.form-group input[type="text"],
.form-group input[type="password"],
.form-group input[type="email"] {
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

.checkbox-group label {
    display: flex;
    align-items: center;
    font-weight: normal;
    cursor: pointer;
}

.checkbox-group input[type="checkbox"] {
    margin-right: 10px;
    width: auto;
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

.success-message {
    background: #efe;
    border: 1px solid #cfc;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 20px;
    color: #2a7;
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
</style>
@endsection
