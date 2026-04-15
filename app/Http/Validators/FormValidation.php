<?php

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator as LaravelValidator;

/**
 * Класс FormValidation для валидации данных форм.
 */
class FormValidation
{
    /**
     * Валидация данных CSV для импорта записей блога.
     *
     * @param array $data Массив данных для валидации
     * @return \Illuminate\Validation\Validator Экземпляр валидатора
     */
    public static function validateCsvImport(array $data)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'author' => 'nullable|string|max:255',
            'created_at' => 'required|date_format:Y-m-d H:i:s',
        ];

        return LaravelValidator::make($data, $rules);
    }

    /**
     * Валидация файла CSV.
     *
     * @param array $data Данные запроса
     * @return \Illuminate\Validation\Validator Экземпляр валидатора
     */
    public static function validateCsvFile(array $data)
    {
        $rules = [
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ];

        return LaravelValidator::make($data, $rules);
    }
}
