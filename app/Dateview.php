<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dateview extends Model
{
    protected $table = 'dateviews';

    protected $fillable = [
        'id', 'date'
    ];

    public $timestamps = false;
}
