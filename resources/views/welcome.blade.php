<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    @livewireStyles
</head>

<body>
    <h1 class="text-6xl font-bold underline">
        Hello world!
    </h1>

    @vite('resources/js/app.js')
    @livewireScripts

</body>

</html>
