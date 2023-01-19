<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Message;
use App\Jobs\SendUnreadMail; 

class CheckUnreadMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:unread_messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check unread messgaes in 10 minutes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $messages = Message::where('is_seen', 0)
                        ->where('created_at','>=',"now() - interval 10 minute")
                        ->with(['user', 'conversation'])
                        ->get();

        foreach($messages as $message) 
        {
            SendUnreadMail::dispatch($message);
        }

        return Command::SUCCESS;
    }
}
