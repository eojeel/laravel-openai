<?php

use App\AI\Chat;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $chat = new Chat();

    $poem = $chat->send('Compose a poem that explains the concept of recursion in programming.');

    return view('welcome', [
        'poem' => $poem,
    ]);
});
