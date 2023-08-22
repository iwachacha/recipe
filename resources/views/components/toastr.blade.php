<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />

<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>

@if (session('toastr_success'))
    <script>
        toastr.success('{{ session('toastr_success') }}');
    </script>
@endif

@if (session('toastr_info'))
    <script>
        toastr.info('{{ session('toastr_info') }}');
    </script>
@endif

@if (session('toastr_warning'))
    <script>
        toastr.warning('{{ session('toastr_warning') }}');
    </script>
@endif

@if (session('toastr_error'))
    <script>
        toastr.error('{{ session('toastr_error') }}');
    </script>
@endif