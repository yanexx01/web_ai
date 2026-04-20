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

        {{-- Вопросы из БД --}}
        @foreach($questions as $index => $question)
        <div class="form-group">
            <p><strong>Вопрос {{ $index + 1 }}:</strong> {{ $question->question_text }}</p>
            
            @if($question->isTextareaType())
                {{-- Текстовый вопрос --}}
                <textarea name="q{{ $index + 1 }}" rows="3">{{ old('q' . ($index + 1), $oldInput['q' . ($index + 1)] ?? '') }}</textarea>
            @else
                {{-- Вопрос с выбором ответа --}}
                <div class="radio-group">
                    @foreach($question->answers as $answer)
                    <label>
                        <input type="radio" 
                               name="q{{ $index + 1 }}" 
                               value="{{ $answer->id }}" 
                               {{ (isset($oldInput['q' . ($index + 1)]) && $oldInput['q' . ($index + 1)] == $answer->id) ? 'checked' : '' }}>
                        {{ $answer->answer_text }}
                    </label>
                    @endforeach
                </div>
            @endif
        </div>
        @endforeach

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
                        @php
                            $answersData = is_string($result->answers) ? json_decode($result->answers, true) : ($result->answers ?? []);
                            if (!is_array($answersData)) {
                                $answersData = [];
                            }
                        @endphp
                        @foreach($answersData as $key => $answerValue)
                            @php
                                $questionIndex = (int)str_replace('q', '', $key) - 1;
                                $displayValue = $answerValue;

                                // Если это числовой ID ответа, пробуем найти текст ответа
                                if (is_numeric($answerValue) && isset($questions[$questionIndex])) {
                                    foreach ($questions[$questionIndex]->answers as $answer) {
                                        if ($answer->id == $answerValue) {
                                            // Извлекаем текст ответа без префикса "А)", "Б)" и т.д.
                                            $answerText = preg_replace('/^[А-Я]\)\s*/', '', $answer->answer_text);
                                            $displayValue = $answerText;
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            Q{{ str_replace('q', '', $key) }}: {{ $displayValue }}<br>
                        @endforeach
                    </small>
                </td>
                <td>{{ $result->score ?? 0 }}/{{ $result->total_questions ?? 0 }}</td>
                <td>
                    @if($result->score == 0)
                        <span style="color: red; font-weight: bold;">Неуд</span>
                    @elseif($result->score == 1)
                        <span style="color: yellow; font-weight: bold;">Удовл</span>
                    @elseif($result->score == 2)
                        <span style="color: green; font-weight: bold;">Хор</span>
                    @else
                        <span style="color: green; font-weight: bold;">Отл</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
