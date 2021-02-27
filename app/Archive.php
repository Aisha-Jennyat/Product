<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $table = 'archives';

    protected $fillable = [
        'id', 'date', 'namefile'
    ];

    public $timestamps = false;
}
