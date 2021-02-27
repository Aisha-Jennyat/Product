@extends('layouts.appmain')

@section('main_navbar')
    @include('layouts.navbar')
@endsection

@section('content')
    <div class="container">
        <div class="form-row justify-content-start text-right">

            <div class="col-12 mb-3">
                <span>تحديد تاريخ العرض:</span>
                <form method="POST" action="" class="mt-3" id="form-view-date">
                    <div class="form-row">
                        @csrf
                        <div class="col-10">
                            <input type="date" name="datestart" class="form-control" value="{{ $datestart }}" >
                        </div>
                        <div class="col-2">
                            <input type="submit" class="btn btn-primary btn-block" value="اعرض" >
                        </div>
                    </div>
                </form>
            </div>

            @if($isProduct)
            <div class="col-12 mb-3">
                <span>يظهر هذ الجدول الموظفين التي تم إضافتهم اليوم:</span>
                <table class="table table-responsive-sm mt-3">
                    <thead>
                        <td>الرقم الوظيفي</td>
                        <td>الاسم</td>
                        <td>الانتاج اليومي</td>
                        <td>الانتاج الكلي خلال الشهر</td>
                        <td>تاريخ الإضافة</td>
                    </thead>
                    @foreach($prodEmployees as $emps)
                    <tr>
                        <td><span class="badge-primary badge-pill">{{ $emps['id_emp'] }}</span></td>
                        <td>{{ $emps['name'] }}</td>
                        <td><span class="badge-dark badge-pill">{{ $emps['production'] }}</span></td>
                        <td><span class="badge-dark badge-pill">{{ $emps['all_production'] }}</span></td>
                        <td><span class="badge-dark badge-pill pl-0">
                                {{ \Illuminate\Support\Carbon::createFromTimeString($emps['updated_at'])->toDateString() }}
                                <span class="badge-primary badge-pill">
                                    {{ \Illuminate\Support\Carbon::createFromTimeString($emps['updated_at'])->toTimeString() }}
                                </span>
                            </span></td>
                    </tr>
                    @endforeach
                </table>
            </div>
            @else
                <div class="col-12 mb-4 border p-5">
                    <div class="text-muted text-center">لايوجد أي إدخال في هذا الشهر بتاريخ: {{ date("Y-m-d") }}</div>
                </div>
            @endif
        <div class="col-12">
            @if(session()->has('error'))
                <div class="alert alert-danger alert-dismissible small">
                    <div>{{ session()->get('error') }}</div>
                    <button type="button" class="close" data-dismiss="alert">×</button>
                </div>
            @endif
        </div>

        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 app-btn-mb">
            <a class="btn btn-info btn-block"
               onclick="
                       event.preventDefault();
                       document.getElementById('form-view-date').action = '{{ route('statistics.downloadFileDay') }}';
                       document.getElementById('form-view-date').submit()">
                <img src="{{asset('images/icons/pdf-icon.svg')}}" width="16px"/>
                <span class="pr-1">تحميل ملف الإنتاج اليومي</span></a>
        </div>
            @if(request()->user()['grants'] == 'admin')
        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 app-btn-mb">
            <a class="btn btn-info btn-block" href="{{ route('statistics.downloadFileMonth') }}">
            <img src="{{asset('images/icons/pdf-icon.svg')}}" width="16px"/><span class="pr-1">تحميل ملف أخر انتاج  في هذا الشهر</span></a>
        </div>
        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 app-btn-mb">
            <a class="btn btn-info btn-block" data-toggle="collapse" data-target="#lastmonth"><img src="{{asset('images/icons/pdf-icon.svg')}}" width="16px"/><span class="pr-1">الأشهر الماضية</span></a>
        </div>
            @endif

        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
            <a class="btn btn-primary btn-block" href="{{ route('index') }}">عودة</a>
        </div>
    </div>
        <div class="collapse" id="lastmonth">
            <div class="col-12 border p-4 mt-3" >
                @if($listFile->count() > 0)
                    <form method="post" action="{{ route('statistics.downloadLastFile') }}">
                        @csrf
                        <div class="form-row">
                            <div class="col-10">
                                <select class="form-control" name="datefile">
                                    @for($i = 0; $i < $listFile->count(); ++$i)
                                        <option selected>{{ $listFile->first()['date'] }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-2">
                                <button class="btn btn-info btn-block" type="submit"><img src="{{asset('images/icons/pdf-icon.svg')}}" width="16px"/><span class="pr-1">تحميل الملف</span></button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="text-muted text-center">لايوجد ملفات شهرية حاليا.</div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('main_footer')
    @include('layouts.footer')
@endsection