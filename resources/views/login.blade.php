@extends('layouts.main')

@section('content')
    <div class="container text-right">
        <div class="row justify-content-center app-container-login">
                <div class="card app-card-login" >
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <img class="mx-auto d-block mb-4 app-img-login" src="{{URL::to('/')}}/images/almatin_group.png" alt="" width="126" height="140">
                            <h1 class="h6 mb-3 font-weight-normal text-muted text-center"><strong class="app-h6-login">برنامج تحديث الإنتاج</strong></h1>

                            @if($errors->has('error'))
                                <div class="alert alert-danger small">
                            <span class="text-right">
                                {{ $errors->first('error')}}
                            </span>
                                </div>
                            @endif

                            <label class="sr-only">الرقم الوظيفي</label>
                            <input type="text" value="{{old('numberid')}}" class="form-control mb-1 app-input-login {{ $errors->has('numberid') ? ' is-invalid' : '' }}" name="numberid" placeholder="الرقم الوظيفي" required autofocus>

                            @if($errors->has('numberid'))
                                <span class="text-danger small">{{ $errors->first('numberid')}}</span>
                            @endif
                            <label class="sr-only">كلمة السر</label>
                            <input type="password" class="form-control app-input-login {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="كلمة السر" required>
                            @if($errors->has('password'))
                                <span class="text-danger small">{{ $errors->first('password')}}</span>
                            @endif
                            <button id="btnsgin" class="btn btn-primary btn-block pt-2 pb-2 badge mt-3" type="submit">تسجيل الدخول</button>

                        </form>
                    </div>
                    <div class="card-footer pt-1 pb-1 text-center">
                        <span class="text-muted"><small>2022-2021 &copy;</small></span>
                    </div>
                </div>
        </div>
    </div>
@endsection