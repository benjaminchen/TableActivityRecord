<?php

require_once(__DIR__.'/TestModel.php');

use PHPUnit\Framework\TestCase;
use InfluxDB\Database;
use InfluxDB\Point;
use Illuminate\Support\Facades\Artisan;
use BenjaminChen\TableActivityRecord\Events\TableActionEvent;
use Symfony\Component\Console\Output\BufferedOutput;

class EventCommandTest extends TestCase
{
    protected static $db;

    public static function setUpBeforeClass()
    {
        self::$db = app('InfluxDB\Database');
        self::$db->query("drop measurement test_models");
        system('rm -rf '.storage_path());
    }

    public function testEventFire()
    {
        $model = new TestModel();
        $model->a = 111;
        $model->b = 222;
        $model->c = 333;
        $model->d = 444;

        Event::fire(new TableActionEvent('create', $model));

        $db = self::$db;
        $table = $model->getTable();
        $result = $db->query("select * from $table where a = '111'");
        $points = $result->getPoints();

        $this->assertEquals(1, count($points));
    }

    public function testCheckOperateRecordCommand()
    {
        Artisan::call('operateRecord:check');
        $this->assertContains('pass', Artisan::output());
    }

    public static function tearDownAfterClass()
    {
        system('rm -rf '.storage_path());
    }
}