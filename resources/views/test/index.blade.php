@extends('layouts.main')
@section('content')

<div class="test-page">
    @auth
        <h2 style="margin-bottom: 20px;">Тест по дисциплине: Безопасность жизнедеятельности</h2>

        @if (!empty($errorsHtml))
            {!! $errorsHtml !!}
        @endif

        @if (session('test_result'))
            <div class='alert alert-success'>
                <h3>Результат теста</h3>
                <p>Ваш счет: <strong>{{ session('test_result.score') }}</strong> из {{ session('test_result.total') }}</p>
                <p>Результат: <strong>{{ session('test_result.isCorrectText') }}</strong></p>
            </div>
        @endif

        <form action="" method="post">
            @csrf

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Фамилия Имя Отчество:</label>
                <input type="text" name="fio" value="{{ old('fio', $oldInput['fio'] ?? auth()->user()->name ?? '') }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Группа:</label>
                <select name="user_group" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                    <option value="">Выберите группу</option>
                    <option value="ИИ/б-25-1-о" {{ (isset($oldInput['user_group']) && $oldInput['user_group'] == 'ИИ/б-25-1-о') ? 'selected' : '' }}>ИИ/б-25-1-о</option>
                    <option value="ИИ/б-25-2-о" {{ (isset($oldInput['user_group']) && $oldInput['user_group'] == 'ИИ/б-25-2-о') ? 'selected' : '' }}>ИИ/б-25-2-о</option>
                    <option value="ПИ/б-23-1-о" {{ (isset($oldInput['user_group']) && $oldInput['user_group'] == 'ПИ/б-23-1-о') ? 'selected' : '' }}>ПИ/б-23-1-о</option>
                </select>
            </div>

            <hr>

            {{-- Вопросы из БД --}}
            @foreach($questions as $index => $question)
            <div class="form-group" style="margin-bottom: 20px; padding: 15px; border: 1px solid #eee; border-radius: 6px; background: #fafafa;">
                <p style="margin-bottom: 10px;"><strong>Вопрос {{ $index + 1 }}:</strong> {{ $question->question_text }}</p>

                @if($question->isTextareaType())
                    {{-- Текстовый вопрос --}}
                    <textarea name="q{{ $index + 1 }}" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">{{ old('q' . ($index + 1), $oldInput['q' . ($index + 1)] ?? '') }}</textarea>
                @else
                    {{-- Вопрос с выбором ответа (без подсветки валидации) --}}
                    <div class="radio-group">
                        @foreach($question->answers as $answer)
                        <label style="display: flex; align-items: center; padding: 8px 12px; margin: 4px 0; border-radius: 4px; border: 1px solid #ddd; background: #fff; cursor: pointer; transition: 0.2s;">
                            <input type="radio"
                                  name="q{{ $index + 1 }}"
                                  value="{{ $answer->id }}"
                                  {{ (isset($oldInput['q' . ($index + 1)]) && $oldInput['q' . ($index + 1)] == $answer->id) ? 'checked' : '' }}
                                  style="margin-right: 8px;">
                            {{ $answer->answer_text }}
                        </label>
                        @endforeach
                    </div>
                @endif
            </div>
            @endforeach

            <div class="button-group" style="margin-top: 20px;">
                <input type="submit" value="Завершить тест" class="btn-submit" style="padding: 12px 24px; background: #0d6efd; color: #fff; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; transition: 0.2s;">
            </div>
        </form>

        {{-- Таблица сохраненных результатов тестов --}}
        @if(!empty($results))
        <hr style="margin: 40px 0;">
        <h2 style="margin-bottom: 20px;">Сохраненные результаты тестов</h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th style="padding: 12px; border: 1px solid #dee2e6; text-align: left;">Дата</th>
                        <th style="padding: 12px; border: 1px solid #dee2e6; text-align: left;">ФИО</th>
                        <th style="padding: 12px; border: 1px solid #dee2e6; text-align: left;">Группа</th>
                        <th style="padding: 12px; border: 1px solid #dee2e6; text-align: left;">Ответы</th>
                        <th style="padding: 12px; border: 1px solid #dee2e6; text-align: center;">Счет</th>
                        <th style="padding: 12px; border: 1px solid #dee2e6; text-align: center;">Результат</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $result)
                    <tr>
                        <td style="padding: 10px; border: 1px solid #dee2e6;">{{ $result->created_at ?? '-' }}</td>
                        <td style="padding: 10px; border: 1px solid #dee2e6;">{{ $result->fio ?? '-' }}</td>
                        <td style="padding: 10px; border: 1px solid #dee2e6;">{{ $result->user_group ?? '-' }}</td>
                        <td style="padding: 10px; border: 1px solid #dee2e6;">
                            <small>
                                @php
                                    $answersData = is_string($result->answers) ? json_decode($result->answers, true) : ($result->answers ?? []);
                                    if (!is_array($answersData)) $answersData = [];
                                @endphp
                                @foreach($answersData as $key => $answerValue)
                                    @php
                                        $questionIndex = (int)str_replace('q', '', $key) - 1;
                                        $displayValue = $answerValue;
                                        $isCorrectAnswer = false;

                                        $isTextareaType = isset($questions[$questionIndex]) && $questions[$questionIndex]->isTextareaType();

                                        if ($isTextareaType && isset($questions[$questionIndex])) {
                                            $isCorrectAnswer = $questions[$questionIndex]->checkAnswerContainsKeyword($answerValue);
                                        } elseif (is_numeric($answerValue) && isset($questions[$questionIndex])) {
                                            foreach ($questions[$questionIndex]->answers as $answer) {
                                                if ($answer->id == $answerValue) {
                                                    $displayValue = preg_replace('/^[А-Я]\)\s*/', '', $answer->answer_text);
                                                    $isCorrectAnswer = ($answer->is_correct == 1);
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp
                                    <span style="{{ $isCorrectAnswer ? 'color: green; font-weight: 500;' : 'color: red; font-weight: 500;' }}">
                                        Q{{ str_replace('q', '', $key) }}: {{ $displayValue }}
                                    </span><br>
                                @endforeach
                            </small>
                        </td>
                        <td style="padding: 10px; border: 1px solid #dee2e6; text-align: center;">{{ $result->score ?? 0 }}/{{ $result->total_questions ?? 0 }}</td>
                        <td style="padding: 10px; border: 1px solid #dee2e6; text-align: center;">
                            @if($result->score == 0)
                                <span style="color: #dc3545; font-weight: bold;">Неуд</span>
                            @elseif($result->score == 1)
                                <span style="color: #fd7e14; font-weight: bold;">Удовл</span>
                            @elseif($result->score == 2)
                                <span style="color: #28a745; font-weight: bold;">Хор</span>
                            @else
                                <span style="color: #155724; font-weight: bold;">Отл</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    @else
        <div class="alert alert-warning" style="padding: 20px; background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 5px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #856404;">Доступ ограничен</h3>
            <p style="margin-bottom: 15px; color: #856404;">Для доступа к тесту по дисциплине необходимо авторизоваться.</p>
            <a href="{{ route('login') }}" style="display: inline-block; padding: 10px 20px; background-color: #0d6efd; color: white; text-decoration: none; border-radius: 4px;">Войти</a>
        </div>
    @endauth
</div>

@endsection