<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LaraVue Ticketing System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['/resources/sass/app.scss','resources/js/app.js'])
</head>
<body class="font-sans antialiased sb-nav-fixed">
    <div  id="app">
        <router-view/>

        {{-- <route-view></route-view> --}}
        {{-- <index-component></index-component> --}}
    </div>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</body>
</html>
