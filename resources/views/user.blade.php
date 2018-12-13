<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="{{ asset('public/assets/images/icon/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/slicknav.min.css') }}">
    <!-- amchart css -->
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <!-- others css -->
    <link rel="stylesheet" href="{{ asset('public/assets/css/typography.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/default-css.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/responsive.css') }}">
    <!-- modernizr css -->
    <script src="{{ asset('public/assets/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    <script 
        src="https://code.jquery.com/jquery-3.3.1.js"
        integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous">
    </script>
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- preloader area start -->
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader area end -->

@yield('content')

    <!-- jquery latest version -->
    <script src="{{ asset('public/assets/js/vendor/jquery-2.2.4.min.js') }}"></script>
    <!-- bootstrap 4 js -->
    <script src="{{ asset('public/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/jquery.slicknav.min.js') }}"></script>

    <!-- start chart js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
    <!-- start highcharts js -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <!-- start zingchart js -->
    <script src="https://cdn.zingchart.com/zingchart.min.js"></script>
    <script>
    zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
    ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "ee6b7db5b51705a13dc2339db3edaf6d"];
    </script>
    <!-- all line chart activation -->
    <script src="{{ asset('public/assets/js/line-chart.js') }}"></script>
    <!-- all pie chart -->
    <script src="{{ asset('public/assets/js/pie-chart.js') }}"></script>
    <!-- others plugins -->
    <script src="{{ asset('public/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('public/assets/js/scripts.js') }}"></script>
</body>

</html>