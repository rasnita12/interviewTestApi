<?php

namespace App\Listeners;

use App\Events\StoreLoginHistory;
use App\Models\LoginHistory;
use App\Models\UserLogin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Stevebauman\Location\Facades\Location;

class StoreLoginHistoryListener
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
    public function handle(StoreLoginHistory $event): void
    {
        $location = Location::get(app()->isProduction() ? request()->ip() : null);

        LoginHistory::create([
            'user_id' => $event->user->id,
            'city' => $location->cityName,
            'ip' => request()->ip()
        ]);

    }
}
