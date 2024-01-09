<?php

namespace App\Console\Commands;

use App\AI\Chat;
use Illuminate\Console\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\text;

class ChatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat {--system=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'What is your question for OpenAI?';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $chat = new Chat();

        if($system = $this->option('system')) {
            $chat->systemMessage($system);
        }

        $question = text(label:'What is your question?', required: true);

        $response = spin(fn () => $chat->send($question), 'Fetching Response...');

        info($response);

        while ($question = text('Do you want to respond?')) {
            $response = spin(fn () => $chat->send($question), 'Fetching Response...');
            info($response);
        }

        info('Conversation Over!');
    }
}
