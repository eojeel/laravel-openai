<?php

namespace App\AI;

use OpenAI\Laravel\Facades\OpenAI;

class Chat
{
    protected array $messages = [];

    public function __invoke()
    {
        $this->messages = [
            [
                'role' => 'system',
                'content' => 'You are a poetic assistant, skilled in explaining complex programming concepts with creative flair.',
            ],
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
     * set the system mesage for the chat.
     *
     * @param string $message
     * @return void
     */
    public function systemMessage(string $message): void
    {
        $this->messages[] = [
            'role' => 'system',
            'content' => $message,
        ];
    }

    /**
     * send the openAI request.
     */
    public function send(string $message): string
    {
        $this->messages[] = [
            'role' => 'user',
            'content' => $message,
        ];

        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => $this->messages,
        ])->choices[0]->message->content;

        if ($response) {
            $this->messages[] = [
                'role' => 'user',
                'content' => $response,
            ];
        }

        return $response;
    }

    /**
     * add a new reply.
     */
    public function reply(string $message): string
    {
        return $this->send($message);
    }
}
