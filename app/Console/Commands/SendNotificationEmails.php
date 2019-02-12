<?php

namespace App\Console\Commands;

use App\Mail\UnreadNotifications;
use App\Notifications\Notification;
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
    protected $signature = 'notifications:send {--limit=} {--live}';

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
        return $this->option('live') === false ? 1 : 0;
    }

    protected function _limit()
    {
        return $this->option('limit') ? $this->option('limit') : 5;
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

        $limit = $this->_limit();

        $notifications = Notification::toBeEmailed()
            ->groupBy('notifiable_id')
            ->select([
                'notifiable_id'
            ])
            ->leftJoin("users", function($join) {
                /** @var $join \Illuminate\Database\Query\JoinClause */
                $join->on("users.id", '=', 'notifications.notifiable_id');
            });

        if (env('DUMMY_EMAIL_PATTERN')) {
            $notifications->where('users.email', 'NOT LIKE', '%' . env('DUMMY_EMAIL_PATTERN'));
        }

        $notifications = $notifications->get();
        $count = $notifications->count();
        $this->info("Sending $count notifications with limit of $limit ($dryRunMessage)");

        foreach ($notifications as $notification) {
            /** @var DatabaseNotification $notification */
            $userToNotify = User::find($notification->notifiable_id);
            $this->_handleUser($userToNotify);
        }
        return;
    }

    /**
     * @param $user User
     */
    protected function _handleUser($user) {
        $count =  $user->notificationsToEmail()->count();
        $this->info("User $user->name <$user->email> ($user->id) has $count unread notifications to email");
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
