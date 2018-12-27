<?php

namespace BenjaminChen\TableActivityRecord\Listeners;

use BenjaminChen\TableActivityRecord\Events\TableActionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use InfluxDB\Database;
use InfluxDB\Point;

class TableActionListener
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
     * @param  TableActionEvent  $event
     * @return void
     */
    public function handle(TableActionEvent $event)
    {
        \Log::channel('operate')->info(json_encode($event));
        $db = app('InfluxDB\Database');
        $result = $db->writePoints([
            new Point(
                $event->table,
                null,
                $event->tags,
                $event->data,
                $event->time
            )
        ]);
    }
}
