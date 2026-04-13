@extends('layouts.main')

@section('content')
<main>
    <section class="main-content">
        <h2>История просмотра</h2>

        <h3>История текущего сеанса</h3>
        <div class="main-table">
            <table id="sessionTable">
                <thead>
                    <tr><th>Страница</th><th>Просмотров</th></tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <h3>История за всё время</h3>
        <div class="main-table">
            <table id="allTimeTable">
                <thead>
                    <tr><th>Страница</th><th>Просмотров</th></tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </section>
</main>

<script src="/assets/js/tracking.js"></script>
<script>
    trackPageView("История просмотра");

    function renderTable(tableId, data) {
        const tbody = document.querySelector(`#${tableId} tbody`);
        tbody.innerHTML = '';

        if (!data || Object.keys(data).length === 0) {
            const tr = document.createElement('tr');
            tr.innerHTML = `<td colspan="2">Нет данных</td>`;
            tbody.appendChild(tr);
            return;
        }

        for (const [page, count] of Object.entries(data)) {
            const tr = document.createElement('tr');
            tr.innerHTML = `<td>${page}</td><td>${count}</td>`;
            tbody.appendChild(tr);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const sessionData = JSON.parse(localStorage.getItem('sessionHistory')) || {};
        renderTable('sessionTable', sessionData);

        const allTimeData = JSON.parse(getCookie('allTimeHistory') || '{}');
        renderTable('allTimeTable', allTimeData);
    });
</script>
@endsection
