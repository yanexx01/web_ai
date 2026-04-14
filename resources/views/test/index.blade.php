@extends('layouts.main')

@section('content')
<div class="test-page">
    <h1>Тест по дисциплине: Безопасность жизнедеятельности</h1>
    
    @if (!empty($errorsHtml))
        {!! $errorsHtml !!}
    @endif

    @if (!empty($resultHtml))
        {!! $resultHtml !!}
    @endif

    <form action="" method="post">
        @csrf
        
        <div class="form-group">
            <label>Фамилия Имя Отчество:</label>
            <input type="text" name="fio" value="{{ old('fio', $oldInput['fio'] ?? '') }}">
        </div>

        <div class="form-group">
            <label>Группа:</label>
            <select name="user_group">
                <option value="">Выберите группу</option>
                <option value="ИИ/б-25-1-о" {{ (isset($oldInput['user_group']) && $oldInput['user_group'] == 'ИИ/б-25-1-о') ? 'selected' : '' }}>ИИ/б-25-1-о</option>
                <option value="ИИ/б-25-2-о" {{ (isset($oldInput['user_group']) && $oldInput['user_group'] == 'ИИ/б-25-2-о') ? 'selected' : '' }}>ИИ/б-25-2-о</option>
                <option value="ПИ/б-23-1-о" {{ (isset($oldInput['user_group']) && $oldInput['user_group'] == 'ПИ/б-23-1-о') ? 'selected' : '' }}>ПИ/б-23-1-о</option>
                
            </select>
        </div>

        <hr>

        <div class="form-group">
            <p><strong>Вопрос 1:</strong> Опишите основные принципы обеспечения безопасности жизнедеятельности.</p>
            <textarea name="q1" rows="3">{{ old('q1', $oldInput['q1'] ?? '') }}</textarea>
        </div>

        <div class="form-group">
            <p><strong>Вопрос 2:</strong> Что является основной целью БЖД?</p>
            <div class="radio-group">
                <label><input type="radio" name="q2" value="А" {{ (isset($oldInput['q2']) && $oldInput['q2'] == 'А') ? 'checked' : '' }}> А) Обеспечение комфортных условий труда</label>
                <label><input type="radio" name="q2" value="Б" {{ (isset($oldInput['q2']) && $oldInput['q2'] == 'Б') ? 'checked' : '' }}> Б) Защита человека от опасностей</label>
                <label><input type="radio" name="q2" value="В" {{ (isset($oldInput['q2']) && $oldInput['q2'] == 'В') ? 'checked' : '' }}> В) Повышение производительности</label>
            </div>
        </div>

        <div class="form-group">
            <p><strong>Вопрос 3:</strong> Какие факторы относятся к опасным?</p>
            <div class="radio-group">
                <label><input type="radio" name="q3" value="phys_chem" {{ (isset($oldInput['q3']) && $oldInput['q3'] == 'phys_chem') ? 'checked' : '' }}> Физические и химические</label>
                <label><input type="radio" name="q3" value="soc_econ" {{ (isset($oldInput['q3']) && $oldInput['q3'] == 'soc_econ') ? 'checked' : '' }}> Социальные и экономические</label>
            </div>
        </div>

        <div class="button-group">
            <input type="submit" value="Завершить тест" class="btn-submit">
        </div>
    </form>

    {{-- Таблица сохраненных результатов тестов --}}
    @if(!empty($results))
    <hr style="margin: 40px 0;">
    <h2>Сохраненные результаты тестов</h2>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th>Дата</th>
                <th>ФИО</th>
                <th>Группа</th>
                <th>Ответы</th>
                <th>Счет</th>
                <th>Результат</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
            <tr>
                <td>{{ $result->created_at ?? '-' }}</td>
                <td>{{ $result->fio ?? '-' }}</td>
                <td>{{ $result->user_group ?? '-' }}</td>
                <td>
                    <small>
                        Q1: {{ mb_substr($result->q1 ?? '', 0, 30) }}{{ mb_strlen($result->q1 ?? '') > 30 ? '...' : '' }}<br>
                        Q2: {{ $result->q2 ?? '-' }}<br>
                        Q3: {{ $result->q3 ?? '-' }}
                    </small>
                </td>
                <td>{{ $result->score ?? 0 }}/3</td>
                <td>
                    @if($result->is_correct === 'passed')
                        <span style="color: green; font-weight: bold;">Верно</span>
                    @else
                        <span style="color: red; font-weight: bold;">Неверно</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
