<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Карточка автора — {{ $author->full_name ?: $author->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
            line-height: 1.45;
        }
        h1 {
            font-size: 20px;
            margin: 0 0 6px;
            color: #094ca1;
        }
        .subtitle {
            color: #666;
            margin: 0 0 18px;
            font-size: 12px;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 1px solid #d9dee7;
            padding-bottom: 14px;
        }
        .photo {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #d9dee7;
            margin-bottom: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        th, td {
            border: 1px solid #d9dee7;
            padding: 8px 10px;
            vertical-align: top;
            text-align: left;
        }
        th {
            width: 32%;
            background: #f4f7fb;
            font-weight: bold;
            color: #333;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 8px;
            color: #094ca1;
        }
        .description {
            border: 1px solid #d9dee7;
            padding: 10px 12px;
            min-height: 40px;
            font-family: DejaVu Sans, sans-serif;
        }
        .description,
        .description * {
            font-family: DejaVu Sans, sans-serif !important;
        }
        .empty {
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $author->full_name ?: $author->name }}</h1>
        <p class="subtitle">Карточка автора #{{ $author->id }}</p>
        @if($photoDataUri)
            <img class="photo" src="{{ $photoDataUri }}" alt="Фото автора">
        @endif
    </div>

    <table>
        <tr>
            <th>Фамилия</th>
            <td>{{ $author->lastname ?: '—' }}</td>
        </tr>
        <tr>
            <th>Имя</th>
            <td>{{ $author->firstname ?: '—' }}</td>
        </tr>
        <tr>
            <th>Отчество</th>
            <td>{{ $author->middlename ?: '—' }}</td>
        </tr>
        <tr>
            <th>Псевдоним</th>
            <td>{{ $author->name ?: '—' }}</td>
        </tr>
        <tr>
            <th>E-mail</th>
            <td>{{ $author->email ?: '—' }}</td>
        </tr>
        <tr>
            <th>Телефон</th>
            <td>{{ $author->phone ?: '—' }}</td>
        </tr>
        <tr>
            <th>Дата создания</th>
            <td>{{ $author->created_at }}</td>
        </tr>
        <tr>
            <th>Дата обновления</th>
            <td>{{ $author->updated_at }}</td>
        </tr>
    </table>

    <div class="section-title">Описание</div>
    <div class="description">
        @if(trim(strip_tags($descriptionHtml)) !== '')
            {!! $descriptionHtml !!}
        @else
            <span class="empty">—</span>
        @endif
    </div>
</body>
</html>
