@extends('layouts.admin')

@section('title', __('lang.system_settings'))

@section('content_header')
    <h1>@lang('lang.system_settings')</h1>
@stop

@section('main_content')
    {!! Form::open(['url' => action('Admin\SettingController@saveSystemSettings'), 'method' => 'post', 'id' => 'setting_form', 'files' => true]) !!}
    <x-adminlte-card title="{{ __('lang.system_settings') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-file">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('logo', __('lang.logo'), []) !!} <small class="text-red">@lang('lang.250_250')</small>
                    <x-adminlte-input-file name="logo" placeholder="{{ __('lang.choose_a_file') }}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-lightblue">
                                <i class="fas fa-upload"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-file>
                </div>
            </div>
            <div class="col-md-8 mb-5">
                @php
                    $logo = !empty($settings['logo']) ? $settings['logo'] : null;
                @endphp
                @if (!empty($logo))
                    <img style="width: 250px; height: 250px;" src="{{ asset('uploads/' . $logo) }}" alt="">
                @endif
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    @if (!empty($settings['home_background_image']))
                        <button type="button" class="btn btn-xs btn-danger remove_image"
                            data-type="home_background_image"><i class="fa fa-times"></i></button>
                    @endif
                    {!! Form::label('home_background_image', __('lang.home_background_image'), []) !!} <small class="text-red">@lang('lang.1350_450')</small>
                    <x-adminlte-input-file name="home_background_image" placeholder="{{ __('lang.choose_a_file') }}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-lightblue">
                                <i class="fas fa-upload"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-file>
                </div>
            </div>
            <div class="col-md-8 mb-5">
                @php
                    $home_background_image = !empty($settings['home_background_image']) ? $settings['home_background_image'] : null;
                @endphp
                @if (!empty($home_background_image))
                    <img style="width: 250px; height: 250px;" src="{{ asset('uploads/' . $home_background_image) }}"
                        alt="">
                @endif
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    @if (!empty($settings['breadcrumb_background_image']))
                        <button type="button" class="btn btn-xs btn-danger remove_image"
                            data-type="breadcrumb_background_image"><i class="fa fa-times"></i></button>
                    @endif
                    {!! Form::label('breadcrumb_background_image', __('lang.breadcrumb_background_image'), []) !!} <small class="text-red">@lang('lang.1350_450')</small>
                    <x-adminlte-input-file name="breadcrumb_background_image"
                        placeholder="{{ __('lang.choose_a_file') }}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-lightblue">
                                <i class="fas fa-upload"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-file>
                </div>
            </div>
            <div class="col-md-8 mb-5">
                @php
                    $breadcrumb_background_image = !empty($settings['breadcrumb_background_image']) ? $settings['breadcrumb_background_image'] : null;
                @endphp
                @if (!empty($breadcrumb_background_image))
                    <img style="width: 250px; height: 250px;" src="{{ asset('uploads/' . $breadcrumb_background_image) }}"
                        alt="">
                @endif
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    @if (!empty($settings['page_background_image']))
                        <button type="button" class="btn btn-xs btn-danger remove_image"
                            data-type="page_background_image"><i class="fa fa-times"></i></button>
                    @endif
                    {!! Form::label('page_background_image', __('lang.page_background_image'), []) !!} <small class="text-red">@lang('lang.1350_900')</small>
                    <x-adminlte-input-file name="page_background_image" placeholder="{{ __('lang.choose_a_file') }}">
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-lightblue">
                                <i class="fas fa-upload"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-file>
                </div>
            </div>
            <div class="col-md-8 mb-5">
                @php
                    $page_background_image = !empty($settings['page_background_image']) ? $settings['page_background_image'] : null;
                @endphp
                @if (!empty($page_background_image))
                    <img style="width: 250px; height: 250px;" src="{{ asset('uploads/' . $page_background_image) }}"
                        alt="">
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('site_title', __('lang.site_title'), []) !!}
                    {!! Form::text('site_title', !empty($settings['site_title']) ? $settings['site_title'] : null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('facebook', __('lang.facebook'), []) !!}
                    {!! Form::text('facebook', !empty($settings['facebook']) ? $settings['facebook'] : null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('twitter', __('lang.twitter'), []) !!}
                    {!! Form::text('twitter', !empty($settings['twitter']) ? $settings['twitter'] : null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('whatsapp', __('lang.whatsapp'), []) !!} <small>i.e 90123456789</small>
                    {!! Form::text('whatsapp', !empty($settings['whatsapp']) ? $settings['whatsapp'] : null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('youtube', __('lang.youtube'), []) !!}
                    {!! Form::text('youtube', !empty($settings['youtube']) ? $settings['youtube'] : null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('instagram', __('lang.instagram'), []) !!}
                    {!! Form::text('instagram', !empty($settings['instagram']) ? $settings['instagram'] : null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('telegram', __('lang.telegram'), []) !!}
                    {!! Form::text('telegram', !empty($settings['telegram']) ? $settings['telegram'] : null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('phone_number_1', __('lang.phone_number_1'), []) !!}
                    {!! Form::text('phone_number_1', !empty($settings['phone_number_1']) ? $settings['phone_number_1'] : null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('phone_number_2', __('lang.phone_number_2'), []) !!}
                    {!! Form::text('phone_number_2', !empty($settings['phone_number_2']) ? $settings['phone_number_2'] : null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('system_email', __('lang.email'), []) !!}
                    {!! Form::text('system_email', !empty($settings['system_email']) ? $settings['system_email'] : null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('open_time', __('lang.open_time'), []) !!}
                    {!! Form::text('open_time', !empty($settings['open_time']) ? $settings['open_time'] : null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('address', __('lang.address'), []) !!}
                    {!! Form::text('address', !empty($settings['address']) ? $settings['address'] : null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                {!! Form::label('language', __('lang.language'), []) !!}
                {!! Form::select('language', $locales, !empty($settings['language']) ? $settings['language'] : null, ['class' => 'form-control select2', 'data-live-search' => 'true', 'placeholder' => __('lang.please_select')]) !!}
            </div>
            <div class="col-md-6">
                {!! Form::label('currency', __('lang.currency'), []) !!}
                {!! Form::select('currency', $currencies, !empty($settings['currency']) ? $settings['currency'] : null, ['class' => 'form-control select2', 'data-live-search' => 'true']) !!}
            </div>
            <br>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('about_us_footer', __('lang.about_us_footer'), []) !!}
                    {!! Form::text('about_us_footer', !empty($settings['about_us_footer']) ? $settings['about_us_footer'] : null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('about_us_content', __('lang.about_us_content'), []) !!} <p>@lang('lang.tags'): {store_name}, {store_location}, {store_phone_number},
                        {all_store_names} <i class="fa fa-info-circle text-primary" data-toggle="tooltip"
                            title="@lang('lang.use_tags_info')"></i></p>
                    @php
                        $config = config('adminlte.editor');
                    @endphp
                    <x-adminlte-text-editor name="about_us_content" :config="$config">
                        {{ !empty($settings['about_us_content']) ? $settings['about_us_content'] : null }}
                    </x-adminlte-text-editor>
                </div>
            </div>

            <div class="col-md-4 hide">
                <div class="form-group">
                    {!! Form::label('homepage_category_count', __('lang.homepage_category_count'), []) !!}
                    {!! Form::select('homepage_category_count', [4 => 4, 8 => 8], !empty($settings['homepage_category_count']) ? $settings['homepage_category_count'] : 8, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('homepage_category_carousel', __('lang.homepage_category_carousel'), []) !!} <br>
                    {!! Form::checkbox('homepage_category_carousel', 1, !empty($settings['homepage_category_carousel']) ? true : false, ['class']) !!}
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">@lang('lang.save')</button>
            </div>
        </div>
    </x-adminlte-card>

    {!! Form::close() !!}

@stop
@section('javascript')
    <script>
        $("[name='homepage_category_carousel']").bootstrapSwitch();

        $(document).on('click', '.remove_image', function() {
            var type = $(this).data('type');
            $.ajax({
                url: "/admin/settings/remove-image/" + type,
                type: "POST",
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                }
            });
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endsection
