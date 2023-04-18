@php
// $moment_time_format = App\Models\System::getProperty('time_format') == '12' ? 'hh:mm A' : 'HH:mm';
$moment_time_format = 'hh:mm A';
@endphp
<script>
    var moment_time_format = "{{ $moment_time_format }}";
</script>
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.4/jquery.validate.min.js"></script>
<script src="{{ asset('js/js.cookie.min.js') }}"></script>
<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/accounting.min.js') }}"></script>
<script src="{{ asset('js/common.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
