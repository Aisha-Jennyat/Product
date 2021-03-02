<?php

namespace App\Http\Controllers;

use App\Archive;
use App\Employee_names;
use App\GenerateFile\Excel;
use App\production;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function callstatistics($datestart)
    {
        $listFile = Archive::query()->select('date', 'namefile');

//        // كائن يقوم بإعطاء الوقت
//        $carbon = new Carbon();
//        $datenow = $carbon->toDateString();

        // جلب الصف المضاف اليوم فقط
        $data = production::query()
            ->select(['productions.id_emp', 'productions.production', 'productions.updated_at','employee_names.name', 'employee_names.all_production'])
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

        $prodinfo = production::query()->selectRaw('id_emp, production,group_name,period, date(updated_at) as date')
            ->whereRaw('updated_at >= ? and updated_at < ? + INTERVAL 1 DAY',
                [$dateDateString, $dateDateString])->get();

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
        $emp = production::query()->select('productions.id_emp','employee_names.name','productions.group_name','productions.period', 'employee_names.all_production')
            ->selectRaw('GROUP_CONCAT((productions.production)) as production')
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
