<?php

namespace App\AI;

use OpenAI\Responses\Assistants\AssistantResponse;
use OpenAI\Responses\Assistants\Files\AssistantFileResponse;
use OpenAI\Responses\Threads\Messages\ThreadMessageListResponse;
use OpenAI\Responses\Threads\Messages\ThreadMessageResponse;
use OpenAI\Responses\Threads\ThreadResponse;

interface AIClient
{
    /**
     * @param string $assistantId
     * @return AssistantResponse
     */
    public function retrieveAssistant(string $assistantId): AssistantResponse;

    /**
     * @param array $config
     * @return AssistantResponse
     */
    public function createAssistant(array $config): AssistantResponse;

    /**
     * @param string $file
     * @param AssistantResponse $assistant
     * @return AssistantFileResponse
     */
    public function uploadFile(string $file, AssistantResponse $assistant): AssistantFileResponse;

    /**
     * @param array $params
     * @return ThreadResponse
     */
    public function createThread(array $params = []): ThreadResponse;

    /**
     * @param string $thread
     * @return ThreadMessageListResponse
     */
    public function messages(string $thread): ThreadMessageListResponse;

    public function createMessage(string $message, string $threadId): ThreadMessageResponse;

    public function run(string $threadId, AssistantResponse $assistant): ThreadMessageListResponse;
}
