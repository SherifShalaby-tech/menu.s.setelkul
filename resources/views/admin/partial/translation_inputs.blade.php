@php
$config_langs = LaravelLocalization::getSupportedLocales();
@endphp


<table class="table hide" @if(!empty($type))id="translation_table_{{$type}}" @else id="translation_table" @endif>
    <tbody>
        @foreach ($config_langs as $key => $lang)
            <tr>
                <td> <input class="form-control" type="text" name="translations[{{ $attribute }}][{{ $key }}]"
                        value="@if (!empty($translations[$attribute][$key])) {{ $translations[$attribute][$key] }} @endif"
                        placeholder="{{ $lang['name'] }}"></td>
            </tr>
        @endforeach
    </tbody>
</table>
