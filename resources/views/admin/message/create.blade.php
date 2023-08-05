@extends('layouts.admin')

@section('title', __('lang.send_message'))

@section('content_header')
    <h1>@lang('lang.send_message')</h1>
@stop

@section('main_content')
    <x-adminlte-card title="{{ __('lang.filter') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-filter">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('user_id', __('lang.user'), []) !!}
                    {!! Form::select('user_id[]', $users, !empty($message) ? $message : false, ['class' => 'form-control select2', 'multiple', 'id' => 'user_id']) !!}
                </div>
            </div>
        </div>
    </x-adminlte-card>

    {!! Form::open(['url' => action('Admin\MessageController@store'), 'method' => 'post', 'files' => true, 'id' => 'message_form']) !!}

    <x-adminlte-card title="{{ __('lang.send_message') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-file">

        <div class="row">
            <div class="col-md-12">
                <div class=" row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="to">{{ __('lang.to') }}:
                                <small>@lang('lang.separated_by_comma')</small></label>
                            <input type="text" class="form-control" id="to" name="to" required
                                value="@if (!empty($number_string)) {{ $number_string }} @endif">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="subject">{{ __('lang.subject') }}:</label>
                            <input type="text" class="form-control" id="name" name="subject" required=""
                                value="{{ old('subject') }}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="body">{{ __('lang.body') }}:</label>
                            @php
                                $config = config('adminlte.editor');
                            @endphp
                            <x-adminlte-text-editor name="body" :config="$config" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="body">{{ __('lang.attachment') }}:</label> <br>
                            <input type="file" name="attachments[]" id="attachments" class="" multiple>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="body">{{ __('lang.notes') }}:</label> <br>
                            <textarea name="notes" id="notes" cols="30" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </x-adminlte-card>

    <div class="col-md-12">
        <button type="submit" class="btn btn-primary pull-right" id="submit_btn">@lang('lang.send')</button>
    </div>
    {!! Form::close() !!}
@stop
@section('javascript')
    <script>
        $(document).ready(function() {
            $('#user_id').change()
        })
        $('#user_id').change(function() {
            let numbers = $(this).val();
            numbers = numbers.filter(e => e);
            $('#to').val(numbers.join());

        });
    </script>
@endsection
