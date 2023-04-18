@extends('adminlte::page')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
@endsection
@section('content_top_nav_right')
    @if (!empty(env('POS_SYSTEM_URL')))
        <a class="btn btn-primary btn-flat mb-3" href="{{ env('POS_SYSTEM_URL') }}" target="_blank"
            rel="noopener noreferrer">@lang('lang.pos')</a>
    @endif
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown" aria-expanded="true">
            <img class="h-5 w-5 rounded-full object-cover mx-1"
                src="{{ asset('images/' . app()->getLocale() . '-flag.png') }}"
                style="object-fit: cover; width: 1.25rem; height: 1.25rem; color: grey; @if(app()->getLocale() == 'ar') visiblity:hidden; @endif" alt="avatar">
            @lang('lang.'.app()->getLocale())
        </a>
        <ul class="dropdown-menu border-0 shadow" style="left: 0px; right: 0;">
            <li>
                <a class="dropdown-item"
                    href="{{ LaravelLocalization::getLocalizedURL('ar') }}">
                    <img class="" src="{{ asset('images/ar-flag.png') }}"
                        style="float: left; margin-top: 3px; width: 1.25rem; height: 1.25rem; color: grey; visiblity:hidden;" alt="avatar">
                    <p style="margin-left: 30px; padding-top: 0px;"> Arabic</p>
                </a>
            </li>
            <li>
                <a class="dropdown-item"
                    href="{{ LaravelLocalization::getLocalizedURL('nl') }}">
                    <img class="" src="{{ asset('images/nl-flag.png') }}"
                        style="float: left; margin-top: 3px; width: 1.25rem; height: 1.25rem; color: grey;" alt="avatar">
                    <p style="margin-left: 30px; padding-top: 0px;">Deutsch</p>
                </a>
            </li>
            <li>
                <a class="dropdown-item"
                    href="{{ LaravelLocalization::getLocalizedURL('fr') }}">
                    <img class="" src="{{ asset('images/fr-flag.png') }}"
                        style="float: left; margin-top: 3px; width: 1.25rem; height: 1.25rem; color: grey;" alt="avatar">
                    <p style="margin-left: 30px; padding-top: 0px;">français</p>
                </a>
            </li>
            <li>
                <a class="dropdown-item"
                    href="{{ LaravelLocalization::getLocalizedURL('en') }}">
                    <img class="" src="{{ asset('images/en-flag.png') }}"
                        style="float: left; margin-top: 3px; width: 1.25rem; height: 1.25rem; color: grey;" alt="avatar">
                    <p style="margin-left: 30px; padding-top: 0px;">English</p>
                </a>
            </li>
            <li>
                <a class="dropdown-item"
                    href="{{ LaravelLocalization::getLocalizedURL('tr') }}">
                    <img class="" src="{{ asset('images/tr-flag.png') }}"
                        style="float: left; margin-top: 3px; width: 1.25rem; height: 1.25rem; color: grey;" alt="avatar">
                    <p style="margin-left: 30px; padding-top: 0px;">Turkce</p>
                </a>
            </li>
            <li>
                <a class="dropdown-item"
                    href="{{ LaravelLocalization::getLocalizedURL('fa') }}">
                    <img class="" src="{{ asset('images/fa-flag.png') }}"
                        style="float: left; margin-top: 3px; width: 1.25rem; height: 1.25rem; color: grey;" alt="avatar">
                    <p style="margin-left: 30px; padding-top: 0px;">فارسی</p>
                </a>
            </li>
            <li>
                <a class="dropdown-item"
                    href="{{ LaravelLocalization::getLocalizedURL('ur') }}">
                    <img class="" src="{{ asset('images/ur-flag.png') }}"
                        style="float: left; margin-top: 3px; width: 1.25rem; height: 1.25rem; color: grey;" alt="avatar">
                    <p style="margin-left: 30px; padding-top: 0px;">اردو</p>
                </a>
            </li>
            <li>
                <a class="dropdown-item"
                    href="{{ LaravelLocalization::getLocalizedURL('hi') }}">
                    <img class="" src="{{ asset('images/hi-flag.png') }}"
                        style="float: left; margin-top: 3px; width: 1.25rem; height: 1.25rem; color: grey;" alt="avatar">
                    <p style="margin-left: 30px; padding-top: 0px;">हिन्दी</p>
                </a>
            </li>
        </ul>
    </li>
@endsection
@section('content')
    @if (session('status'))
        <div class="row alert-div">
            <div class="col-md-12">
                @if (session('status.success') == '1')
                    <x-adminlte-alert theme="success" title="{{ __('lang.success') }}" dismissable>
                        {{ session('status.msg') }}
                    </x-adminlte-alert>
                @endif
                @if (session('status.success') == '0')
                    <x-adminlte-alert theme="danger" title="{{ __('lang.error') }}" dismissable>
                        {{ session('status.msg') }}
                    </x-adminlte-alert>
                @endif
            </div>
        </div>
    @endif
    @yield('main_content')

@section('footer')
    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <p style="margin: 0">&copy; Copyright {{ date('Y') }} @lang('lang.proudly_developed_at') <a
                        href="http://sherifshalaby.tech">sherifshalaby.tech</a></p>
            </div>
        </div>
    </div>
@stop

<div class="modal fade view_modal no-print" role="dialog" aria-hidden="true"></div>
<div class="modal" id="cropper_modal" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('lang.crop_image_before_upload')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-12">
                            <img src="" id="sample_image" />
                        </div>
                        {{-- <div class="col-md-4">
                            <div class="preview_div"></div>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="crop" class="btn btn-primary">@lang('lang.crop')</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('lang.cancel')</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{ asset('js/accounting.min.js') }}"></script>

@section('js')
    <script type="text/javascript">
        base_path = "{{ url('/') }}";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function(jqXHR, settings) {
                if (settings.url.indexOf('http') === -1) {
                    settings.url = base_path + settings.url;
                }
            },
        });
    </script>
    <script type="text/javascript" src="{{ asset('js/datatable/pdfmake.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/datatable/vfs_fonts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/datatable/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/datatable/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/datatable/buttons.bootstrap4.min.js') }}">
        ">
    </script>
    <script type="text/javascript" src="{{ asset('js/datatable/buttons.colVis.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/datatable/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/datatable/buttons.print.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/datatable/sum().js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/datatable/dataTables.checkboxes.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/datatable/date-eu.js') }}"></script>
    <script src="{{ asset('js/common.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="{{ asset('admin/js/cropper.js') }}"></script>
    <script>
        $(document).on('click', '.delete_item', function(e) {
            e.preventDefault();
            swal({
                title: 'Are you sure?',
                text: "Are you sure You Wanna Delete it?",
                icon: 'warning',
            }).then(willDelete => {
                if (willDelete) {
                    var check_password = $(this).data('check_password');
                    var href = $(this).data('href');
                    var data = $(this).serialize();

                    swal({
                        title: 'Please Enter Your Password',
                        content: {
                            element: "input",
                            attributes: {
                                placeholder: "Type your password",
                                type: "password",
                                autocomplete: "off",
                                autofocus: true,
                            },
                        },
                        inputAttributes: {
                            autocapitalize: 'off',
                            autoComplete: 'off',
                        },
                        focusConfirm: true
                    }).then((result) => {
                        if (result) {
                            $.ajax({
                                url: check_password,
                                method: 'POST',
                                data: {
                                    value: result
                                },
                                dataType: 'json',
                                success: (data) => {

                                    if (data.success == true) {
                                        swal(
                                            'Success',
                                            'Correct Password!',
                                            'success'
                                        );

                                        $.ajax({
                                            method: 'DELETE',
                                            url: href,
                                            dataType: 'json',
                                            data: data,
                                            success: function(result) {
                                                if (result.success ==
                                                    true) {
                                                    swal(
                                                        'Success',
                                                        result.msg,
                                                        'success'
                                                    );
                                                    setTimeout(() => {
                                                        location
                                                            .reload();
                                                    }, 1500);
                                                    location.reload();
                                                } else {
                                                    swal(
                                                        'Error',
                                                        result.msg,
                                                        'error'
                                                    );
                                                }
                                            },
                                        });

                                    } else {
                                        swal(
                                            'Failed!',
                                            'Wrong Password!',
                                            'error'
                                        )

                                    }
                                }
                            });
                        }
                    });
                }
            });
        });
        $(document).on('click', '.btn-modal', function(e) {
            e.preventDefault();
            var container = $(this).data('container');
            $.ajax({
                url: $(this).data('href'),
                dataType: 'html',
                success: function(result) {
                    $(container).html(result).modal('show');
                },
            });
        });
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert-div').slideUp('slow');
            }, 3000);
        });
        $('.hidden_menu').hide();
        $('.select2').select2();
    </script>
    @yield('javascript')
@endsection
@endsection
