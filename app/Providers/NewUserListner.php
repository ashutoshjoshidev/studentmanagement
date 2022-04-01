<?php

namespace App\Providers;

use App\Providers\NewUserEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
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
     * @param  \App\Providers\NewUserEvent  $event
     * @return void
     */
    public function handle(NewUserEvent $event)
    {
        //
    }
}
