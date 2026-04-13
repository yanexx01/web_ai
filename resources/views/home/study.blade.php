@extends('layouts.main')

@section('content')
<main class="study-page">
    <section class="block">
        <div class="content">
            <h3>Севастопольский государственный университет</h3>
            <p>Высшая технологическая школа «Севастопольский приборостроительный институт»</p>
            <p>Факультет информационных технологий</p>
            <p>Кафедра «Прикладная математика и информатика»</p>
        </div>
    </section>
    
    <section class="study-table">
        <h2 style="text-align:center; margin-bottom:20px;">Общий вид перечня дисциплин</h2>
        <table>
            <tr>
                <th rowspan="3">№</th>
                <th rowspan="3">Дисциплина</th>
                <th colspan="12">Часов в неделю (Лекции, ЛР, ПЗ)</th>
            </tr>
            <tr>
                <th colspan="6">1 курс</th>
                <th colspan="6">2 курс</th>
            </tr>
            <tr>
                <th colspan="3">1 сем</th>
                <th colspan="3">2 сем</th>
                <th colspan="3">3 сем</th>
                <th colspan="3">4 сем</th>
            </tr>

            <tr><td>1</td><td>Экология</td><td></td><td></td><td></td><td>2</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td>2</td><td>Высшая математика</td><td>3</td><td>0</td><td>3</td><td>3</td><td>0</td><td>3</td><td>2</td><td>0</td><td>2</td><td>1</td><td>0</td><td>1</td></tr>
            <tr><td>3</td><td>Русский язык и культура речи</td><td>1</td><td>0</td><td>2</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td>4</td><td>Основы дискретной математики</td><td></td><td></td><td></td><td>2</td><td>0</td><td>3</td><td>0</td><td>0</td><td>2</td><td></td><td></td><td></td></tr>
            <tr><td>5</td><td>Основы программирования и алгоритмические языки</td><td>3</td><td>2</td><td>0</td><td>3</td><td>0</td><td>3</td><td>1</td><td>0</td><td>1</td><td></td><td></td><td></td></tr>
            <tr><td>6</td><td>Основы экологии</td><td></td><td></td><td></td><td></td><td></td><td></td><td>1</td><td>0</td><td>0</td><td></td><td></td><td></td></tr>
            <tr><td>7</td><td>Теория вероятностей и математическая статистика</td><td></td><td></td><td></td><td></td><td></td><td></td><td>2</td><td>0</td><td>2</td><td></td><td></td><td></td></tr>
            <tr><td>8</td><td>Физика</td><td>2</td><td>2</td><td>0</td><td>2</td><td>2</td><td>0</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td>9</td><td>Основы электротехники и электроники</td><td></td><td></td><td></td><td></td><td></td><td></td><td>2</td><td>1</td><td>1</td><td></td><td></td><td></td></tr>
            <tr><td>10</td><td>Численные методы в информатике</td><td></td><td></td><td></td><td></td><td></td><td></td><td>2</td><td>0</td><td>0</td><td>2</td><td>0</td><td>1</td></tr>
            <tr><td>11</td><td>Методы исследования операций</td><td></td><td></td><td></td><td></td><td></td><td></td><td>1</td><td>1</td><td>0</td><td>1</td><td>1</td><td>1</td></tr>
        </table>
    </section>
    <section class="block">
        <a href="/test">Ссылка на тест</a>
    </section>
</main>
@endsection
