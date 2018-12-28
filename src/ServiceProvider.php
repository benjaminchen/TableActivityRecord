<?php

namespace BenjaminChen\TableActivityRecord;

use Illuminate\Support\ServiceProvider as BaseProvider;
use InfluxDB\Client;
use InfluxDB\Database;

class ServiceProvider extends BaseProvider
{
    protected $commands = [
        'BenjaminChen\TableActivityRecord\Console\Commands\CheckOperateRecord',
    ];

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {}

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(Providers\AppServiceProvider::class);
        $this->app->register(Providers\EventServiceProvider::class);

        $this->app->singleton(Database::class, function ($app) {
            $client = new Client(env('INFLUXDB_HOST'), env('INFLUXDB_PORT'));
            return $client->selectDB(env('INFLUXDB_DATABASE'));
        });

        $this->commands($this->commands);
    }
}