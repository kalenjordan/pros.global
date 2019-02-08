<?php

namespace App\Console\Commands;

use App\Mail\UnreadNotifications;
use Log;
use Mail;

use App\Date;
use App\User;

use Illuminate\Notifications\DatabaseNotification;
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

    protected function _limit()
    {
        return $this->option('limit') ? $this->option('limit') : 1;
    }

    protected $usersWithNotificationsToEmail = 0;

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
        $limit = $this->_limit();
        $this->info("Running command - with limit of $limit ($dryRunMessage)");
        Log::info("Running command - with limit of $limit");

        $users = User::where('id', '>', 0);
        foreach ($users->get() as $user) {
            $this->_handleUser($user);
        }
        return;
    }

    /**
     * @param $user User
     */
    protected function _handleUser($user) {
        $count = $user->notificationsToEmail()->count();
        $this->info("User $user->name has $count unread notifications to email");
        if ($count) {
            $this->usersWithNotificationsToEmail++;
        }

        if (! $this->_dryRun() && $count) {
            $this->_send($user);
        }
        if ($this->usersWithNotificationsToEmail >= $this->_limit()) {
            exit;
        }
    }

    /**
     * @param $user User
     */
    protected function _send($user) {
        $isDummyEmail = env('DUMMY_EMAIL_PATTERN') ? strpos($user->email, env('DUMMY_EMAIL_PATTERN')) : false;
        if ($isDummyEmail) {
            $this->info("Dummy email, skipping");
            return;
        } else {
            $this->info("Sending notification to $user->name ($user->email)");
        }

        Mail::to($user->email)->send(new UnreadNotifications($user));
        foreach ($user->notificationsToEmail()->get() as $notification) {
            /** @var DatabaseNotification $notification */
            $notification->emailed_at = Date::now();
            $notification->save();
        }
    }
}
