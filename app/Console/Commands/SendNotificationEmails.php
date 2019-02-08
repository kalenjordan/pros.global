<?php

namespace App\Console\Commands;

use App\Date;
use App\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Log;
use Illuminate\Console\Command;

class SendNotificationEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send {--limit=} {--dry=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notifications for unread emails';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function _dryRun()
    {
        return $this->option('dry') !== null ? (int)$this->option('dry') : 1;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dryRunMessage = $this->_dryRun() ? "Dry run" : "SENDING";

        // todo maybe control whether these are sent out via an upvote on a tag on my
        // profile - that way I can easily disable them from my phone without having
        // to go into .env
        $limit = $this->option('limit') ? $this->option('limit') : 10;
        $this->info("Running command - with limit of $limit ($dryRunMessage)");
        Log::info("Running command - with limit of $limit");


        $notifications = DatabaseNotification::whereNull('read_at')
            ->whereNull('emailed_at')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        foreach ($notifications as $notification) {
            $this->_handleNotification($notification);
        }
        return;
    }

    /**
     * @param $notification DatabaseNotification
     */
    protected function _handleNotification($notification) {
        $text = $notification->data['text'];
        $notifiedUser = User::find($notification->notifiable_id);
        $to = $notifiedUser->name . " ($notifiedUser->email)";
        $this->info("Notification to $to: " . $text);

        if (! $this->_dryRun()) {
            $this->_send($notifiedUser, $notification);
        }
    }

    /**
     * @param $user
     * @param $notification DatabaseNotification
     */
    protected function _send($user, $notification) {
        $notification->emailed_at = Date::now();
        $notification->save();
    }
}
