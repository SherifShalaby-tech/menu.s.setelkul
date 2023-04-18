@extends('layouts.admin')

@section('title', __('lang.edit_message'))

@section('content_header')
    <h1>@lang('lang.edit_message')</h1>
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

    {!! Form::open(['url' => action('Admin\MessageController@update', $message->id), 'method' => 'put', 'files' => true, 'id' => 'message_form']) !!}

    <x-adminlte-card title="{{ __('lang.edit_message') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-file">

        <div class="row">
            <div class="col-md-12">
                <div class=" row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="to">{{ __('lang.to') }}:
                                <small>@lang('lang.separated_by_comma')</small></label>
                            <input type="text" class="form-control" id="to" name="to" required
                                value="{{ $message->emails }}">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="subject">{{ __('lang.subject') }}:</label>
                            <input type="text" class="form-control" id="name" name="subject" required=""
                                value="{{ $message->subject }}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="body">{{ __('lang.body') }}:</label>
                            @php
                                $config = config('adminlte.editor');
                            @endphp
                            <x-adminlte-text-editor name="body" :config="$config">
                                {{ $message->body }}
                            </x-adminlte-text-editor>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="attachment">{{ __('lang.attachment') }}:</label> <br>
                            <input type="file" name="attachments[]" id="attachments" class="" multiple>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="note">{{ __('lang.notes') }}:</label> <br>
                            <textarea name="notes" id="notes" cols="30" rows="3"
                                class="form-control">{{ $message->notes }}</textarea>
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
