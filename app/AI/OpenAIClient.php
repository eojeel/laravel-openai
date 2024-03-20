<?php

namespace App\AI;

use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Assistants\AssistantResponse;
use OpenAI\Responses\Assistants\Files\AssistantFileResponse;
use OpenAI\Responses\Threads\Messages\ThreadMessageListResponse;
use OpenAI\Responses\Threads\Messages\ThreadMessageResponse;
use OpenAI\Responses\Threads\Runs\ThreadRunResponse;
use OpenAI\Responses\Threads\ThreadResponse;

class OpenAIClient implements AIClient
{
    /**
     * @param string $assistantId
     * @return AssistantResponse
     */
    public function retrieveAssistant(string $assistantId): AssistantResponse
    {
        return OpenAI::assistants()->retrieve($assistantId);
    }

    /**
     * @param array $config
     * @return AssistantResponse
     */
    public function createAssistant(array $config): AssistantResponse
    {
        return OpenAI::assistants()->create($config);
    }

    /**
     * @param string $file
     * @param AssistantResponse $assistant
     * @return AssistantFileResponse
     */
    public function uploadFile(string $file, AssistantResponse $assistant): AssistantFileResponse
    {
        $file = OpenAI::files()->upload([
            'purpose' => 'assistants',
            'file' => fopen($file, 'rb'),
        ]);

        return OpenAI::assistants()->files()->create($assistant->id, ['file' => $file->id,]);
    }

    /**
     * @param array $params
     * @return ThreadResponse
     */
    public function createThread(array $params = []): ThreadResponse
    {
        return OpenAI::threads()->create($params);
    }

    /**
     * @param string $thread
     * @return ThreadMessageListResponse
     */
    public function messages(string $thread): ThreadMessageListResponse
    {
        return OpenAI::threads()->messages()->list($thread);
    }

    public function createMessage(string $message, string $threadId): ThreadMessageResponse
    {
        return OpenAI::threads()->messages()->create($threadId, [
            'role' => 'user',
            'content' => $message,
        ]);
    }

    public function run(string $threadId, AssistantResponse $assistant): ThreadMessageListResponse
    {
        $run = OpenAI::threads()->runs()->create($threadId, [
            'assistant_id' => $assistant->id,
        ]);

        while ($this->runStatus($run))
        {
            sleep(1);
        }
        return $this->messages($threadId);
    }

    protected function runStatus(ThreadRunResponse $run): bool
    {
        $run = OpenAI::threads()->runs()->retrieve(
            threadId: $run->threadId,
            runId: $run->id
        );

        return $run->status !== 'completed';
    }
}
