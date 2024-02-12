<?php

use App\AI\Assistant;
use App\AI\AssistantHelper;
use Illuminate\Support\Facades\Route;
use OpenAI\Laravel\Facades\OpenAI;

Route::get('/', function () {

    $chat = new Assistant(['role' => 'system', 'content' => 'Hello, I am your poetic assistant, skilled in explaining complex programming concepts with creative flair.']);

    $poem = $chat->send('Compose a poem that explains the concept of recursion in programming.');

    return view('welcome', [
        'poem' => $poem,
    ]);
});

Route::get('/roast', function () {
    return view('roast');
});

Route::post('/roast', function () {
    $validated = request()->validate([
        'roast' => 'required|min:2|max:255|string',
    ]);

    $mp3 = (new Assistant())->send(
        message: "Please roast {$validated['roast']} in a very sarcasting tone",
        speech: true
    );

    $file = '/roasts/'.md5($mp3).'.mp3';

    file_put_contents(public_path($file), $mp3);

    return redirect('/roast')->with([
        'flash' => 'Roast generated successfully!',
        'file' => $file,
    ]);
});

Route::get('/image', function () {
    return view('image', [
        'messages' => session('messages', []),
    ]);
});

Route::post('/reset', function () {

    session()->forget('messages');

    return redirect('/image');
});

Route::post('image', function () {

    //use openai laravel and generate an image.
    $validate = request()->validate([
        'image' => 'required|min:2|string',
    ]);

    $assistant = new Assistant(session('messages', []));

    $url = $assistant->visualize($validate['image']);

    session(['messages' => $assistant->messages()]);

    return redirect('/image')->with([
        'url' => $url,
    ]);
});

Route::get('comment', function () {
    return view('comment');
});

Route::post('comment', function () {

    $validate = request()->validate([
        'comment' => 'required|min:5|string',
    ]);

    $chat = new Assistant([
        [
            'content' => 'You are a forum moderator designed to output JSON',
            'role' => 'system',
        ],
    ]);

    $response = $chat->sendJSON($validate['comment']);

    dd($response);
});

Route::get('assistant', function () {

    $assistant = new AssistantHelper(config('openai.assistants.id'));

    $messages = $assistant->createThread()
    ->write('Hello!')
    ->write('How do I parse the first paragraph?')
    ->send();

    dd($messages);

});
