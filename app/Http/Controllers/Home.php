<?php

namespace App\Http\Controllers;

use App\group;
use Illuminate\Http\Request;

class Home extends Controller
{
    //
    public function index()
    {

        //            $datas['groups'] = group::query()->select('group_name')->get()->all()['group_name'];


//          $groups = group::orderBy('group_name', 'asc')->get();
        $getgroup = group::select('group_name')->get();
//            ->where('group_id','=',$request->group_id);
//        $groups = $getgroup -> select('group_name')->get()->first()['group_name'];
        return $getgroup;
        return view('homeview');
//        return view('homeview')->with('groups',$groups);
    }

}
