<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $table = 'productions';

    protected $fillable = [
        'id_emp' ,'group_name', 'period'  ,'pro1' ,'pro2','pro3','pro4','pro5','pro6','pro7','pro8','pro9','pro10', 'daily_production','created_at', 'updated_at'];

    public $timestamps = false;

    public function prodWithEmployeeName()
    {
        return $this->belongsTo('App\Employee_names', 'id_emp', 'id_emp');
    }
}
