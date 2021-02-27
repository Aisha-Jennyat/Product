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
        <div class="row">
            <div class="form-group">
                <div class="col-12 text-right ">
                    <label class="pr-3 pt-2"> رقم المجموعة:</label>
                    <div class="form-row text-right pl-4 pr-3">
                        <div class="col-12">
                            <select id="select_id" name="group_id" class="form-control input-lg py-2">
                                <option value=""> اختر مجموعة</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->group_id }}"> {{$group->group_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection