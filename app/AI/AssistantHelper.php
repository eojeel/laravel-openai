<?php

namespace App\AI;

use OpenAI\Responses\Assistants\AssistantResponse;
use OpenAI\Responses\Threads\Messages\ThreadMessageListResponse;

class AssistantHelper
{
    protected AssistantResponse $assistant;
    protected string $threadId;

    protected OpenAIClient $client;

    public function __construct(string $assistantId, ?OpenAIClient $client = null)
    {
        $this->client = $client ?? new OpenAIClient();

        $this->assistant = $this->client->retireveAssistant($assistantId);
    }

    public static function create(array $config = []): static
    {
        $defaultConfig = [
            'name' => 'LaraParse Assistant',
            'instructions' => 'You are a helpfull programming assistant',
            'model' => 'gpt-4-1106-preview',
            'tools' => [
                [
                    'type' => 'retrieval',
                ],
            ]
        ];
        $assistant = (new OpenAIClient())->createAssistant(array_merge_recursive($defaultConfig, $config));

        return new static($assistant->id);
    }

    public function educate(string $file): static
    {
        $this->client->uploadFile($file, $this->assistant);

        return $this;


    }

    public function createThread(array $params = []): static
    {
        $thread = $this->client->createThread($params);

        $this->threadId = $thread->id;

        return $this;

    }

    public function messages(): ThreadMessageListResponse
    {
        return $this->client->messages($this->threadId);

    }

    public function write(string $message): static
    {
        $this->client->createMessage($message, $this->threadId);
        return $this;
    }

    public function send(): ThreadMessageListResponse
    {
        return $this->client->run($this->threadId, $this->assistant);
    }

}
