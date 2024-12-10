<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;

class UserRegistered
{
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
    }
}
