@extends('layouts.main')

@section('content')
<section class="main-section">
    <div class="main-photo">
        <img src="/assets/img/me1.jpg" class="photo">
    </div>

    <div class="main-content">
        <h3>Александров Александр Сергеевич</h3>
        <p>ПИ/б-23-2-о</p>
    </div>
    
    <div class="main-table">
        <table>
            <tr>
                <th>№</th>
                <th>Тема</th>
                <th>Оценка</th>
            </tr>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Исследование возможностей языка разметки гипертекстов HTML и каскадных таблиц стилей CSS</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Исследование возможностей программирования на стороне клиента. Основы языка JavaScript</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Исследование объектной модели документа (DOM) и системы событий JavaScript</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Исследование возможностей библиотеки jQuery</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>SCSS</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Vue.js - формы</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>Vue.js – маршрутизация и управление состоянием</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
@endsection
