@extends('layouts.app')
@php
$locale_direction = LaravelLocalization::getCurrentLocaleDirection();
@endphp
@section('content')
    @include('layouts.partials.aboutus-header')
    <div class="container mx-auto mt-14">
        <div style="height: 400px;" class="mx-auto bg-red lg:w-1/2 rounded-lg text-white pt-10">
            <div class="flex bg11 pt-5 " >
                <div class="flex-1  @if ($locale_direction == 'rtl') text-right pr-3 @else text-left pl-3  cl5 @endif" >
                    <h4 style="text-shadow: 1px 1px #bdb9b9;" class="p-3">
                        {!! $content !!}
                    </h4>
                    
                </div>
            </div>
        </div>

        @include('layouts.partials.cart-row')
    </div>
@endsection

@section('javascript')
@endsection
