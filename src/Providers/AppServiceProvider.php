<?php

namespace BenjaminChen\TableActivityRecord\Providers;

use Illuminate\Support\ServiceProvider;
use InfluxDB\Client;
use InfluxDB\Database;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Database::class, function ($app) {
            $client = new Client(env('INFLUXDB_HOST'), env('INFLUXDB_PORT'));
            return $client->selectDB(env('INFLUXDB_DATABASE'));
        });
    }
}