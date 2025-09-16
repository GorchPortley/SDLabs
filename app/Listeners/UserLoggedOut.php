<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Cookie;

class UserLoggedOut
{
    protected $flarumAuth;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        Cookie::queue('flarum_remember', ".", 1, "/forum");
        Cookie::queue('flarum_session', ".", 1, "/forum");
    }
}
