<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use App\models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $errorsHtml = '';
        $resultHtml = '';
        $oldInput = [];

        // Получаем все активные вопросы с ответами из БД
        $questions = Question::getActiveQuestionsWithAnswers();

        if ($request->isMethod('POST')) {
            $oldInput = $request->all();

            // Динамически формируем правила валидации на основе вопросов из БД
            $rules = [
                'fio' => 'required|string|max:255',
                'user_group' => 'required|string',
            ];
            
            $customMessages = [
                'fio.required' => 'Введите ФИО.',
                'user_group.required' => 'Выберите группу.',
            ];

            foreach ($questions as $index => $question) {
                $fieldName = 'q' . ($index + 1);
                
                if ($question->isTextareaType()) {
                    // Для текстовых вопросов
                    $rules[$fieldName] = 'required|string';
                    $customMessages["{$fieldName}.required"] = "Ответьте на вопрос №" . ($index + 1) . ".";
                    $customMessages["{$fieldName}.min"] = "Ответ должен содержать не менее 10 символов.";
                } else {
                    // Для вопросов с выбором ответа
                    $validValues = [];
                    foreach ($question->answers as $answer) {
                        $validValues[] = (string)$answer->id;
                    }
                    $rules[$fieldName] = 'required|in:' . implode(',', $validValues);
                    $customMessages["{$fieldName}.required"] = "Выберите ответ на вопрос №" . ($index + 1) . ".";
                    $customMessages["{$fieldName}.in"] = "Выбран недопустимый вариант ответа.";
                }
            }

            $validator = Validator::make($request->all(), $rules, $customMessages);

            if ($validator->passes()) {
                // Проверка ответов
                $score = 0;
                $total = count($questions);

                foreach ($questions as $index => $question) {
                    $fieldName = 'q' . ($index + 1);
                    
                    if ($question->isTextareaType()) {
                        // Текстовый вопрос - засчитываем если длина > 10
                        if (!empty($request[$fieldName]) && mb_strlen($request[$fieldName]) > 10) {
                            $score++;
                        }
                    } else {
                        // Вопрос с выбором ответа - проверяем на правильность
                        $selectedAnswerId = $request[$fieldName];
                        $correctAnswer = Answer::getCorrectByQuestionId($question->id);
                        
                        if ($correctAnswer && $selectedAnswerId == $correctAnswer->id) {
                            $score++;
                        }
                    }
                }

                $isCorrect = ($score >= ceil($total * 2 / 3)) ? 'passed' : 'failed';
                $isCorrectText = ($score >= ceil($total * 2 / 3)) ? 'Верно' : 'Неверно';

                // Сохраняем результат в БД
                $testResult = new TestResult();
                $testResult->fio = $request->input('fio');
                $testResult->user_group = $request->input('user_group');
                
                // Сохраняем ответы в формате JSON
                $answersData = [];
                foreach ($questions as $index => $question) {
                    $fieldName = 'q' . ($index + 1);
                    $answersData[$fieldName] = $request->input($fieldName);
                }
                $testResult->answers = json_encode($answersData, JSON_UNESCAPED_UNICODE);
                
                $testResult->score = $score;
                $testResult->total_questions = $total;
                $testResult->is_correct = $isCorrect;
                $testResult->created_at = date('Y-m-d H:i:s');
                $testResult->save();

                $resultHtml = "<div class='alert alert-success'>
                    <h3>Результат теста</h3>
                    <p>Ваш счет: <strong>{$score}</strong> из {$total}</p>
                    <p>Результат: <strong>{$isCorrectText}</strong></p>
                </div>";

                $oldInput = [];
            } else {
                $errors = $validator->errors()->all();
                $errorsHtml = '<div class="validation-errors" style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin-bottom: 20px;">';
                $errorsHtml .= '<h4 style="margin-top:0;">Обнаружены ошибки:</h4><ul style="margin-bottom:0; padding-left: 20px;">';
                foreach ($errors as $error) {
                    $errorsHtml .= "<li>{$error}</li>";
                }
                $errorsHtml .= '</ul></div>';
            }
        }

        // Получаем все результаты тестов из БД
        $results = TestResult::findAll();
        
        // Сортируем по убыванию даты
        usort($results, function($a, $b) {
            $dateA = strtotime($a->created_at ?? '0000-00-00 00:00:00');
            $dateB = strtotime($b->created_at ?? '0000-00-00 00:00:00');
            return $dateB - $dateA;
        });

        return view('test.index', [
            'title' => 'Тест по БЖД',
            'errorsHtml' => $errorsHtml,
            'resultHtml' => $resultHtml,
            'oldInput' => $oldInput,
            'results' => $results,
            'questions' => $questions
        ]);
    }
}
