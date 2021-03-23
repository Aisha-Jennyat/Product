<?php

namespace App\Http\Controllers;

use App\Dateview;
use App\production;
use App\Employee_names;
use Carbon\Carbon;
use Cassandra\Date;
use Faker\Provider\DateTime;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    public function __construct()
    {
        // admin or entry
        $this->middleware('auth');
    }

    public function production()
    {
        return view('production')
            ->with('messageStart', 'يرجى البحث عن الموظف إما بالرقم الوظيفي أو بالاسم.')
            ->with('navProduction', 'active');
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'search' => 'required|string'
        ],[
            'required' => 'الرجاء ملئ الحقل قبل البحث.'
        ]);

        $dateValue = $this->getDateValue();

        if($validator->fails())
            return back()
                ->withErrors($validator)->withInput();
        else
        {
            if(filter_var($request->get('search'), FILTER_VALIDATE_INT)) {

                $data = Employee_names::query()->select('id_emp', 'name', 'all_production')->where('id_emp', '=', $request->search)->get();

                //$dateValue = $this->getDateValue();

                if ($data->first())
                    return view('production')
                        ->with('data', $data)
                        ->with('old', $request->get('search'))
                        ->with('navProduction', 'active')
                        ->with('date_value' , $dateValue);
            }else
            {
                // حذف الفراغات وجلب الأحرف فقط
                $listName = explode(" ",$request->get('search'));
                $count = count($listName);

                for ($i = 0; $i < $count;++$i)
                    if(empty($listName[$i]))
                        unset($listName[$i]);

                // reset array keeys
                $listName = array_values($listName);

                // إضافة حرف + من أجل البحث في قاعدة البيانات
                $tempListName = array_fill(0, count($listName), NULL);
                for ($i = 0; $i < count($listName);++$i)
                    $tempListName[$i] .= '+' . $listName[$i];

                // convert array to string
                $strSearch = implode(" ", $tempListName);

                $sql = "MATCH(name) AGAINST('. $strSearch .' IN BOOLEAN MODE) limit 25";

                $data = Employee_names::query()->select()->whereRaw($sql)->get();

                if ($data->first())
                    return view('production')
                        ->with('data', $data)
                        ->with('old', $request->get('search'))
                        ->with('navProduction', 'active')
                        ->with('date_value' , $dateValue);
            }
        }
        return view('production')
            ->with('messageStart', 'لايوجد موظف بهذا الاسم أو الرقم الوظيفي.')
            ->with('data', null)
            ->with('old', $request->get('search'))
            ->with('date_value' , $dateValue);
    }

    public function store(Request $request)
    {

//       dd($request->all());
        // max:4 when numeric -> number      <= 4
        // max"4 when string  -> char length <= 4
        $validator = Validator::make($request->only(['id_emp','group_name','period']),
            [
            'id_emp'     => 'required|numeric|max:4294967295',
            'group_name' => 'required',
            'period' => 'required'
            ],[
            'required' => 'الرجاء قم بملئ الحقل.',
            'numeric' => 'الرجاء أدخل قيمة رقمية فقط.',
            'max' => 'الرقم الذي أدخلته أكبر من المتوقع يرجى إدخال رقم أقل'
        ]);

        $validatorDate = Validator::make($request->only(['date_value']), [
            'date_value' => 'required|date_format:Y-m-d|before:today +1 day'
            ],[
            'date_format' => 'الرجاء قم بإدخال تاريخ صحيح.',
            'before' => 'لايمكن إدخال تاريخ بعد تاريخ اليوم.'
        ]);

        // حفظ التاريخ في قاعدة البيانات في جدول dateviews
        Dateview::query()->where('id','=','1')->update(['date' => $request->get('date_value')]);

        $dataColl = collect([['id_emp' => $request->get('id_emp'),
                              'name'   => $request->get('name'),
                              'group_name'   => $request->get('group_name'),
                              'period'   => $request->get('period'),
                              'daily_production'   => $request->get('daily_production'),
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
                              'all_production' => $request->get('all_production')]]);

        if($validator->fails())
            return view('production')
                ->with('data', $dataColl)
                ->with('old', $request->get('search'))
                ->withErrors($validator)
                ->with('navProduction', 'active')
                ->with('date_value', $request->get('date_value'));

        elseif($validatorDate->fails())
            return view('production')
                ->with('data', $dataColl)
                ->with('old', $request->get('search'))
                ->withErrors($validatorDate)
                ->with('navProduction', 'active')
                ->with('date_value', $request->get('date_value'));

        else
        {
            // كائن يقوم بإعطاء الوقت
            $carbon = new Carbon();
            $carbon->setTimezone('Asia/Damascus');

            $dateDateTime =  Carbon::createFromTimeString($request->get('date_value') . $carbon->toTimeString() );
            $dateDateString = $dateDateTime->toDateString();

            // جلب الصف المضاف اليوم فقط
            $data = production::query()
                ->where('id_emp', '=', $request->id_emp)
                ->whereBetween('updated_at',[
                    $dateDateString . ' 00:00:00',
                    $dateDateString . ' 23:59:59'
                    ])
                ->where('group_name' , '=', $request->group_name)
                ->where('period' , '=', $request->period);

            if($data !== null) {
                $msg = '';
                $state ='';

                if ($data->count() > 0) {

                    // القيمة القديمة قبل التحديث من أجل طرحها من قيمة الانتاج الكلي واضافة القيمة الجديدة
                    $oldValue = $data->select('daily_production')->get()->first()['daily_production'];

                    $data->update([
                        'id_emp' => $request->get('id_emp'),
                        'daily_production' => $request->get('pro1') + $request->get('pro2') + $request->get('pro3') + $request->get('pro4') + $request->get('pro5')  + $request->get('pro6')+ $request->get('pro7')+ $request->get('pro8')+ $request->get('pro9')+ $request->get('pro10'),
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
                        'group_name'   => $request->get('group_name'),
                        'period'   => $request->get('period')
                    ]);

                    // إضافة قيمة الإنتاج إلى الانتاج الكلي في جدول الأسماء
                    $query = Employee_names::query()->where('id_emp',$request->id_emp);
                    $allValue = $query->select('all_production')->first()['all_production'];

                    $query->update([
                        'all_production' => $allValue  + $request->daily_production
                    ]);


                    $msg = 'تم إضافة قيمة الإنتاج للموظف ' . $request->get('name') . ' ،وتساوي: ' . $request->get('daily_production');
                    $state = 'info';

                } else {

                    production::query()->create(
                            [   'id_emp' => $request->id_emp,
                                'group_name' => $request->group_name,
                                'period' => $request->period,
                                'daily_production' => $request->pro1 + $request->pro2 + $request->pro3 + $request->pro4  + $request->pro5 + $request->pro6 + $request->pro7 + $request->pro8 + $request->pro9 + $request->pro10 ,
                                'pro1' => $request->pro1,
                                'pro2' => $request->pro2,
                                'pro3' => $request->pro3,
                                'pro4' => $request->pro4,
                                'pro5' => $request->pro5,
                                'pro6'   => $request->pro6,
                                'pro7'   => $request->pro7,
                                'pro8'   => $request->pro8,
                                'pro9'   => $request->pro9,
                                'pro10'   => $request->pro10,
                                'created_at' => $dateDateTime,
                                'updated_at' => $dateDateTime]);

                    // إضافة قيمة الإنتاج إلى الانتاج الكلي في جدول الأسماء
                    $query = Employee_names::query()->where('id_emp',$request->id_emp);
                    $value = $query->select('all_production')->first()['all_production'];

                    $query->update([
                        'all_production' => $value + $request->pro1 + $request->pro2 + $request->pro3 + $request->pro4  + $request->pro5 + $request->pro6 + $request->pro7 + $request->pro8 + $request->pro9 + $request->pro10
                    ]);

                    $msg = 'تم إضافة قيمة الإنتاج للموظف ' . $request->get('name') ;
                    $state =  'info';
                }

                $dataColl = collect([['id_emp' => $request->get('id_emp'),
                    'name'   => $request->get('name'),
                    'daily_production'  => $request->get('daily_production'),
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
                    'group_name'   => $request->get('group_name'),
                    'period'   => $request->get('period'),
                    'all_production' => $query->select('all_production')->first()['all_production']]]);


                return view('production')
                    ->with('data', $dataColl)
                    ->with('old', $request->get('search'))
                    ->with(['message'=> $msg])
                    ->with('state', $state)
                    ->with('navProduction', 'active')
                    ->with('date_value' , $request->get('date_value'));

            }
        }
        return view('production')->withErrors(['خطأ، لم يتم تحديث المعلومات.'])->with('navProduction', 'active')
            ->with('date_value' , $request->get('date_value'));
    }

    private function getDateValue()
    {
        $dateValue = Dateview::query()->select('date')->where('id','=','1');

        if($dateValue->count() > 0)
            return $dateValue->first()['date'];
        else
            return today()->toDateString();
    }

    public function storeGet()
    {
        return redirect('/');
    }

    public function delete(Request $request)
    {
        Employee_names::query()->where('id_emp', '=', $request->get('id_emp'))->delete();

        return view('production')
            ->with('messageStart', 'يرجى البحث عن الموظف إما بالرقم الوظيفي أو بالاسم.')
            ->with('navProduction', 'active');
    }

//

}
