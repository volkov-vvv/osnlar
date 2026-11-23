<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Договор')</title>
    <style>
        @page {
            margin: 14mm 18mm 14mm 28mm;
        }
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
            padding: 12px 16px;
            border: 1px solid #ccc;
        }
        .signatures tr:first-child td {
            text-align: center;
            font-weight: bold;
        }
        .signatures tr:last-child td {
            text-align: left;
            
        }
        .placeholder {
            display: inline-block;
            min-width: 180px;
            border-bottom: 1px solid #222;
        }
        .appendix {
            page-break-before: always;
        }
        .appendix-header {
            text-align: right;
            margin-bottom: 18px;
            line-height: 1.4;
        }
        .appendix-form-label {
            text-align: left;
            margin: 0 0 4px;
        }
        .appendix-title {
            margin-top: 0;
        }
        .appendix-subtitle {
            text-align: center;
            margin-bottom: 16px;
        }
        .appendix-inner {
            border-collapse: collapse;
            width: 100%;
            margin: 12px 0 18px;
        }
        .appendix-inner td {
            border: 1px solid #555;
            padding: 6px 8px;
            vertical-align: top;
        }
        .appendix-signatures {
            margin-top: 24px;
        }
        .appendix-signatures-draft {
            margin-top: 12px;
        }
        .appendix-signatures-draft td {
            border: none !important;
            padding: 4px 8px;
            line-height: 1.15;
        }
        .appendix-signatures-final {
            margin-top: 12px;
        }
        table.signatures.appendix-signatures-final td,
        table.signatures.appendix-signatures-final tr:last-child td,
        table.signatures.assignment-signatures td,
        table.signatures.assignment-signatures tr:last-child td {
            border: 1px solid #ccc;
        }
        .appendix-agreed {
            margin: 16px 0 0;
            font-weight: bold;
            text-align: left;
        }
        .assignment-title {
            margin: 0 0 4px;
        }
        .assignment-subtitle {
            margin-bottom: 8px;
        }
        .consent {
            page-break-before: always;
            line-height: 1.15;
        }
        .consent p {
            margin: 0 0 4px;
            line-height: 1.15;
        }
        .consent-addressee {
            text-align: right;
            margin: 0 0 8px;
            line-height: 1.15;
        }
        .consent-title {
            margin: 6px 0 0;
        }
        .consent-subtitle {
            text-align: center;
            margin: 0 0 6px;
            font-weight: bold;
        }
        .consent-list {
            margin: 0;
        }
        .consent-sign {
            margin-top: 12px;
            border-collapse: collapse;
        }
        .consent-sign td {
            border: none;
            padding: 0;
            vertical-align: top;
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
