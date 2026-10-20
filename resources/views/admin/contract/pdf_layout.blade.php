<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Договор')</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            color: #222;
            line-height: 1.5;
        }
        body, body * {
            font-family: "Times New Roman", Times, serif !important;
        }
        h1 {
            font-size: 18px;
            margin: 0 0 18px;
            text-align: center;
            font-weight: bold;
        }
        h2 {
            font-size: 13px;
            text-align: center;
            margin: 16px 0 8px;
            font-weight: bold;
        }
        p {
            margin: 0 0 10px;
            text-align: justify;
        }
        .meta {
            margin-bottom: 18px;
        }
        .meta td {
            padding: 2px 0;
            vertical-align: top;
        }
        .signatures {
            width: 100%;
            margin-top: 36px;
            border-collapse: collapse;
        }
        .signatures td {
            width: 50%;
            vertical-align: top;
            padding-right: 16px;
        }
        .placeholder {
            display: inline-block;
            min-width: 180px;
            border-bottom: 1px solid #222;
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
