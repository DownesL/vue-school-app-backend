<?php

namespace App\Console\Commands;

use App\Mail\MessageReminder;
use App\Models\Message;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AutoReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:autoreminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically sends a reminder';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::get();
        foreach ($users as $user) {
            $all = Message::whereHas('group.users', function ($q) use ($user) {
                $q->where('id', $user->id);
            })
                ->orderByDesc('created_at')
                ->get();
            $read = $user->messages;
            $unread = $all->diff($read);
            Mail::to($user)->send(new MessageReminder($unread));
        }
        return Command::SUCCESS;
    }
}
