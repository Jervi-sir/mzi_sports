<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/settings.css">
    <link rel="icon" href="pics/favicon.svg">

    @yield('style-header')
    @yield('script-header')
    @yield('title')

    @cloudinaryJS

</head>
<body>
    <div id="body" class="body">
        @include('layouts.settings')
        @yield('content')
        @include('layouts.menu')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>

    @yield('vuejs')
    @yield('style-footer')

    <link rel="stylesheet" href="../css/responsivity.css">
</body>
</html>
