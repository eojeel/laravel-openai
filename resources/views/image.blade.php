<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="h-full grid place-items-center p-6">
        <div>
            @if(count($messages))
            <div>
                @foreach(array_chunk($messages, 2) as $message)
                <div>
                    <p class="text-center border rounded mt-5 border-gray-200">{{ $message[0]['content'] }}</p>
                    <img src="{{ ($message[1]['content']) }}" style="max-width:400px" alt="">
                </div>
                @endforeach
            </div>
            @endif
        </div>
        <form method="POST" action="/image" class="w-full lg:max-w-md lg:mx-auto mt-5">
            @csrf
            <div class="flex gap-2">
            <textarea class="rounded border border-gray-200 text-sm p-2 flex-1" name="image" type="text" placeholder="What images do you want me to generate"></textarea>
            <input type="submit" value="Generate!" class="rounded p-2 bg-gray-200 hover:bg-gray-400 hover:text-white">
            </div>
        </form>
        <form method="POST" action="/reset" class="w-full lg:max-w-md lg:mx-auto mt-5">
            @csrf
            <div class="flex gap-2">
            <input type="submit" value="Reset!" class="w-full rounded p-2 bg-gray-200 hover:bg-gray-400 hover:text-white">
            </div>
    </body>
</html>
