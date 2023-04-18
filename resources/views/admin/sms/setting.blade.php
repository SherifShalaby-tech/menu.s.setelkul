@extends('layouts.admin')

@section('title', __('lang.sms_settings'))

@section('content_header')
    <h1>@lang('lang.sms_settings')</h1>
@stop

@section('main_content')
    {!! Form::open(['url' => action('Admin\SmsController@saveSetting'), 'method' => 'post', 'files' => true, 'id' => 'sms_form']) !!}

    <x-adminlte-card title="{{ __('lang.sms_settings') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-file">

        <div class="row">
            <div class="col-md-12">
                <div class=" row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sms_username">{{ __('lang.username') }}:</label>
                            <input type="text" class="form-control" id="sms_username" name="sms_username" required
                                value="@if (!empty($settings['sms_username'])) {{ $settings['sms_username'] }} @endif">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sms_password">{{ __('lang.password') }}:</label>
                            <input type="text" class="form-control" id="sms_password" name="sms_password" required
                                value="@if (!empty($settings['sms_password'])) {{ $settings['sms_password'] }} @endif">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sms_sender_name">{{ __('lang.sender_name') }}:</label>
                            <input type="text" class="form-control" id="sms_sender_name" name="sms_sender_name" required
                                value="@if (!empty($settings['sms_sender_name'])) {{ $settings['sms_sender_name'] }} @endif">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </x-adminlte-card>

    <div class="col-md-12">
        <button type="submit" class="btn btn-primary pull-right" id="submit_btn">@lang('lang.save')</button>
    </div>
    {!! Form::close() !!}
@stop
@section('javascript')

@endsection
