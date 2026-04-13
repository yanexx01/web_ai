@extends('layouts.main')

@section('content')
<main class="interests-page">
@foreach ($categories as $key => $title)
@php
    $item = $interests[$key];
    $layout = $item['layout'] ?? 'content-first';
    $imageSrc = $item['image'] ?? '';
    
    $textData = $item['description'] ?? ($item['items'] ?? []);
    
    $showImageFirst = ($layout === 'image-first'); 
    $isImageWide = (strpos($layout, 'image-wide') !== false);
@endphp

<section class="block {{ $isImageWide ? 'image-wide' : 'text-wide' }} {{ strpos($layout, 'last') !== false ? 'last' : '' }}" id="{{ $key }}">
    
    @if ($isImageWide)
        <div class="image">
            <img src="{{ $imageSrc }}" alt="{{ $title }}">
        </div>
        <div class="content">
            <h3>{{ $title }}</h3>
            
            @if (!empty($textData))
                @foreach ($textData as $line)
                    <p>{{ $line }}</p>
                @endforeach
            @endif

            @if (!empty($item['items']))
                <div id="{{ $key }}-list" class="list-container"></div>
            @endif
        </div>
    @else
        <div class="content">
            <h3>{{ $title }}</h3>
            
            @if (!empty($textData))
                @foreach ($textData as $line)
                    <p>{{ $line }}</p>
                @endforeach
            @endif

            @if (!empty($item['items']))
                <div id="{{ $key }}-list" class="list-container"></div>
            @endif
        </div>
        <div class="image">
            <img src="{{ $imageSrc }}" alt="{{ $title }}">
        </div>
    @endif
</section>
@endforeach
</main>

<a href="#top" id="backToTop" title="Наверх">↑</a>

<script>
const interestsDataFromPhp = {!! json_encode($interests) !!};

$(function() {
    $('.list-container').each(function() {
        const $container = $(this);
        const key = $container.attr('id').replace('-list', '');
        const items = interestsDataFromPhp[key]?.items || [];
        
        if (items.length > 0) {
            const $ul = $('<ul>');
            items.forEach(text => {
                $ul.append($('<li>').text(text));
            });
            $container.append($ul);
        }
    });
});
</script>
@endsection
