<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Interest;
use App\Models\Photo;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index', [
            'title' => 'Главная страница'
        ]);
    }

    public function about()
    {
        return view('home.about');
    }
    
    public function interests()
    {
        $interestsCategories = Interest::getCategories();
        $interestsData = Interest::getInterests();
        
        return view('home.interests', [
            'categories' => $interestsCategories,
            'interests' => $interestsData
        ]);
    }

    public function photos()
    {
        $photosData = Photo::getAll();
        
        return view('home.photos', [
            'photos' => $photosData
        ]);
    }
    
    public function study()
    {
        return view('home.study');
    }

    public function history()
    {
        return view('home.history');
    }

    public function contacts(Request $request) 
    {
        $errorsHtml = '';
        $successMessage = '';
        $oldInput = [];

        if ($request->isMethod('POST')) {
            $oldInput = $request->all();
            
            $validator = Validator::make($request->all(), [
                'ФИО' => 'required|string|max:255',
                'Пол' => 'required|in:Мужской,Женский',
                'Дата рождения' => 'required|string',
                'Сообщение' => 'required|string|min:10',
                'Email' => 'required|email',
            ], [
                'ФИО.required' => 'Поле ФИО не должно быть пустым.',
                'Пол.required' => 'Выберите пол.',
                'Пол.in' => 'Некорректное значение пола.',
                'Дата рождения.required' => 'Укажите дату рождения.',
                'Сообщение.required' => 'Введите сообщение.',
                'Сообщение.min' => 'Сообщение должно содержать не менее 10 символов.',
                'Email.required' => 'Введите Email.',
                'Email.email' => 'Некорректный формат Email.',
            ]);

            if ($validator->passes()) {
                $successMessage = "Спасибо, {$oldInput['ФИО']}! Ваше сообщение успешно отправлено.";
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

        return view('home.contacts', [
            'title' => 'Обратная связь',
            'errorsHtml' => $errorsHtml,
            'successMessage' => $successMessage,
            'oldInput' => $oldInput
        ]);
    }
}
