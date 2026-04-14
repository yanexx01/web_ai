<?php

namespace App\Http\Controllers;

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

        if ($request->isMethod('POST')) {
            $oldInput = $request->all();

            $validator = Validator::make($request->all(), [
                'fio' => 'required|string|max:255',
                'group' => 'required|string',
                'q1' => 'required|string|min:10',
                'q2' => 'required|in:А,Б,В',
                'q3' => 'required|in:phys_chem,soc_econ',
            ], [
                'fio.required' => 'Введите ФИО.',
                'group.required' => 'Выберите группу.',
                'q1.required' => 'Ответьте на первый вопрос.',
                'q1.min' => 'Ответ должен содержать не менее 10 символов.',
                'q2.required' => 'Выберите ответ на второй вопрос.',
                'q3.required' => 'Выберите ответ на третий вопрос.',
            ]);

            if ($validator->passes()) {
                // Проверка ответов
                $score = 0;
                $correctAnswers = [
                    'q2' => 'Б',
                    'q3' => 'phys_chem'
                ];

                foreach ($correctAnswers as $key => $correct) {
                    if (isset($request[$key]) && $request[$key] === $correct) {
                        $score++;
                    }
                }

                // Текстовый вопрос (q1)
                if (!empty($request['q1']) && mb_strlen($request['q1']) > 10) {
                    $score++;
                }

                $total = 3;
                $isCorrect = ($score >= 2) ? 'passed' : 'failed';
                $isCorrectText = ($score >= 2) ? 'Верно' : 'Неверно';

                // Сохраняем результат в БД
                $testResult = new TestResult();
                $testResult->fio = $request->input('fio');
                $testResult->group = $request->input('group');
                $testResult->q1 = $request->input('q1');
                $testResult->q2 = $request->input('q2');
                $testResult->q3 = $request->input('q3');
                $testResult->score = $score;
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
            'results' => $results
        ]);
    }
}
