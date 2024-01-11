<?php

namespace App\AI;

use OpenAI\Laravel\Facades\OpenAI;

class Assistant
{
    protected array $messages = [];

    public function __construct(array $messages = [])
    {
        $this->messages = $messages;
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

    protected function addMessage(string $message, string $role = 'user'): void
    {
        $this->messages[] = [
            'role' => $role,
            'content' => $message,
        ];
    }

    /**
     * set the system mesage for the chat.
     */
    public function systemMessage(string $message): void
    {
        $this->addMessage($message, 'system');
    }

    /**
     * send the openAI request.
     */
    public function send(string $message, bool $speech = false): string
    {
        $this->addMessage($message);

        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => $this->messages,
        ])->choices[0]->message->content;

        if ($response) {
            $this->addMessage($response, 'assistant');
        }

        return $speech ? $this->speech($response) : $response;
    }

    private function speech(string $message): string
    {
        $mp3 = OpenAI::audio()->speech([
            'model' => 'tts-1',
            'input' => $message,
            'voice' => 'alloy',
        ]);

        return $mp3;
    }

    /**
     * add a new reply.
     */
    public function reply(string $message): string
    {
        return $this->send($message);
    }

    public function visualize(string $description, array $options = []): string
    {
        $this->addMessage($description);

        $description = collect($this->messages)->where('role', 'user')->pluck('content')->implode(' ');

        $options = array_merge([
            'prompt' => $description,
            'model' => 'dall-e-3',
        ], $options);

        $url = OpenAI::images()->create($options)->data[0]->url;

        $this->addMessage($url, 'assistant');

        return $url;
    }
}
