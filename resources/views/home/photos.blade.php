@extends('layouts.main')

@section('content')
<main>
    <div class="gallery-page">
        <h2>Фотоальбом</h2>
        
        <div id="galleryContainer" class="gallery-grid">
            @if (!empty($photos))
                @foreach ($photos as $photo)
                    <div class="photo-item">
                        <img 
                            src="{{ $photo['src'] }}" 
                            alt="{{ $photo['title'] }}" 
                            data-index="{{ $photo['index'] }}"
                        >
                        <div class="photo-caption">
                            {{ $photo['title'] }}
                        </div>
                    </div>
                @endforeach
            @else
                <p>Фотографий пока нет.</p>
            @endif
        </div>
    </div>

    <div id="photoModal" class="photo-modal" style="display:none;">
    </div>
</main>
@endsection
