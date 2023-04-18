@extends('layouts.app')

@section('content')
    @include('layouts.partials.aboutus-header')
    <div class="container mx-auto mt-14">
        <div class="flex" style="height: 600px;">
            <div class="flex-1">
                {!! $content !!}
            </div>


        </div>

        @include('layouts.partials.cart-row')
    </div>
@endsection

@section('javascript')
@endsection
