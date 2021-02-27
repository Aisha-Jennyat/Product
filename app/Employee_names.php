<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee_names extends Model
{
    protected $table = 'employee_names';

    protected $fillable = [
        'id_emp', 'name', 'all_production'
    ];

    // disable created_at, updated_at
    public $timestamps = false;

    public function employeeNameWithProd()
    {
        return $this->hasMany('App\production', 'id_emp', 'id_emp');
    }
}
