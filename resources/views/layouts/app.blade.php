<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="keywords"
        content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href={{asset('assets/images/brand/logo-1.png')}} />

    <!-- TITLE -->
    <title>Portal Afiliados</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href={{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}} rel="stylesheet" />
    <!-- STYLE CSS -->
    <link href={{asset('assets/css/style.css')}} rel="stylesheet" />
    <link href={{asset('assets/css/skin-modes.css')}} rel="stylesheet" />
    <link href={{asset('assets/css/select2.min.css')}} rel="stylesheet" />

    <link rel="stylesheet" href={{asset(('assets/thema/plugins/bower_components/custom-select/custom-select.css'))}}>
    <link rel="stylesheet"
        href={{asset(('assets/thema/plugins/bower_components/bootstrap-select/bootstrap-select.min.css'))}}>
    <link rel="stylesheet" href={{asset(('assets/thema/plugins/bower_components/multiselect/css/multi-select.css'))}}>

    <link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />

    @yield('styles')

    <!-- DATA TABLE CSS-->
    {{--
    <link href={{asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}} rel="stylesheet" /> --}}
    {{--
    <link href={{asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css')}} rel="stylesheet">
    <link href={{asset('assets/plugins/datatable/responsive.bootstrap5.css')}} rel="stylesheet" /> --}}
    {{--
    <link rel="stylesheet" href="{{asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}"> --}}

    <!--- FONT-ICONS CSS -->
    <link href={{asset('assets/css/icons.css')}} rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    {{--
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <style>
    :root {
        /* Tema Tractocar (naranja de marca) - alimenta automaticamente los botones,
           estados activos y badges del tema vía assets/js/themeColors.js */
        --primary-bg-color: #e8791a;
        --primary-bg-hover: #b85e0f;
        --primary-bg-border: #e8791a;
    }

    .background-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        max-width: 420px;
        height: auto;
        z-index: -1;
        opacity: 0.06;
        pointer-events: none;
        user-select: none;
    }

    .opacity-animation {
        animation-name: opacityAnimation;
        animation-duration: 4s;
        animation-timing-function: ease-in-out;
        animation-iteration-count: infinite;
    }

    @keyframes opacityAnimation {
        0% { opacity: 0.04; }
        50% { opacity: 0.09; }
        100% { opacity: 0.04; }
    }


    </style>
</head>

<body>
    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src={{asset('assets/images/loader.svg')}} class="loader-img" alt="Loader">
    </div>
    <div id="app">
        @include('layouts.header')

        <div class="">
            @include('layouts.sidebar')
        </div>

        <!-- Main Content -->
        <div class="">
            @yield('content')
        </div>

        <footer class="">
            @include('layouts.footer')
        </footer>
    </div>
    @include('profile.change_password')
    @include('profile.edit_profile')

</body>
<script src="{{ mix('js/app.js') }}" defer></script>
<!-- SWEETALERT2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<!-- BACK-TO-TOP -->
<a href="#top" id="back-to-top"><i class="fa fa-long-arrow-up"></i></a>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

<!--PUSHER NOTIFICATION -->
<script src={{ asset('views/js/notification-register/notificationPusher.js') }}></script>

<script src={{ asset('views/js/notification-register/accessToChannelCreation.js') }}></script>
<!-- JQUERY JS -->
<script src={{ asset('assets/js/jquery.min.js') }}></script>

<script src={{ asset('views/js/invoice/helpers.js') }}></script>

<!-- BOOTSTRAP JS -->
<script src={{ asset('assets/plugins/bootstrap/js/popper.min.js') }}></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

<!-- SIDE-MENU JS-->
<script src="{{ asset('assets/plugins/sidemenu/sidemenu.js') }}"></script>

<!-- APEXCHART JS -->
<script src="{{ asset('assets/js/apexcharts.js') }}"></script>

<!-- INTERNAL SELECT2 JS -->
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
@unless(View::hasSection('disableSelectPlugins'))
<script src="{{ asset('assets/thema/plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('assets/thema/plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
@endunless

{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}

<!-- DATA TABLE JS-->
<script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/buttons.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/js/table-data.js')}}"></script>

<!-- CUSTOM JS -->
<script src={{ asset('assets/js/custom.js') }}></script>

<!-- Form Validations js -->
<script src={{asset('assets/js/form-validation.js')}}></script>

<!-- CHART-CIRCLE JS-->
<script src={{ asset('assets/js/circle-progress.min.js') }}></script>

<!-- INDEX JS -->
<script src={{ asset('assets/js/index1.js') }}></script>

<!-- REPLY JS-->
<script src={{ asset('assets/js/reply.js') }}></script>

<script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>

<!-- INTERNAL Notifications js -->
{{-- <script src={{asset('assets/plugins/notify/js/rainbow.js')}}></script>
<script src={{asset('assets/plugins/notify/js/sample.js')}}></script>
<script src={{asset('assets/plugins/notify/js/jquery.growl.js')}}></script>
<script src={{asset('assets/plugins/notify/js/notifIt.js')}}></script> --}}


{{-- <script src="https://jsuites.net/v4/jsuites.js"></script> --}}
<!-- PERFECT SCROLLBAR JS-->
{{-- <script src={{ asset('assets/plugins/p-scroll/perfect-scrollbar.js') }}></script>
<script src={{ asset('assets/plugins/p-scroll/pscroll.js') }}></script> --}}

{{-- <script src={{ asset('assets/js/form-elements.js')}}></script> --}}
<!-- STICKY JS -->
<script src={{ asset('assets/js/sticky.js') }}></script>

<!-- COLOR THEME JS -->
<script src={{ asset('assets/js/themeColors.js') }}></script>
@can('/usuario.index')

<script>
    let response = `{{Auth::User()->rol->role_id}}`
    creationChannel(response);

    document.addEventListener('DOMContentLoaded', function() {
        // Obtener el elemento del enlace
        const enlace = document.getElementById('menu');

        // Simular el clic en el enlace
        enlace.click();
    });

</script>
@endcan

@yield('scripts')

</html>
