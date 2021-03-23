@extends('layouts.appmain')

@section('main_navbar')
    @include('layouts.navbar')
@endsection

@section('content')

    @if(isset($message))
        <div class="col-12 text-right mt-3 pl-4">
            <div class="alert alert-{{ $state }} small">
                <span class="mr-1">{{ $message }}</span>
            </div>
        </div>

    @endif

    @if(isset($data) )
                    @if($data->count() === 1)
                        <form id="modify_form" method="POST" action="{{ route('confirm') }}">
                            <div class="row">
                                <div class="col-12 text-right ">
                                    <label class="pr-3 pt-2"> تاريخ إدخال الإنتاج:</label>
                                            <span class="badge-dark badge-pill ">{{ $data->first()['updated_at']  }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-right ">
                                    <label class="pr-3 pt-2"> رقم المجموعة:</label>
                                            <span class="badge-dark badge-pill ">{{ $data->first()['group_name']  }}
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-12 text-right ">
                                    <label class="pr-3 pt-2"> الوردية:</label>
                                            <span class="badge-dark badge-pill ">{{ $data->first()['period']  }}
                                </div>
                            </div>


                            <div class="col-12 pl-4 mt-4">
                                <div class="card">
                                    <div class="card-header app-card-table">
                                        <table class="table table-borderless text-center m-0">
                                            <thead>
                                            <tr>
                                                <td width="10%">رقم الموظف</td>
                                                <td width="30%">اسم الموظف</td>
                                                <td width="10%">الإنتاج الكلي</td>
                                                <td width="10%">الإنتاج اليومي</td>
                                                <td width="40%">الإنتاج</td>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="card-body app-card-table">
                                        <table class="table table-borderless text-center m-0">
                                            <tr>
                                                <td width="10%"><span class="badge-pill badge-primary">{{ $data->first()['id_emp'] }}</span></td>
                                                <td width="30%">{{ $data->first()['name'] }}</td>
                                                <td width="10%"><span class="badge-pill badge-dark">{{ $data->first()['all_production'] }}</span></td>
                                                <td width="10%"><span class="badge-pill badge-dark">{{ $data->first()['daily_production'] }}</span></td>
                                                <td width="40%" class="text-right pr-3 ">
                                                    <input type="text" name="id_emp" value="{{ $data->first()['id_emp'] }}" hidden>
                                                    <input type="text" name="name" value="{{ $data->first()['name'] }}" hidden>
                                                    <input type="text" name="all_production" value="{{ $data->first()['all_production'] }}" hidden>
                                                    <input type="text" name="daily_production" value="{{ $data->first()['daily_production'] }}" hidden>
                                                    <input type="text" name="group_name" value="{{ $data->first()['group_name'] }}" hidden>
                                                    <input type="text" name="period" value="{{ $data->first()['period'] }}" hidden>
                                                    <input type="text" name="updated_at" value="{{ $data->first()['updated_at'] }}" hidden>
                                                    <input type="text" class="form-control" name="pro1" value="{{ $data->first()['pro1'] }}" >
                                                    <input type="text" class="form-control" name="pro2" value="{{ $data->first()['pro2'] }}">
                                                    <input type="text" class="form-control" name="pro3" value="{{ $data->first()['pro3'] }}" >
                                                    <input type="text" class="form-control" name="pro4" value="{{ $data->first()['pro4'] }}">
                                                    <input type="text" class="form-control" name="pro5" value="{{ $data->first()['pro5'] }}">
                                                    <input type="text" class="form-control" name="pro6" value="{{ $data->first()['pro6'] }}">
                                                    <input type="text" class="form-control" name="pro7" value="{{ $data->first()['pro7'] }}">
                                                    <input type="text" class="form-control" name="pro8" value="{{ $data->first()['pro8'] }}">
                                                    <input type="text" class="form-control" name="pro9" value="{{ $data->first()['pro9'] }}">
                                                    <input type="text" class="form-control" name="pro10" value="{{ $data->first()['pro10'] }}">

                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 mr-3">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-block mt-4">تعديل الإنتاج اليومي</button>
                                </div>
                            </div>
                        </form>

                    @endif
                @endif

@endsection

@section('main_footer')
    @include('layouts.footer')
@endsection