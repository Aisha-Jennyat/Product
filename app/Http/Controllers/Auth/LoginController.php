<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function loginGet()
    {
        return view('login');
    }

    public function loginPost(Request $request)
    {
        $message = [
            'numberid.required' => 'هذا الحقل مطلوب',
            'numberid.numeric'  => 'يجب أن يكون هذا الحقل أرقام فقط',
            'password.required' => 'هذا الحقل مطلوب',
            'password.min' => 'كلمة السر يجب أن ألا تقل عن 6 أحرف'
        ];

        $validator = Validator::make($request->all(),
        [
            'numberid' => 'required|numeric',
            'password' => 'required|string|min:6'
        ], $message);

        if($validator->fails())
            return back()->withErrors($validator)->withInput();
        else{
            if(Auth::attempt(
                ['numberid' => $request->numberid,
                 'password' => $request->password]
            ))
            {
//                return redirect()->route('index')->with('id_emp', $request->get('numberid'));
//                session()->flash('id_emp', $request->get('numberid'));
                session()->push('id_emp', $request->get('numberid'));
                return Redirect::route('index');
            }
            return redirect()->back()->withInput($request->only('numberid'))->withErrors([
                'error' => 'اسم المستخدم أو كلمة السر غير صحيحة يرجى إعادة المحاولة.',
            ]);
        }
    }
//    public function register()
//    {
//        return Hash::make('alm1234');
//    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        return redirect('/login');
    }

    public function logoutGet()
    {
        return back();
    }
}
