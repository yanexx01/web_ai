<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Показать форму регистрации.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Обработать регистрацию пользователя.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'login' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'login' => $request->login,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Регистрация прошла успешно!');
    }

    /**
     * Показать форму входа.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Обработать вход пользователя.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Находим пользователя по логину
        $user = User::where('login', $credentials['login'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended('/')->with('success', 'Вы успешно вошли!');
        }

        return back()->withErrors([
            'login' => 'Неверный логин или пароль.',
        ])->onlyInput('login');
    }

    /**
     * Выход пользователя.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Вы вышли из системы.');
    }
}
