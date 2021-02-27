<?php

namespace App\Http\Controllers;

use App\Archive;
use App\Employee_names;
use App\GenerateFile\Excel;
use App\Grant;
use App\production;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // تفريغ جدول الانتاج حفظه في ملف اكسل
        $fetchfromdb = production::query()->select('updated_at')->orderByDesc('id')->limit(1)->get();

        if($fetchfromdb->count() > 0) {
            $lastdate = $fetchfromdb->first()['updated_at'];

            $olddate = new Carbon($lastdate);
            $today = new Carbon();

            if ($today->month > $olddate->month) {

                // fetch employee
                $emp = production::query()->select('productions.id_emp', 'employee_names.name', 'employee_names.all_production')
                    ->selectRaw('GROUP_CONCAT(productions.production) as production')
                    ->selectRaw('GROUP_CONCAT(DAY(productions.updated_at)) as updated')
                    ->join('employee_names', 'productions.id_emp', '=', 'employee_names.id_emp')
                    ->groupBy(['id_emp'])->get();

                // create file excel
                $nameFile = Excel::generateFileThisMonth($emp, 'files/', $today);

                Archive::query()->create([
                    'date' => $today->toDateTimeString(),
                    'namefile' => $nameFile
                ]);

                // clear
                production::truncate();

                Employee_names::query()->update(['all_production' => '0']);

            }
        }

        $id_emp = Session::get('id_emp');

        $userProd = User::query()->select('numberid', 'name')->where('numberid', $id_emp);

        $countEmp = Employee_names::query()->count('id_emp');

        if($userProd->get()->count() > 0)
            return view('home')
                ->with('userProd', $userProd->get()->first())
                ->with('count', $countEmp)
                ->with('navHome', 'active');;
    }
}
