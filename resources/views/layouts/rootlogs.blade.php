<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    @yield('style-header')
    @yield('script-header')
    @yield('title')
</head>
<body>

    @yield('body')

    @yield('style-footer')
    @yield('script-footer')

</body>
</html>
