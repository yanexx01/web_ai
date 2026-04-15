@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>{{ $pageTitle }}</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('errors') && is_array(session('errors')) && count(session('errors')) > 0)
        <div class="alert alert-warning">
            <h5>Ошибки при загрузке:</h5>
            <ul>
                @foreach (session('errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            Загрузка CSV файла с сообщениями блога
        </div>
        <div class="card-body">
            <p class="text-muted">
                Формат CSV файла: <code>title,message,author,created_at</code><br>
                Пример: <code>"тема 1","сообщение 1","Vasiliy","2019-01-01 14:00"</code>
            </p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('blog.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="csv_file" class="form-label">Выберите CSV файл</label>
                    <input type="file" 
                           class="form-control @error('csv_file') is-invalid @enderror" 
                           id="csv_file" 
                           name="csv_file" 
                           accept=".csv,.txt"
                           required>
                    @error('csv_file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Загрузить записи
                </button>
                <a href="{{ route('blog.index') }}" class="btn btn-secondary">
                    Назад к блогу
                </a>
            </form>
        </div>
    </div>

    <div class="mt-4">
        <h5>Пример содержимого CSV файла:</h5>
        <pre class="bg-light p-3 border rounded"><code>"тема 1","сообщение 1","Vasiliy","2019-01-01 14:00"
"тема 2","сообщение 2","Anna","2019-01-02 15:30"
"тема 3","сообщение 3","Ivan","2019-01-03 10:15"</code></pre>
    </div>
</div>
@endsection
