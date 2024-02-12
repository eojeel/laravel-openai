<?php

namespace App\AI;

use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Assistants\AssistantResponse;
use OpenAI\Responses\Threads\Messages\ThreadMessageListResponse;

class AssistantHelper
{

    protected AssistantResponse $assistant;
    protected string $threadId;

    public function __construct(string $assistantId)
    {
        $this->assistant = OpenAI::assistants()->retrieve($assistantId);
    }

    public static function create(array $config = []): static
    {
        $assistant = OpenAI::assistants()->create(array_merge_recursive([
            'name' => 'LaraParse Assistant',
            'instructions' => 'You are a helpfull programming assistant',
            'model' => 'gpt-4-1106-preview',
            'tools' => [
                [
                    'type' => 'retrieval',
                ],
            ]
        ], $config));

        return new static($assistant->id);
    }

    public function educate(string $file): static
    {
        $file = OpenAI::files()->upload([
            'purpose' => 'assistants',
            'file' => fopen($file, 'rb'),
        ]);


        OpenAI::assistants()->files()->create($this->$assistant->id, ['file' => $file->id,]);

        return $this;


    }

    public function createThread(array $params = []): static
    {
        $thread = OpenAI::threads()->create($params);

        $this->threadId = $thread->id;

        return $this;

    }

    public function messages(): ThreadMessageListResponse
    {
        return OpenAI::threads()->messages()->list($this->threadId);

    }

    public function write(string $message): static
    {

        OpenAI::threads()->messages()->create($this->threadId, [
            'role' => 'user',
            'content' => $message,
        ]);
        return $this;
    }

    public function send()
    {
        $run = OpenAI::threads()->runs()->create($this->threadId, [
            'assistant_id' => $this->assistant->id,
        ]);

        do {
            sleep(1);

            $run = OpenAI::threads()->runs()->retrieve(
                threadId: $run->threadId,
                runId: $run->id
            );
        } while ($run->status != 'completed');

        return $this->messages();
    }




}
