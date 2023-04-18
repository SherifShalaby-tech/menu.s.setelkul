@extends('layouts.admin')

@section('title', __('lang.message_settings'))

@section('content_header')
    <h1>@lang('lang.message_settings')</h1>
@stop

@section('main_content')
    {!! Form::open(['url' => action('Admin\MessageController@saveSetting'), 'method' => 'post', 'files' => true, 'id' => 'sms_form']) !!}

    <x-adminlte-card title="{{ __('lang.message_settings') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-file">

        <div class="row">
            <div class="col-md-12">
                <div class=" row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="system_email">{{ __('lang.email') }}:</label>
                            <input type="text" class="form-control" id="system_email" name="system_email" required
                                value="@if (!empty($settings['system_email'])) {{ $settings['system_email'] }} @endif">
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
