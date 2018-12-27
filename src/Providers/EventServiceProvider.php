<?php

namespace BenjaminChen\TableActivityRecord\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'BenjaminChen\TableActivityRecord\Events\TableActionEvent' => [
            'BenjaminChen\TableActivityRecord\Listeners\TableActionListener',
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}