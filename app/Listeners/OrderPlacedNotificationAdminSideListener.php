<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\OrderPlacedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class OrderPlacedNotificationAdminSideListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = User::findOrFail(Auth::user()->id);
        $user->notify(new OrderPlacedNotification($event->order->id));
    }
}
