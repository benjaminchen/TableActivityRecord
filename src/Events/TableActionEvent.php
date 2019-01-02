<?php

namespace BenjaminChen\TableActivityRecord\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class TableActionEvent
{
    use SerializesModels;

    public $table;
    public $action;
    public $tags;
    public $data;
    public $time;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($action, Model $model, $adminUserId = null, array $tags = [])
    {
        // note: all value of data and tags should be string type
        $this->action = $action;
        $modelArr = $model->toArray();

        if (array_key_exists('admin_user_id', $modelArr)) $tags['admin_user_id'] = strval($modelArr['admin_user_id']);
        if ($adminUserId) $tags['admin_user_id'] = strval($adminUserId);

        foreach ($model->tags as $key) {
            if (array_key_exists($key, $modelArr)) $tags[$key] = strval($model->{$key});
        }

        $this->table = $model->getTable();
        $this->tags = $tags;
        $this->tags["action"] = $action;
        $this->data = array_diff_key($modelArr, $tags);
        $this->data = array_map('strval', $this->data);
        $this->time = exec('date +%s%N');
    }
}
