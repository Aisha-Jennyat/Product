@extends('layouts.appmain')

@section('main_navbar')
    @include('layouts.navbar')
@endsection

@section('content')
    <div class="container">
        <div class="form-row justify-content-start text-right">
            <div class="col-12 ">
                <span>أهلا بك <span style="color:red">{{ $userProd['name']??'' }}</span>  في برنامج تحديث انتاج الموظفين.</span>
                <table class="table table-responsive-xl mt-3">
                    <tr>
                        <td class="app-tb-td">رقمك الوظيفي:</td>
                        <td><span class="badge-pill badge-primary">{{ $userProd['numberid']??'' }}</span></td>
                    </tr>
                    <tr>
                        <td>عدد موظفين الإنتاج:</td>
                        <td>{{ $count??'0' }} موظف</td>
                    </tr>
                </table>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12 app-btn-mb">
                <a class="btn btn-primary btn-block" href="{{ route('production.index') }}">إدخال الإنتاج</a>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-12">
                <a class="btn btn-primary btn-block" href="{{ route('statistics') }}">احصائيات</a>
            </div>
        </div>
    </div>
@endsection

@section('main_footer')
    @include('layouts.footer')
@endsection