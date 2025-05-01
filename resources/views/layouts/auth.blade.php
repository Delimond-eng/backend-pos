<!DOCTYPE html>
<html lang="en" dir="ltr" style="--primary-rgb: 14, 107, 230;" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>

    <!-- META DATA eta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="RH Management application">
    <meta name="Author" content="Dev.Gaston Delimond">
    <meta name="keywords" content="IT Developer, Freelance developer, ">

    <!-- TITLE -->
    <title> Zando Print </title>

    <!-- FAVICON -->
    <link rel="icon" href="{{asset('assets/images/logos/logo.ico')}}" type="image/x-icon">

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{asset('assets/libs/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- ICONS CSS -->
    <link href="{{asset('assets/icon-fonts/icons.css')}}" rel="stylesheet">

    <!-- APP SCSS -->
    <link rel="preload" as="style" href="{{asset('assets/css/app.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/app.css')}}" />

    <!-- MAIN JS -->
    <script src="{{asset('assets/authentication-main.js')}}"></script>




</head>

<body>



    @yield('content')



    <!-- SCRIPTS -->

    <!-- BOOTSTRAP JS -->
    <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- SECTION SCRIPTS -->
    @yield('scripts')
    <!-- END SECTION SCRIPTS -->
    <!-- END SCRIPTS -->

</body>


</html>
