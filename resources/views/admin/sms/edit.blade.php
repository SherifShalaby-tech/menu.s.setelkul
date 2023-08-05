@extends('layouts.admin')

@section('title', __('lang.edit_sms'))

@section('content_header')
    <h1>@lang('lang.edit_sms')</h1>
@stop

@section('main_content')
    <x-adminlte-card title="{{ __('lang.filter') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-filter">
        <div class="row">
            <div class="col-md-12">
                <input id="select_all" name="select_all" type="checkbox" value="1" class="form-control-custom">
                <label for="select_all"><strong>@lang('lang.select_all')</strong></label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('user_id', __('lang.users'), []) !!}
                    {!! Form::select('user_id[]', ['select_all' => __('lang.select_all')] + $users, !empty($user_mobile_number) ? [$user_mobile_number] : false, ['class' => 'form-control select2', 'multiple', 'data-live-search' => 'true', 'id' => 'user_id']) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('customer_id', __('lang.customer'), []) !!}
                    {!! Form::select('customer_id[]', ['select_all' => __('lang.select_all')] + $customers, !empty($customer_mobile_number) ? [$customer_mobile_number] : false, ['class' => 'form-control select2', 'multiple', 'data-live-search' => 'true', 'id' => 'customer_id']) !!}
                </div>
            </div>
        </div>
    </x-adminlte-card>

    {!! Form::open(['url' => action('Admin\SmsController@update', $sms->id), 'method' => 'put', 'files' => true, 'id' => 'sms_form']) !!}

    <x-adminlte-card title="{{ __('lang.edit_sms') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-file">

        <div class="row">
            <div class="col-md-12">
                <div class=" row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="to">{{ __('lang.to') }}:
                                <small>@lang('lang.separated_by_comma')</small></label>
                            <input type="text" class="form-control" id="to" name="to" required
                                value="{{$sms->mobile_numbers}}">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="message">{{ __('lang.message') }}:</label>
                            <textarea name="message" id="message" cols="30" rows="6" required
                                class="form-control">{{$sms->message}}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="body">{{ __('lang.notes') }}:</label> <br>
                            <textarea name="notes" id="notes" cols="30" rows="3" class="form-control">{{$sms->notes}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </x-adminlte-card>

    <div class="col-md-12">
        <button type="submit" class="btn btn-primary pull-right" id="submit_btn">@lang('lang.update')</button>
    </div>
    {!! Form::close() !!}
@stop
@section('javascript')
    <script>
        $('#user_id').change(function() {
            let numbers = $(this).val()
            if (numbers.includes('select_all')) {
                $('#user_id').select2('selectAll')
            }
            get_numbers()
        })
        $('#customer_id').change(function() {
            let numbers = $(this).val()
            if (numbers.includes('select_all')) {
                $('#customer_id').select2('selectAll')
            }
            get_numbers()
        })


        $('#select_all').change(function() {
            if ($(this).prop('checked')) {
                $('#user_id').select2('selectAll')
                $('#customer_id').select2('selectAll')
            } else {
                $('#user_id').select2('deselectAll')
                $('#customer_id').select2('deselectAll')
            }
            get_numbers()
        })

        function get_numbers() {
            let employee_numbers = $('#user_id').val();
            let customer_numbers = $('#customer_id').val();
            let numbers = employee_numbers.concat(customer_numbers);
            var list_numbers = numbers.filter(function(e) {
                return e !== 'select_all'
            })

            list_numbers = list_numbers.filter(e => e);
            $('#to').val(list_numbers.join())
        }
    </script>
@endsection
