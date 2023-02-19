<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $title ?? 'Report' }}</title>
    <style>
        table {
            border-collapse: collapse;
            color: black;
            width: 100%;
            font-family: Calibri;
            page-break-inside: auto;
        }
    </style>
    {{ $styles ?? '' }}
</head>

<body>
    {{ $slot }}
</body>

</html>
