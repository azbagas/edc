<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <style>
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -100;
        }

        .watermark img {
            width: 200px;
            opacity: 0.05;
        }
    </style>

    @yield('styles')
</head>

<body>
    <div class="watermark">
        <img src="{{ asset('storage/images/logo-purple-solid.png') }}" alt="wm">
    </div>
    @yield('content')
</body>

</html>
