@extends('layouts.admin')

@section('title', __('lang.edit_user'))

@section('content_header')
    <h1>@lang('lang.edit_user')</h1>
@stop

@section('main_content')
    {!! Form::open(['url' => action('Admin\UserController@update', $user->id), 'method' => 'put', 'files' => true, 'id' => 'user_form']) !!}
    <x-adminlte-card title="{{ __('lang.edit_user') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-file">

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('name', __('lang.name') . ' *', []) !!}
                    {!! Form::text('name', $user->name, ['class' => 'form-control', 'required', 'placeholder' => __('lang.name')]) !!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('email', __('lang.email') . ' *', []) !!}
                    {!! Form::text('email', $user->email, ['class' => 'form-control', 'required', 'placeholder' => __('lang.email')]) !!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('date_of_join', __('lang.date_of_join'), []) !!}
                    {!! Form::text('date_of_join', !empty($user->date_of_join) ? @format_date($user->date_of_join) : null, ['class' => 'form-control datepicker', 'placeholder' => __('lang.date_of_join')]) !!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('phone', __('lang.phone'), []) !!}
                    {!! Form::text('phone', $user->phone, ['class' => 'form-control', 'placeholder' => __('lang.phone')]) !!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('image', __('lang.image'), []) !!}
                    <x-adminlte-input-file name="image" placeholder="{{ __('lang.choose_a_file') }}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-lightblue">
                                <i class="fas fa-upload"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-file>
                </div>
            </div>
            <div class="col-md-4"></div>

            <div class="col-md-4">
                {!! Form::label('password', __('lang.password'), []) !!}
                <input type="password" class="form-control" name="password" id="password"
                    placeholder="@lang('lang.password')">
            </div>
            <div class="col-md-4">
                {!! Form::label('confirm_password', __('lang.confirm_password'), []) !!}
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    placeholder="@lang('lang.confirm_password')">
            </div>



        </div>

    </x-adminlte-card>

    <x-adminlte-card title="{{ __('lang.permissions') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-file">

        <div class="row">
            @include('admin.user.partial.permission')

        </div>

    </x-adminlte-card>

    <div class="col-md-12">
        <button type="button" class="btn btn-primary pull-right" id="submit_btn">@lang('lang.save')</button>
    </div>
    {!! Form::close() !!}
@stop
@section('javascript')
    <script>
        $(document).on('click', '#submit_btn', function(e) {
            jQuery('#user_form').validate({
                rules: {
                    password: {
                        minlength: function() {
                            return $('#password').val().length > 0 ? 6 : 0;
                        }
                    },
                    password_confirmation: {
                        minlength: function() {
                            return $('#password').val().length > 0 ? 6 : 0;
                        },
                        equalTo: {
                            depends: function() {
                                return $('#password').val().length > 0;
                            },
                            param: "#password"
                        }
                    }
                }
            });
            if ($('#user_form').valid()) {
                $('form#user_form').submit();
            }
        });

        $('.checked_all').change(function() {
            tr = $(this).closest('tr');
            var checked_all = $(this).prop('checked');

            tr.find('.check_box').each(function(item) {
                if (checked_all === true) {
                    $(this).prop('checked', true)
                } else {
                    $(this).prop('checked', false)
                }
            })
        })
        $('.all_module_check_all').change(function() {
            var all_module_check_all = $(this).prop('checked');
            $('#permission_table > tbody > tr').each((i, tr) => {
                $(tr).find('.check_box').each(function(item) {
                    if (all_module_check_all === true) {
                        $(this).prop('checked', true)
                    } else {
                        $(this).prop('checked', false)
                    }
                })
                $(tr).find('.module_check_all').each(function(item) {
                    if (all_module_check_all === true) {
                        $(this).prop('checked', true)
                    } else {
                        $(this).prop('checked', false)
                    }
                })
                $(tr).find('.checked_all').each(function(item) {
                    if (all_module_check_all === true) {
                        $(this).prop('checked', true)
                    } else {
                        $(this).prop('checked', false)
                    }
                })

            })
        })
        $('.module_check_all').change(function() {
            let moudle_id = $(this).closest('tr').data('moudle');
            if ($(this).prop('checked')) {
                $('.sub_module_permission_' + moudle_id).find('.checked_all').prop('checked', true);
                $('.sub_module_permission_' + moudle_id).find('.check_box').prop('checked', true);
            } else {
                $('.sub_module_permission_' + moudle_id).find('.checked_all').prop('checked', false);
                $('.sub_module_permission_' + moudle_id).find('.check_box').prop('checked', false);
            }
        });
        $(function() {
            $(".datepicker").daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: "Clear",
                },
                singleDatePicker: true,
            });

            $(".datepicker").on("apply.daterangepicker", function(ev, picker) {
                $(this).val(picker.startDate.format("MM/DD/YYYY"));
            });

            $(".datepicker").on("cancel.daterangepicker", function(ev, picker) {
                $(this).val("");
            });
        });
    </script>

@endsection
