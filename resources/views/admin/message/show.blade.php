@extends('layouts.admin')

@section('title', __('lang.message'))

@section('content_header')
    <h1>@lang('lang.message')</h1>
@stop

@section('main_content')
    <x-adminlte-card title="{{ __('lang.message') }}" theme="{{ config('adminlte.right_sidebar_theme') }}"
        theme-mode="outline" icon="fas fa-file">

        <div class="row">
            <div class="col-md-12">
                <div class=" row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="to">{{ __('lang.to') }}:</label>
                            <input type="text" class="form-control" id="to" name="to" readonly
                                value="{{ $message->emails }}">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="subject">{{ __('lang.subject') }}:</label>
                            <input type="text" class="form-control" id="name" name="subject" readonly
                                value="{{ $message->subject }}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="body">{{ __('lang.body') }}:</label>
                            <br>
                            {!! $message->body !!}
                        </div>
                    </div>
                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            <label for="attachment">{{ __('lang.attachment') }}:</label> <br>
                            @foreach ($message->attachments as $item)
                                <a target="_blank" href="{{ asset($item) }}{{ str_replace('/emails/', '', $item) }}"></a>
                                <br>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="note">{{ __('lang.notes') }}:</label> <br>
                            <textarea name="notes" id="notes" cols="30" rows="3" readonly
                                class="form-control">{{ $message->notes }}</textarea>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>

    </x-adminlte-card>
@stop
@section('javascript')
    <script>
        $('#summernote').summernote('disable');
    </script>
@endsection
