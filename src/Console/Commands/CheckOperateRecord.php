<?php

namespace BenjaminChen\TableActivityRecord\Console\Commands;

use Log;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use function GuzzleHttp\json_decode;

class CheckOperateRecord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'operateRecord:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Operate Record';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $db = app('InfluxDB\Database');
        $today = date("Y-m-d");
        $handle = fopen(\base_path("storage/logs/operate/operate-$today.log"), "r");

        $this->warn('check start');

        while (($line = fgets($handle))) {
            $spilt = preg_match('/\{.*\}/', $line, $m);
            $data = \json_decode($m[0], true);
            $table = $data['table'];
            $time = $data['time'];
            $queryArr = [];

            foreach ($data['tags'] as $key => $val) {
                $queryArr[] = "$key = '$val'";
            }

            $queryArr[] = "time = $time";
            $selectString = "[table = $table, ".implode(', ', $queryArr)."]";

            $this->line("<fg=blue>$selectString check</>");

            $result = $db->getQueryBuilder()
                        ->select('*')
                        ->from($table)
                        ->where($queryArr)
                        ->getResultSet()
                        ->getPoints();

            $count = count($result);

            if ($count == 0) {
                $this->error("can't finde $selectString record");
                continue;
            }

            if ($count > 1) {
                $this->error("$selectString record more than 1");
                continue;
            }

            $splitTime = str_split($time, 10);

            // note: date timezone set for influxdb
            date_default_timezone_set("utc");

            $timeFormat = date("Y-m-d\Th:i:s", $splitTime[0]).".$splitTime[1]Z";
            if (count(array_diff($data['tags']+$data['data']+['time' => $timeFormat], $result[0])) !== 0) {
                $this->error("$selectString data didn't match");
                continue;
            }

            $this->info("$selectString pass");
        }

        $this->warn('check finish');
    }
}