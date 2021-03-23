<?php

namespace App\Http\Controllers;

use App\Archive;
use App\Employee_names;
use App\GenerateFile\Excel;
use App\production;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function callstatistics($datestart)
    {
        $listFile = Archive::query()->select('date', 'namefile');

        // جلب الصف المضاف اليوم فقط
        $data = production::query()
            ->select(['productions.id_emp', 'productions.daily_production', 'productions.group_name','productions.period','productions.updated_at','employee_names.name', 'employee_names.all_production'])
            ->join('employee_names', 'productions.id_emp', '=', 'employee_names.id_emp')
            ->whereBetween('updated_at',[
                $datestart . ' 00:00:00',
                $datestart . ' 23:59:59'
            ]);


        if($data !== null) {

            $prodArr = $data->get()->count();

            if($prodArr > 0)
            {
                return view('statistics')
                    ->with('prodEmployees', $data->get())
                    ->with('listFile', $listFile->get())
                    ->with('navStatistics', 'active')
                    ->with('isProduct', true)
                    ->with('datestart', $datestart);
            }else
            {
                return view('statistics')
                    ->with('navStatistics', 'active')
                    ->with('listFile', $listFile->get())
                    ->with('isProduct', false)
                    ->with('datestart', $datestart);
            }
        }
        return view('statistics')
            ->with('navStatistics', 'active')
            ->with('listFile', $listFile->get())
            ->with('isProduct', false)
            ->with('datestart', $datestart);
    }

    public function deleteRow(Request $request)
    {        
        //جلب قيم السطر المحدد من الجدول من القاعدة عند الضغط على زر حذف
        $query = production::query()
        ->where('productions.id_emp'    , '=', $request->id_emp )
        ->where('productions.group_name', '=', $request->group_name )
        ->where('productions.period'    , '=', $request->period)
        ->where('productions.updated_at', '=', $request->updated_at);

        $oldProduction = $query->select('daily_production')->get()->first()->daily_production;

        $queryEmp = Employee_names::query()
            ->select('all_production')
            ->where('id_emp','=',$request->id_emp);

        $allProduction = $queryEmp->get()->first()->all_production;

        $queryEmp->update(['all_production' => $allProduction - $oldProduction]);

        if(Production::query()->select('id_emp')->count() == 1)
            Production::query()->truncate();
        else
            $query->delete();

//        return view('statistics')
//            ->with('datestart', $request->get('datestart'));

        return redirect('statistics');
    }

    public function editProduction(Request $request)
    {

            // جلب الصف المضاف اليوم فقط
            $data = production::query()
                ->select(['productions.id_emp', 'productions.daily_production', 'productions.group_name','productions.period','productions.pro1','productions.pro2','productions.pro3','productions.pro4','productions.pro5', 'productions.pro6','productions.pro7','productions.pro8','productions.pro9','productions.pro10','productions.updated_at','employee_names.name', 'employee_names.all_production'])
                ->join('employee_names', 'productions.id_emp', '=', 'employee_names.id_emp')
                ->where('productions.id_emp', '=', $request->id_emp)
                ->where('productions.group_name' , '=', $request->group_name)
                ->where('productions.period' , '=', $request->period)
                ->where('productions.updated_at','=' , $request->updated_at);

                return view('editProduction')
                    ->with('data', $data)
                    ->with('navProduction', 'active')
                    ->with('date_value' , $request->get('date_value'));

            }

    public function confirmProduction(Request $request)
    {
//        dd($request->all());

        $msg = '';
        $state ='';

        // جلب الصف المضاف اليوم فقط
        $data = production::query()
            ->select(['productions.id_emp', 'productions.daily_production','productions.pro1','productions.pro2','productions.pro3','productions.pro4','productions.pro5', 'productions.pro6','productions.pro7','productions.pro8','productions.pro9','productions.pro10','productions.updated_at', 'employee_names.all_production'])
            ->join('employee_names', 'productions.id_emp', '=', 'employee_names.id_emp')
            ->where('productions.id_emp', '=', $request->get('id_emp') )
            ->where('productions.updated_at','=' , $request->get('updated_at') );

        $oldValue = $data->select('daily_production')->get()->first()['daily_production'];

        $data->update([
            'daily_production' => $request->get('pro1') + $request->get('pro2') + $request->get('pro3') + $request->get('pro4') + $request->get('pro5') + $request->get('pro6')+ $request->get('pro7')+ $request->get('pro8')+ $request->get('pro9')+ $request->get('pro10'),
            'pro1'   => $request->get('pro1'),
            'pro2'   => $request->get('pro2'),
            'pro3'   => $request->get('pro3'),
            'pro4'   => $request->get('pro4'),
            'pro5'   => $request->get('pro5'),
            'pro6'   => $request->get('pro6'),
            'pro7'   => $request->get('pro7'),
            'pro8'   => $request->get('pro8'),
            'pro9'   => $request->get('pro9'),
            'pro10'   => $request->get('pro10'),
        ]);

        $query = Employee_names::query()->where('id_emp',$request->id_emp);
        $allValue = $query->select('all_production')->first()['all_production'];

        $query->update([
            'all_production' => $allValue - $oldValue  + $request->pro1 + $request->pro2 + $request->pro3 + $request->pro4 + $request->pro5 + $request->pro6 + $request->pro7 + $request->pro8 + $request->pro9 + $request->pro10
        ]);

        $msg = 'تم تعديل قيمة الإنتاج للموظف ' . $request->get('name') ;
        $state = 'info';


        $newdata = production::query()
            ->select(['productions.id_emp', 'productions.daily_production', 'productions.group_name','productions.period','productions.pro1','productions.pro2','productions.pro3','productions.pro4','productions.pro5','productions.pro6','productions.pro7','productions.pro8','productions.pro9','productions.pro10','productions.updated_at','employee_names.name', 'employee_names.all_production'])
            ->join('employee_names', 'productions.id_emp', '=', 'employee_names.id_emp')
            ->where('productions.id_emp', '=', $request->id_emp)
            ->where('productions.group_name' , '=', $request->group_name)
            ->where('productions.period' , '=', $request->period)
            ->where('productions.updated_at','=' , $request->updated_at);

        return view('editProduction')
            ->with('data', $newdata)
            ->with(['message'=> $msg])
            ->with('state', $state)
            ->with('navProduction', 'active')
            ->with('date_value' , $request->get('date_value'));

    }



    public function statistics()
    {
        // كائن يقوم بإعطاء الوقت
        $carbon = new Carbon();
        $datenow = $carbon->toDateString();

        return $this->callstatistics($datenow);
    }

    public function statisticsPost(Request $request)
    {
        return $this->callstatistics($request->get('datestart'));
    }

    public function downloadFileDay(Request $request)
    {
        $date = Carbon::createFromTimeString($request->get('datestart') . ' 00:00:00');
        $dateDateString = $date->toDateString();

        $prodinfo = production::query()->select('productions.id_emp','employee_names.name','productions.group_name','productions.period','productions.pro1','productions.pro2','productions.pro3','productions.pro4','productions.pro5','productions.pro6','productions.pro7','productions.pro8','productions.pro9','productions.pro10','productions.daily_production')
            ->selectRaw(' date (productions.updated_at) as date')
            ->join('employee_names','productions.id_emp','=','employee_names.id_emp')
            ->whereRaw('productions.updated_at >= ? and productions.updated_at < ? + INTERVAL 1 DAY',
                [$dateDateString, $dateDateString])->get();
//        dd($prodinfo->all());


        if($prodinfo->count() > 0) {
            $file = Excel::generateFileThisDay($prodinfo, 'date_production.xlsx');

            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'inline; filename="date_production.xlsx"'
            ];

            return response()->download($file, 'date_production.xlsx', $headers);
        }
        return redirect('statistics')->with('error', 'الملف فارغ، لايوجد بيانات لموظفي الإنتاج اليوم.');
    }

    public function downloadFileMonth(Request $request)
    {

        // fetch employee
        $emp = production::query()->select('productions.id_emp','employee_names.name', 'employee_names.all_production')
            ->selectRaw('GROUP_CONCAT((productions.daily_production)) as production')
            ->selectRaw(' GROUP_CONCAT(DAY(productions.updated_at)) as updated')
            ->join('employee_names','productions.id_emp','=','employee_names.id_emp')
            ->groupBy(['id_emp'])->get();

        // create file excel

        Excel::generateFileThisMonth($emp, 'files/excel/', null);

        $file = public_path() . "/files/excel/this_month_production.xlsx";

        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'inline; filename="this_month_production.xlsx"'
        ];

        return response()->download($file, 'this_month_production.xlsx', $headers);
    }

    public function downloadLastFile(Request $request)
    {
        if($request->datefile != null) {
            $path = public_path() . '/files/' . $request->datefile . 'date_production.xlsx';

            return response()->download($path, $request->datefile . 'date_production.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'Content-Disposition' => 'inline; filename="date_production.xlsx"'
                ]);
        }
        return redirect('statistics')->with('error', 'لايوجد ملفات انتاج شهرية حاليا.');
    }



}
