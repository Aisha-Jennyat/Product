@extends('layouts.appmain')

@section('main_navbar')
    @include('layouts.navbar')
@endsection

{{--<script>--}}
{{--function getFormById(id, id_input, value) {--}}
{{--    document.getElementById(id_input).value = value;--}}
{{--    event.preventDefault();--}}
{{--    document.getElementById(id).submit()--}}
{{--}--}}
{{--</script>--}}

@section('content')

{{--    <script type="text/javascript">--}}
{{--        var mytextbox = document.getElementById('displayUser');--}}
{{--        var mydropdown = document.getElementById('select_id');--}}
{{--        mydropdown.onchange = function(){--}}
{{--            mytextbox.value = mytextbox.value  + this.value; //to appened--}}
{{--            mytextbox.innerHTML = this.value;--}}
{{--        }--}}
{{--    </script>--}}

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <form id="search-form" method="POST" action="{{ route('search') }}">
                    @csrf
                    <div class="form-row col-12">
                        <div class="col-10">
                            <input id="input-search" class="form-control" value="{{ $old??'' }}" type="text" name="search" placeholder="البحث عن الموظف إما برقم التوظيف أو الاسم." required>
                        </div>
                        <div class="col-2">
                            <input class="btn btn-primary btn-block" value="ابحث" type="submit">
                        </div>
                    </div>
                </form>

                @if($errors->has('search'))
                <div class="col-12 text-right text-danger font-weight-bolder small">
                    <span>
                        {{ $errors->first('search') }}
                    </span>
                </div>
                @endif
{{--                @if($errors->has('production'))--}}
{{--                    <div class="col-12 text-right font-weight-bolder small pl-4 mt-4">--}}
{{--                        <div class="alert alert-danger">--}}
{{--                            {{ $errors->first('production') }}--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endif--}}

                @if(isset($message))
                    <div class="col-12 text-right mt-3 pl-4">
                        <div class="alert alert-{{ ($state)?'success':'info' }} small">
                            <img src="{{ asset('images/icons/' . ($state? 'update-icon.svg':'add.svg')) }}" class="app-filter-{{ ($state? 'green':'blue') }}" width="16px" >
                            <span class="mr-1">
                            {{ $message }}
                            </span>
                        </div>
                    </div>
                @endif

                @if(isset($data) )
                    @if($data->count() === 1)
                    <form id="store_form" method="POST" action="{{ route('store') }}">
                        <div class="row">
                            <div class="col-12 text-right ">
                                <label class="pr-3 pt-2">تحديد تاريخ إدخال الإنتاج:</label>
                                <div class="form-row text-right pl-4 pr-3">
                                    <div class="col-12">
                                        <input type="date" id="to_date_value" class="form-control" name="date_value" value="{{ $date_value }}">
                                    </div>
                                </div>
                            </div>



                            <div class="col-12 text-danger text-right small mr-3">
                                @if($errors->has('date_value'))
                                    <span>{{ $errors->first() }}</span>
                                @endif
                            </div>
                        </div>

{{--                        <form id="group_form" method="GET" action="{{ route('SearchController@store',[$groups],true) }}">--}}
                         <div class="row">
{{--                            <div class="form-group">--}}
                            <div class="col-12 text-right ">
                                <label class="pr-3 pt-2"> رقم المجموعة:</label>
                                <div class="form-row text-right pl-4 pr-3">
                                    <div class="col-12">
                                        <select id="group_name" name="group_name" class="form-control input-lg py-2">
                                            <option value=""> اختر مجموعة</option>
                                            <option value="الأولى">المجموعة الأولى</option>
                                            <option value="الثانية">المجموعة الثانية</option>
                                            <option value="الثالثة">المجموعة الثالثة</option>
                                            <option value="الرابعة">المجموعة الرابعة</option>
                                            <option value="الخامسة">المجموعة الخامسة</option>
                                            <option value="السادسة">المجموعة السادسة</option>
{{--                                            @foreach($getgroup as $group)--}}
{{--                                                <option value="{{ $group->group_id }}"> {{$group->group_name}}</option>--}}
{{--                                            @endforeach--}}
                                        </select>
                                        </div>
                                    </div>
                                </div>
{{--                            </div>--}}
                         </div>

{{--                        </form>--}}
                        <div class="row">
                            <div class="col-12 text-right ">
                                <label class="pr-3 pt-2"> الوردية:</label>
                                <div class="form-row text-right pl-4 pr-3">
                                    <div class="col-12">
                                        <div class="col-6">
                                            <input type="radio" name="period" id="period" value=" صباحية">
                                            <label class="pr-3 pt-2" >وردية صباحية</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="radio" name="period" id="period" value=" مسائية">
                                            <label class="pr-3 pt-2" >وردية مسائية</label>
                                        </div>
                                    </div>
                                </div>
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
                                                <td width="40%">الإنتاج الحالي</td>
                                                @if(request()->user()['grants'] == 'admin')
                                                <td width="10%">الخيارات</td>
                                                @endif
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="card-body app-card-table">
                                    <table class="table table-borderless text-center m-0">
                                        <thead>
                                        <tr>
                                            <td width="10%"><span class="badge-pill badge-primary">{{ $data->first()['id_emp'] }}</span></td>
                                            <td width="30%">{{ $data->first()['name'] }}</td>
                                            <td width="10%"><span class="badge-pill badge-dark">{{ $data->first()['all_production'] }}</span></td>
                                            <td width="40%" class="text-right">
                                                <input type="text" name="id_emp" value="{{ $data->first()['id_emp'] }}" hidden>
                                                <input type="text" name="name" value="{{ $data->first()['name'] }}" hidden>
                                                <input type="text" name="all_production" value="{{ $data->first()['all_production'] }}" hidden>
                                                <input type="text" class="form-control" name="production">
                                                @if($errors->has('production'))
                                                <small class="small text-danger">{{ $errors->first() }}</small>
                                                @endif
                                            </td>
                                            @if(request()->user()['grants'] == 'admin')
                                            <td width="10%">
                                                <div class="form-row pr-2">
                                                    <div class="col-12"><a class="btn btn-danger btn-block" data-toggle="modal" data-target="#modelDelete">حذف</a></div>
                                                </div>
                                            </td>
                                            @endif
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-block mt-4">تحديث</button>
                        </div>
                    </form>

                    @else
                        <div class="col-12 pt-4 pl-4 text-right">
                            <div class="list-group">
                                @foreach($data as $item)
                                    <a  class="list-group-item list-group-item-action"
                                        onclick="getForm({{ $item['id_emp'] }});">
                                        <span class="badge-info badge-pill small"><strong>{{ $item['id_emp'] }}</strong></span>
                                        <span class="mr-4">{{ $item['name'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <div class="col-12 text-center mt-5">
                        <span class="text-muted">{{ $messageStart }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if(request()->user()['grants'] == 'admin')
    @if(isset($data))
        <form method="POST" action="{{ route('production.delete') }}">
        @csrf
        <!-- Modal -->
            <div id="modelDelete" class="modal fade text-right" role="dialog" style="">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">تحذير</h4>
                        </div>
                        <div class="modal-body">
                            <p >هل تريد حذف الموظف <strong>{{ $data->first()['name'] }}؟</strong></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">إلغاء</button>
                            <input type="text" value="{{ $data->first()['id_emp'] }}" hidden name="id_emp">
                            <button type="submit" class="btn btn-default">موافق</button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    @endif
    @endif
@endsection

@section('main_footer')
    @include('layouts.footer')
@endsection