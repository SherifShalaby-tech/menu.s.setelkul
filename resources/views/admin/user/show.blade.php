@extends('layouts.admin')

@section('title', __('lang.users'))

@section('content_header')
    <h1>{{ ucfirst($user->name) }}</h1>
@stop

@section('main_content')
    <div class="row">
        <div class="col-xl-4">
            <div class="card mb-4 mb-xl-0">
                <div class="card-header">Profile Picture</div>
                <div class="card-body text-center">
                    <img class="img-account-profile rounded-circle mb-2" style="width: 250px; height: 250px;"
                        src=" @if (!empty($user->getFirstMediaUrl('profile'))) {{ $user->getFirstMediaUrl('profile') }}@else{{ asset('/uploads/' . session('logo')) }} @endif"
                        alt="">
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">@lang('lang.details')</div>
                <div class="card-body">
                    <form>
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                {!! Form::label('name', __('lang.name'), ['class' => 'small mb-1']) !!}
                                {!! Form::text('name', $user->name, ['class' => 'form-control', 'readonly', 'placeholder' => __('lang.name')]) !!}
                            </div>
                            <div class="col-md-6">
                                {!! Form::label('email', __('lang.email'), ['class' => 'small mb-1']) !!}
                                {!! Form::text('email', $user->email, ['class' => 'form-control', 'readonly', 'placeholder' => __('lang.email')]) !!}
                            </div>
                        </div>
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                {!! Form::label('phone', __('lang.phone'), ['class' => 'small mb-1']) !!}
                                {!! Form::text('phone', $user->phone, ['class' => 'form-control', 'readonly', 'placeholder' => __('lang.phone')]) !!}
                            </div>
                            <div class="col-md-6">
                                {!! Form::label('date_of_join', __('lang.date_of_join'), ['class' => 'small mb-1']) !!}
                                {!! Form::text('date_of_join', !empty($user->date_of_join) ? @format_date($user->date_of_join) : null, ['class' => 'form-control datepicker', 'readonly', 'placeholder' => __('lang.date_of_join')]) !!}
                            </div>
                        </div>
                        {{-- <button class="btn btn-primary" type="button">Save changes</button> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
@section('javascript')
    <script>

    </script>
@endsection
