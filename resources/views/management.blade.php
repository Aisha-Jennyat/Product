@extends('layouts.appmain')

@section('main_navbar')
    @include('layouts.navbar')
@endsection

@section('content')
    <div class="container">
        <div class="form-row justify-content-center text-right">
            <div class="col-6">

                @if(session()->get('state') !== null && session()->get('result') !== null)
                    <div class="col-12 text-right">
                        <div class="alert alert-{{ !session()->get('state')?'success':'info' }} small">
                            <img src="{{ asset('/images/icons/' . (!session()->get('state')? 'update-icon.svg':'add.svg')) }}"
                                 class="app-filter-{{ !session()->get('state')? 'green':'blue' }}"
                                 width="16px" >
                            <span class="mr-1">
                            {{ session()->get('result') }}
                            </span>
                        </div>
                    </div>
                @endif


                <p class="text-muted">قم بإضافة أو تحديث معلومات موظفين الإنتاج:</p>
                <form method="POST" action="{{ route('management.add') }}">
                    @csrf
                    <div class="col-12">
                        <label>الرقم الوظيفي:</label>
                        <input type="text" class="form-control" name="id_emp" value="">
                        @if($errors->has('id_emp'))
                            <small class="text-danger">{{ $errors->first('id_emp') }}</small>
                        @endif
                    </div>
                    <div class="col-12">
                        <label>اسم الموظف:</label>
                        <input type="text" class="form-control" name="name">
                        @if($errors->has('id_emp'))
                            <small class="text-danger">{{ $errors->first('name') }}</small>
                        @endif
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary mt-3 pr-5 pl-5">إضافة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('main_footer')
    @include('layouts.footer')
@endsection