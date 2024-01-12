<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-full grid place-items-center p-6">
    <div class="flex justify-center items-center w-full min-h-screen bg-white">
        <form method="POST" action="/comment">
            @csrf
            <div class="flex justify-between">
                <div class="mb-4">
                    <span class="bg-[#F3F4F6] rounded-md font-semibold cursor-pointer p-2">Write</span>
                </div>
            </div>
            <textarea placeholder="Add your comment..." name="comment"
                class="p-2 focus:outline-1 focus:outline-blue-500 font-bold border-[0.1px] resize-none h-[120px] border-[#9EA5B1] rounded-md w-[60vw]"></textarea>
            <div class="flex justify-end">
                <button
                    type="submit"
                    class="text-sm font-semibold absolute bg-[#4F46E5] w-fit text-white py-2 rounded px-3">Post</button>
            </div>
        </form>
    </div>
</body>

</html>
