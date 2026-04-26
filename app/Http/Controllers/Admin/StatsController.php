<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    /**
     * Количество записей на странице (константа)
     */
    private const PER_PAGE = 20;

    /**
     * Отображение страницы статистики посещений
     */
    public function index(Request $request)
    {
        // Получаем номер страницы
        $page = $request->input('page', 1);

        // Получаем общее количество записей
        $totalItems = Visit::count();

        // Рассчитываем общее количество страниц
        $totalPages = $totalItems > 0 ? ceil($totalItems / self::PER_PAGE) : 1;

        // Ограничиваем номер страницы допустимым диапазоном
        $page = max(1, min($page, $totalPages));

        // Получаем записи о посещениях с пагинацией и сортировкой по убыванию даты
        $visits = Visit::orderBy('visited_at', 'desc')
            ->offset(($page - 1) * self::PER_PAGE)
            ->limit(self::PER_PAGE)
            ->get();

        return view('admin.stats.index', [
            'pageTitle' => 'Статистика посещений',
            'pageName' => 'admin-stats',
            'visits' => $visits,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems
        ]);
    }
}
