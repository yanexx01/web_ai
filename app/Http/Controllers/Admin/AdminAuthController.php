<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('admin.auth.login', [
            'pageTitle' => 'Вход в админ-панель',
            'pageName' => 'admin-login'
        ]);
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Проверяем, является ли пользователь администратором
            if (Auth::user()->is_admin) {
                return redirect()->intended(route('admin.blog.index'));
            }

            // Если пользователь не админ - разлогиниваем
            Auth::logout();
            return back()->withErrors([
                'email' => 'У вас нет прав администратора.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'Неверный email или пароль.',
        ])->onlyInput('email');
    }

    /**
     * Logout admin.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
