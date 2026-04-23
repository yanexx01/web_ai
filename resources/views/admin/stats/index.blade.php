@extends('admin.layouts.app')

@section('content')
<div class="admin-card">
    <h2 style="margin-top: 0;">📊 Статистика посещений сайта</h2>

    @if($visits->isEmpty())
        <p style="color: #7f8c8d;">Статистика посещений пока отсутствует.</p>
    @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Дата и время посещения</th>
                    <th>URL страницы</th>
                    <th>IP-адрес</th>
                    <th>Имя хоста</th>
                    <th>Браузер</th>
                </tr>
            </thead>
            <tbody>
                @foreach($visits as $visit)
                <tr>
                    <td>{{ $visit->id }}</td>
                    <td>{{ $visit->visited_at->format('d.m.Y H:i:s') }}</td>
                    <td style="max-width: 300px; word-break: break-all;">{{ $visit->url }}</td>
                    <td>{{ $visit->ip_address }}</td>
                    <td>{{ $visit->host_name ?? '-' }}</td>
                    <td style="max-width: 250px; word-break: break-all;">{{ $visit->user_agent }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($totalPages > 1)
        <div style="margin-top: 20px; display: flex; gap: 10px; align-items: center;">
            @if($currentPage > 1)
                <a href="?page={{ $currentPage - 1 }}" class="admin-btn" style="padding: 8px 15px;">← Назад</a>
            @endif

            <span>Страница {{ $currentPage }} из {{ $totalPages }} (всего записей: {{ $totalItems }})</span>

            @if($currentPage < $totalPages)
                <a href="?page={{ $currentPage + 1 }}" class="admin-btn" style="padding: 8px 15px;">Вперед →</a>
            @endif
        </div>
        @endif
    @endif
</div>
@endsection
