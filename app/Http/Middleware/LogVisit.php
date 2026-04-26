<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;

class LogVisit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Получаем данные из запроса
        $url = $request->url();
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent() ?? '';
        
        // Имя хоста получаем из заголовка Host
        $hostName = $request->getHost();

        // Записываем информацию о посещении в БД
        Visit::create([
            'url' => $url,
            'ip_address' => $ipAddress,
            'host_name' => $hostName,
            'user_agent' => $userAgent,
        ]);

        return $next($request);
    }
}
