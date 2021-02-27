<?php

namespace App\Http\Controllers;

use App\Employee_names;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManagementController extends Controller
{
    public function index()
    {
        return view('management')->with('navManagement', 'active');
    }

    public function directing()
    {
        return redirect('management');
    }

    public function add(Request $requst)
    {
        $state = true;
        $validator = Validator::make($requst->all(), [
            'id_emp' => 'required|numeric|unique:employee_names,id_emp',
            'name' => 'required|string'
        ],[
            'required' => 'الحقل مطلوب يرجئ ملئه.',
            'unique' => 'هذا الرقم الوظيفي موجود مسبقا الرجاء إضافة رقم وظيفي أخر.',
            'numeric' => 'هذا الحقل يجب أن يكون رقمي.'
        ]);

        if($validator->fails())
            return back()
                ->withErrors($validator)
                ->withInput();
        else
        {
            $state = true;
            Employee_names::query()->create([
                'id_emp' => $requst->get('id_emp'),
                'name' => $requst->get('name'),
                'all_production' => '0'
            ]);
            return redirect('management')
                ->with('result', 'تم إضافة الموظف:' . $requst->get('name') . ' برقم وظيفي: ' .$requst->get('id_emp'))
                ->with('state', $state);
        }
    }

//    public function modify(Request $requst)
//    {
//        return view('management');
//    }

}
