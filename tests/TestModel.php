<?php

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public $tags = [
        'a', 'b', 'c'
    ];
}