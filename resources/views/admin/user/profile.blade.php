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
            {!! Form::open(['url' => action('Admin\UserController@updateProfile', $user->id), 'method' => 'pos', 'files' => true, 'id' => 'user_form']) !!}
            <div class="card mb-4">
                <div class="card-header">@lang('lang.details')</div>
                <div class="card-body">
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
                                {!! Form::text('email', $user->email, ['class' => 'form-control', 'readonly', 'placeholder' => __('lang.email')]) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('date_of_join', __('lang.date_of_join'), []) !!}
                                {!! Form::text('date_of_join', !empty($user->date_of_join) ? @format_date($user->date_of_join) : null, ['class' => 'form-control datepicker', 'readonly', 'placeholder' => __('lang.date_of_join')]) !!}
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
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                            {!! Form::label('password', __('lang.password'), []) !!}
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="@lang('lang.password')">
                        </div>
                        <div class="col-md-4">
                            {!! Form::label('confirm_password', __('lang.confirm_password'), []) !!}
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="@lang('lang.confirm_password')">
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <button type="button" class="btn btn-primary pull-right" id="submit_btn">@lang('lang.save')</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

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
    </script>
@endsection
