<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="h-full grid place-items-center p-6">
        @if(session('file'))
        <audio controls>
            <source src="{{ asset(session('file')) }}" type="audio/mp3">
          Your browser does not support the audio element.
          </audio>
        @endif
        <form method="POST" action="/roast" class="w-full lg:max-w-md lg:mx-auto mt-3">
            @csrf
            <div class="flex gap-2">
            <input class="rounded border p-2 flex-1" name="roast" type="text" placeholder="what do you want me to roast" required>
            <input type="submit" value="Roast!" class="rounded p-2 bg-gray-200 hover:bg-gray-400 hover:text-white">
            </div>
        </form>
    </body>
</html>
