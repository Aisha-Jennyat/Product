<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $table = 'productions';

    protected $fillable = [
        'id_emp' , 'period' , 'group_name', 'production', 'created_at', 'updated_at'
    ];

    public $timestamps = false;

    public function prodWithEmployeeName()
    {
        return $this->belongsTo('App\Employee_names', 'id_emp', 'id_emp');
    }
}
