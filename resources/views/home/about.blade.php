@extends('layouts.main')

@section('content')
<main class="about-page">
    <section class="block text-wide">
        <div class="content">
            <h3>Кто я?</h3>
            <p>Меня зовут Александр, я студент 3 курса, увлекаюсь веб-разработкой. 
                Нравится создавать красивые и удобные сайты, 
                изучать современные технологии и улучшать свои навыки в дизайне.</p>
            <p>Помимо учёбы, люблю музыку, книги и путешествия — они вдохновляют меня на новые идеи.</p>
        </div>
        <div class="image">
            <img src="/assets/img/me2.jpg" alt="Обо мне">
        </div>
    </section>

    <section class="block text-wide last">
        <div class="image">
            <img src="/assets/img/about2.png" alt="Мои цели">
        </div>
        <div class="content">
            <h3>Мои цели</h3>
            <p>В будущем хочу развиваться в IT-сфере, изучать фреймворки и создавать полезные проекты.</p>
            <p>Моя цель — не только писать код, но и проектировать интерфейсы, 
                которые будут удобны для пользователей и эстетичны визуально.</p>
        </div>
    </section>
</main>
@endsection
