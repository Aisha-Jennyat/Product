<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grant extends Model
{
    protected $table = 'grants';
    public $timestamps = false;
    protected $fillable = [
        'id', 'group', 'numberid'
    ];

    public function isGrant()
    {
        return $this->hasMany('App\User', 'numberid', 'id');
    }
}
