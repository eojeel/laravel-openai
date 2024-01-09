<?php

namespace App\AI;

use Illuminate\Support\Facades\Http;


class Chat
{
    protected array $messages = [];

     public function __invoke()
     {
         $this->messages = [
             [
                 'role' => 'system',
                 'content' => 'You are a poetic assistant, skilled in explaining complex programming concepts with creative flair.'
             ]
         ];
     }

     /**
      * set the message array.
      *
      * @return void
      */
    public function messages(): array
    {
        return $this->messages;
    }

    /**
     * send the openAI request.
     *
     * @param string $message
     * @return string
     */
    public function send(string $message) : string
    {
        $this->messages[] = [
            'role' => 'user',
            'content' => $message
        ];

        $response = Http::withToken(config('services.openai.secret'))->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => $this->messages
        ])->json('choices.0.message.content');


        if($response) {
            $this->messages[] = [
                'role' => 'user',
                'content' => $response
            ];
        }

        return $response;
    }

    /**
     * add a new reply.
     *
     * @param string $message
     * @return string
     */
    public function reply(string $message) : string
    {
        return $this->send($message);
    }
}
