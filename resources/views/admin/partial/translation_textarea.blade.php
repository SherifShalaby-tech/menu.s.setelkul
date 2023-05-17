@php
$config_langs = LaravelLocalization::getSupportedLocales();
@endphp


<table class="table hide" id="translation_textarea_table">
    <tbody>
        @foreach ($config_langs as $key => $lang)
            <tr>
                <td>
                    <textarea name="details_translations[{{ $attribute }}][{{ $key }}]" class="form-control" cols="10" rows="2" placeholder="{{ $lang['name'] }}">@if (!empty($translations[$attribute][$key])){{ $translations[$attribute][$key] }}@endif</textarea>
            </tr>
        @endforeach
    </tbody>
</table>
