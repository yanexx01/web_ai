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
            <select name="group">
                <option value="">Выберите группу</option>
                <option value="ИИ/б-25-1-о" {{ (isset($oldInput['group']) && $oldInput['group'] == 'ИИ/б-25-1-о') ? 'selected' : '' }}>ИИ/б-25-1-о</option>
                <option value="ИИ/б-25-2-о" {{ (isset($oldInput['group']) && $oldInput['group'] == 'ИИ/б-25-2-о') ? 'selected' : '' }}>ИИ/б-25-2-о</option>
                <option value="ПИ/б-23-1-о" {{ (isset($oldInput['group']) && $oldInput['group'] == 'ПИ/б-23-1-о') ? 'selected' : '' }}>ПИ/б-23-1-о</option>
                
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
</div>
@endsection
