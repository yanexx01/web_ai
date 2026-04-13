<?php

namespace App\Models;

class Photo
{
    private const PATHS = [
        "/assets/img/meme5.jpg",
        "/assets/img/meme2.jpg",
        "/assets/img/meme3.jpg",
        "/assets/img/meme6.jpg",
        "/assets/img/5.webp",
        "/assets/img/6.jpg",
        "/assets/img/7.jpg",
        "/assets/img/8.jpg",
        "/assets/img/9.jpg",
        "/assets/img/10.webp",
        "/assets/img/11.jpg",
        "/assets/img/12.jpg",
        "/assets/img/13.jpg",
        "/assets/img/14.jpg",
        "/assets/img/15.jpg"
    ];

    private const TITLES = [
        "Профессиональный скриншот",
        "Контент в ВК",
        "Сбербанк",
        "Песочек",
        "Бойцовский клуб",
        "Операция «Спасение Нео» и другие приключения Шурика",
        "Райан Гослинг",
        "Майлз Моралес",
        "Apex Legends",
        "Rainbow Six Siege",
        "osu!",
        "Titanfall 2",
        "Акустическая гитара",
        "Классическая гитара",
        "Электрогитара"
    ];

    public static function getAll()
    {
        $photos = [];

        foreach (self::PATHS as $index => $path) {
            $photos[] = [
                'src' => $path,
                'title' => self::TITLES[$index] ?? 'NoName',
                'index' => $index
            ];
        }

        return $photos;
    }
}
