<?php

namespace App\Models;

class Interest
{
    // Категории (ключи для навигации)
    const CATEGORIES = [
        'hobby' => 'Мое хобби',
        'books' => 'Любимые книги',
        'music' => 'Любимые исполнители',
        'games' => 'Любимые игры'
    ];

    // Данные интересов
    const INTERESTS = [
        'hobby' => [
            'description' => [
                'Играю на гитаре почти 5 лет',
                'Увлекаюсь рукоделием'
            ],
            'image' => '/assets/img/hobby.png',
            'layout' => 'image-wide'
        ],
        'books' => [
            'items' => [
                '«Марсианин» - Энди Вейер',
                '«1984» - Джордж Оруэлл',
                '«Двадцать тысяч льё под водой» - Жюль Верн'
            ],
            'image' => '/assets/img/books.png',
            'layout' => 'image-wide'
        ],
        'music' => [
            'items' => [
                'The 5:55',
                'flawed mangoes',
                'Fitz & The Tantrums'
            ],
            'image' => '/assets/img/music.png',
            'layout' => 'image-wide'
        ],
        'games' => [
            'items' => [
                'Apex Legends',
                'Rainbow Six Siege',
                'Osu!'
            ],
            'image' => '/assets/img/games.png',
            'layout' => 'image-wide last'
        ]
    ];

    public static function getCategories()
    {
        return self::CATEGORIES;
    }

    public static function getInterests()
    {
        return self::INTERESTS;
    }

    public static function getCategory($key)
    {
        return self::INTERESTS[$key] ?? null;
    }
}
