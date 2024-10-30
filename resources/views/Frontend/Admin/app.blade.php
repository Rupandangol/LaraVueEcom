<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="https://seeklogo.com/images/L/L-logo-2BD4B04DA4-seeklogo.com.png" />
    <title>Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <div id="app"></div>
    </div>
</body>

</html>
