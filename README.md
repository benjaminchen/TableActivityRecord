# Lumen Table Activity Record

## Requirements

- PHP >= 5.5.9
- lumen >= 5.7
- influxdb >= 1.7

## Installation

Installation via Composer:
```
composer require benjamin-chen/table-activity-record
```

## Configuration

### Lumen 5.7

Add the Service Provider in ```bootstrap/app.php```:
```
$app->register(BenjaminChen\TableActivityRecord\ServiceProvider::class);
```

Add env setting in ```.env```:
```
INFLUXDB_HOST=your-influx-db-host
INFLUXDB_PORT=8086
INFLUXDB_DATABASE=your-influx-db-database
```

## Usage

Define model tags in model file:
```
class TestModel extends Model
{
    ...........

    public $tags = [
        'a', 'b', 'c'
    ];
}
```

Fire event after model executing create, update and delete methods
```
use BenjaminChen\TableActivityRecord\Events\TableActionEvent;

Event::fire(new TableActionEvent('create', $model));
```

Fire event will log your event data in ```storage/logs/operate/operate-yyyy-mm-dd.log``` and store the event record at influx-db.

You can use command ```php artisan operateRecord:check``` to check local log is the same with influx-db record

## License

This package is open-source software and licensed under the MIT License.