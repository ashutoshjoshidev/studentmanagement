<?php

namespace App\Listeners;

use App\Events\NewUserEvent;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Queue\InteractsWithQueue;

class NewUserListner
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewUserEvent  $event
     * @return void
     */
    public function handle(NewUserEvent $event)
    {
        $admin = User::where('id', 1)->firstOrfail();
        Notification::send($admin, new NewUserNotification($event->user));
    }
}
