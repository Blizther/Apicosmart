<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('title', 'ApicoSmart')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/abeja.png') }}">

    <script src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <script src="{{asset('js/bootstrap.min.js')}}"></script> <!-- MODIFICADO -->

    <link href="{{asset('font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <!-- Toastr style -->
    <link href="{{asset('css/plugins/toastr/toastr.min.css')}}" rel="stylesheet">

    <!-- Gritter -->
    <link href="{{asset('js/plugins/gritter/jquery.gritter.css')}}" rel="stylesheet">
    <script src="{{asset('js/jquery-3.1.1.min.js')}}"></script> <!-- MODIFICADO -->
    <link href="{{asset('css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="{{ asset('css/custom-apicosmart.css') }}" rel="stylesheet">


    <style>
        /* ===== Sidebar ApicoSmart (solo nave izquierda) ===== */

        /* Contenedor lateral */
        .navbar-default.navbar-static-side {
            background: #3A4F26;
            /* verde */
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
        }

        /* Área colapsable (para que herede el fondo) */
        .navbar-static-side .sidebar-collapse {
            background: #3A4F26;
        }

        /* Header del perfil */
        #side-menu .nav-header {
            background: linear-gradient(180deg, #3A4F26 0%, #334522 100%);
            padding: 25px 20px 20px;
            border-bottom: 1px solid rgba(0, 0, 0, .35);
        }

        /* Foto redonda con aro amarillo */
        #side-menu .nav-header .img-circle {
            border: 3px solid #F9B233;
            /* amarillo */
            padding: 2px;
        }

        /* Nombre y rol */
        #side-menu .nav-header .font-bold,
        #side-menu .nav-header .text-muted {
            color: #EDD29C;
            /* beige */
        }

        #side-menu .nav-header .text-muted {
            opacity: .85;
        }

        /* Enlaces de primer nivel */
        #side-menu>li>a {
            color: #EDD29C;
            /* color del texto */
            font-size: 16px;
            background-color: transparent;
            /* más explícito */
            padding: 12px 20px;
            border-left: 4px solid transparent;
            /* borde inicial invisible */
            transition:
                background-color 0.18s ease,
                color 0.18s ease,
                border-color 0.18s ease,
                padding-left 0.18s ease;
            /* animación suave */
            display: block;
            /* hace que toda el área sea clicable */
            text-decoration: none;
            /* quita subrayado */
        }


        /* Iconos existentes (si los usas), que hereden el color */
        #side-menu>li>a>i {
            color: inherit;
            width: 18px;
            text-align: center;
            margin-right: 8px;
            opacity: .95;
            font-size: 25px;
        }

        /* Hover/Focus */
        #side-menu>li>a:hover,
        #side-menu>li>a:focus {
            background: rgba(0, 0, 0, .12);
            color: #FFFFFF;
            border-left-color: #F9B233;
            /* acento */
            padding-left: 24px;
            /* pequeño desplazamiento */
        }

        /* Activo (li .active lo pone metisMenu/inspinia) */
        #side-menu>li.active>a,
        #side-menu>li.mm-active>a {
            background: rgba(0, 0, 0, .22);
            color: #FFFFFF;
            border-left-color: #F9B233;
            font-weight: 600;
        }

        /* Flecha de submenu */
        #side-menu .fa.arrow {
            color: #EDD29C;
            transition: transform .18s ease, color .18s ease;
        }

        #side-menu li.mm-active>a .fa.arrow {
            transform: rotate(90deg);
            color: #F9B233;
        }

        /* Submenú */
        #side-menu .nav-second-level {
            background: #334522;
            /* un verde más oscuro */
            padding: 6px 0;
        }

        #side-menu .nav-second-level li a {
            color: #EAD7AA;
            /* beige suave */
            padding: 9px 20px 9px 44px;
            /* indentado */
            font-size: 13.5px;
            border-left: 4px solid transparent;
        }

        #side-menu .nav-second-level li a:hover {
            background: rgba(0, 0, 0, .14);
            color: #FFFFFF;
            border-left-color: #F9B233;
        }

        /* Separadores sutiles entre items */
        #side-menu>li {
            border-bottom: 1px solid rgba(0, 0, 0, .18);
        }

        #side-menu>li:last-child {
            border-bottom: none;
        }

        /* Badge/puntos que ya tengas: hazlos resaltar sin cambiar tu HTML */
        #side-menu .nav-label::after {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            margin-left: 8px;
            background: transparent;
            /* por defecto nada */
        }

        /* Ejemplo: resalta "Colmenas" y "Sensores" si ya los marcas con .has-alert en su <li> */
        #side-menu li.has-alert>a .nav-label::after {
            background: #F9B233;
            /* amarillo como alerta */
            box-shadow: 0 0 0 3px rgba(249, 178, 51, .15);
        }

        /* Dropdown del perfil */
        #side-menu .dropdown-menu {
            background: #2B3B1E;
            /* más oscuro */
            border: 1px solid rgba(0, 0, 0, .35);
        }

        #side-menu .dropdown-menu>li>a {
            color: #EAD7AA;
        }

        #side-menu .dropdown-menu>li>a:hover {
            background: rgba(0, 0, 0, .25);
            color: #FFF;
        }

        /* Scrollbar del sidebar (webkit) */
        .navbar-static-side ::-webkit-scrollbar {
            width: 10px;
        }

        .navbar-static-side ::-webkit-scrollbar-track {
            background: #2F421F;
        }

        .navbar-static-side ::-webkit-scrollbar-thumb {
            background: #445C2D;
            border: 2px solid #2F421F;
            border-radius: 8px;
        }

        .navbar-static-side ::-webkit-scrollbar-thumb:hover {
            background: #4E6734;
        }

        /* Estado "minimizado" de Inspinia (cuando clic en el botón hamburguesa) */
        .mini-navbar .navbar-static-side {
            width: 70px;
        }

        .mini-navbar #side-menu>li>a {
            padding-left: 18px;
            text-align: left;
        }

        .mini-navbar #side-menu>li>a .nav-label {
            display: none;
        }

        .mini-navbar #side-menu>li>a>i {
            margin-right: 0;
        }

        /* ===== Fix azul por defecto de Inspinia ===== */

        /* Item padre activo (p.ej. "Ventas") */
        .navbar-static-side #side-menu>li.active>a,
        .navbar-static-side #side-menu>li.mm-active>a {
            background: rgba(0, 0, 0, .22) !important;
            /* verde oscurecido */
            color: #FFFFFF !important;
            border-left-color: #F9B233 !important;
            /* acento amarillo */
        }

        /* Fondo del contenedor de submenú abierto */
        .navbar-static-side #side-menu .nav-second-level {
            background: #334522 !important;
            /* verde más oscuro */
        }

        /* Enlaces del submenú (quitar azul de hover/activo) */
        .navbar-static-side #side-menu .nav-second-level>li>a {
            background: transparent !important;
            color: #EAD7AA !important;
            /* beige */
            border-left: 4px solid transparent !important;
        }

        .navbar-static-side #side-menu .nav-second-level>li>a:hover,
        .navbar-static-side #side-menu .nav-second-level>li.active>a,
        .navbar-static-side #side-menu .nav-second-level>li.mm-active>a {
            background: rgba(0, 0, 0, .14) !important;
            color: #FFFFFF !important;
            border-left-color: #F9B233 !important;
        }

        /* Cualquier activo genérico dentro del sidebar que aún herede azul */
        .navbar-static-side .nav>li.active>a {
            background-color: rgba(0, 0, 0, .22) !important;
            color: #FFF !important;
        }

        /* ==== Mata-azul definitivo solo para el sidebar ==== */

        /* Fondo del contenedor lateral completo */
        .navbar-default.navbar-static-side,
        .navbar-default.navbar-static-side .sidebar-collapse,
        .navbar-default.navbar-static-side #side-menu,
        .navbar-default.navbar-static-side .nav.metismenu {
            background: #3A4F26 !important;
        }

        /* Item padre activo (ej. Ventas) */
        .navbar-default.navbar-static-side #side-menu>li.active>a,
        .navbar-default.navbar-static-side #side-menu>li.mm-active>a {
            background: rgba(0, 0, 0, .22) !important;
            color: #fff !important;
            border-left-color: #F9B233 !important;
        }

        /* Asegurar que el <li> activo no pinte azul por detrás */
        .navbar-default.navbar-static-side #side-menu>li.active,
        .navbar-default.navbar-static-side #side-menu>li.mm-active {
            background: transparent !important;
        }

        /* Submenú abierto (todos los estados de metismenu/Bootstrap) */
        .navbar-default.navbar-static-side #side-menu .nav-second-level,
        .navbar-default.navbar-static-side #side-menu .nav-second-level.collapse,
        .navbar-default.navbar-static-side #side-menu .nav-second-level.collapse.in,
        .navbar-default.navbar-static-side #side-menu .nav-second-level.mm-collapse,
        .navbar-default.navbar-static-side #side-menu .nav-second-level.mm-show {
            background: #334522 !important;
            border-left: 0 !important;
        }

        /* Enlaces del submenú: normal/hover/activo */
        .navbar-default.navbar-static-side #side-menu .nav-second-level>li>a {
            background: transparent !important;
            color: #EAD7AA !important;
            border-left: 4px solid transparent !important;
        }

        .navbar-default.navbar-static-side #side-menu .nav-second-level>li>a:hover,
        .navbar-default.navbar-static-side #side-menu .nav-second-level>li.active>a,
        .navbar-default.navbar-static-side #side-menu .nav-second-level>li.mm-active>a,
        .navbar-default.navbar-static-side #side-menu .nav-second-level>li>a:focus {
            background: rgba(0, 0, 0, .14) !important;
            color: #fff !important;
            border-left-color: #F9B233 !important;
        }

        /* Cualquier activo genérico dentro del sidebar */
        .navbar-default.navbar-static-side .nav>li.active>a,
        .navbar-default.navbar-static-side .nav>li>a.active {
            background: rgba(0, 0, 0, .22) !important;
            color: #fff !important;
        }

        /* Fondo de “espacios vacíos” bajo el último item */
        .navbar-default.navbar-static-side .nav {
            min-height: 100%;
            background: #3A4F26 !important;
        }

        /* === Banner de bienvenida ApicoSmart === */
        .welcome-banner {
            width: 100%;
            height: 120px;
            /* altura del banner */
            background-image: url("{{ asset('img/fondo2.png') }}");
            /* cambia por tu imagen */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .welcome-banner::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            /* oscurece un poco para mejor contraste del texto */
            z-index: 0;
        }

        .welcome-text {
            position: relative;
            z-index: 1;
            color: #3A4F26;
            font-size: 36px;
            /* tamaño del texto */
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 3px 3px 6px rgba(249, 178, 51, 0.6);
            font-family: "Segoe UI", Arial, sans-serif;
        }

        #map {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border: 2px solid #3A4F26;
        }
    </style>
</head>

<body>

    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <span>
                                <img alt="image" class="img-circle img-fluid" src="{{ asset('img/logoApicoSmart.jpg') }}" style="max-width: 70px;" />
                            </span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                                    <span class="block m-t-xs">
                                        <strong class="font-bold">{{ auth()->user()->nombre }}</strong>
                                    </span>
                                    <span class="text-muted text-xs block"><i class="fa fa-user"></i> {{ auth()->user()->rol }} <b class="caret"></b></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="profile.html"><i class="fa fa-id-card-o"></i> Profile</a></li>
                                <li><a href="contacts.html"><i class="fa fa-address-book-o"></i> Contacts</a></li>
                                <li><a href="mailbox.html"><i class="fa fa-envelope-o"></i> Mailbox</a></li>
                                <li class="divider"></li>
                            </ul>
                        </div>
                        <div class="logo-element"></div>
                    </li>

                    @if(auth()->user()->rol == 'usuario')
                    <li>
                        <a href="<?php echo asset('') ?>productos" title="Productos"><i class="fa fa-cubes"></i> <span class="nav-label"> Productos</span></a>
                    </li>

                    <li>
                        <a href="{{ route('apiario.index') }}" title="Apiarios"><i class="fa fa-pagelines"></i> <span class="nav-label"> Apiarios</span></a>
                    </li>
                    <li>
                        <a href="{{route('colmenas.index')}}" title="Colmenas"><i class="fa fa-archive"></i> <span class="nav-label"> Colmenas</span></a>
                    </li>
                    <li>
                        <a href="{{ route('tratamiento.index') }}" title="Tratamientos"><i class="fa fa-plus-circle"></i> <span class="nav-label"> Tratamientos</span></a>
                    </li>
                    <li>
                        <a href="{{route('cosechas.index')}}" title="Cosechas"><i class="fa fa-align-center"></i><span class="nav-label"> Cosecha</span></a>
                    </li>
                    <li>
                        <a href="{{ route('alimentacion.index') }}" title="Tratamientos"><i class="fa fa-coffee"></i> <span class="nav-label"> Alimentación</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-line-chart"></i> <span class="nav-label">Estadísticas</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="{{route('estadisticas.index')}}"><i class="fa fa-bar-chart"></i> General</a></li>
                            <li><a href="{{route('estadisticas.colmenas.index')}}"><i class="fa fa-pie-chart"></i> Por Colmena</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('mis.dispositivos') }}" title="Dispositivos"><i class="fa fa-thermometer-half"></i> <span class="nav-label">Dispositivos</span></a>
                    </li>
                    <li class="">
                        <a href="<?php echo asset('') ?>ventaUsuario" title="Realizar venta"><i class="fa fa-pencil-square-o"></i><span class="nav-label"> Realizar venta</span></a>
                    </li>
                    <li>
                        <a href="<?php echo asset('') ?>reporteUsuario" title="Reporte venta"><i class="fa fa-bar-chart"></i> <span class="nav-label">Reportes de venta</span></a>
                    </li>

                    @endif
                </ul>
            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#" title="Max/Min menú lateral"><i class="fa fa-bars"></i> </a>
                        <a class="minimalize-styl-only btn btn-success " href="{{ url('/usuario/inicio') }}" title="Ir a Inicio"><i class="fa fa-home"></i> </a>


                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li class="welcome-container">
                            <span class="welcome-text">Bienvenido a ApicoSmart</span>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-envelope"></i> <span class="label label-warning">16</span>
                            </a>
                            <ul class="dropdown-menu dropdown-messages">
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a href="profile.html" class="pull-left">
                                            <img alt="image" class="img-circle" src="img/a7.jpg">
                                        </a>

                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a href="profile.html" class="pull-left">
                                            <img alt="image" class="img-circle" src="img/a4.jpg">
                                        </a>

                                    </div>
                                </li>
                                <li class="divider"></li>

                                <li class="divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a href="mailbox.html">
                                            <i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-bell"></i> <span class="label label-primary">8</span>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts">
                                <li>
                                    <a href="mailbox.html">
                                        <div>
                                            <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                            <span class="pull-right text-muted small">4 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="profile.html">
                                        <div>
                                            <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                            <span class="pull-right text-muted small">12 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="grid_options.html">
                                        <div>
                                            <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                            <span class="pull-right text-muted small">4 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a href="notifications.html">
                                            <strong>See All Alerts</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>


                        <li>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out"></i> SALIR
                            </a>

                            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                                @csrf
                            </form>
                        </li>
                        <li>
                            <a class="right-sidebar-toggle">
                                <i class="fa fa-tasks"></i>
                            </a>
                        </li>
                    </ul>

                </nav>
            </div>
            <div>
                @yield('content')
            </div>

        </div>
    </div>
    </div>
    </div>

    <!-- Mainly scripts -->
    <script src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
    <script src="{{asset('js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

    <!-- Flot -->
    <script src="{{asset('js/plugins/flot/jquery.flot.js')}}"></script>
    <script src="{{asset('js/plugins/flot/jquery.flot.tooltip.min.js')}}"></script>
    <script src="{{asset('js/plugins/flot/jquery.flot.spline.js')}}"></script>
    <script src="{{asset('js/plugins/flot/jquery.flot.resize.js')}}"></script>
    <script src="{{asset('js/plugins/flot/jquery.flot.pie.js')}}"></script>

    <!-- Peity -->
    <script src="{{asset('js/plugins/peity/jquery.peity.min.js')}}"></script>
    <script src="{{asset('js/demo/peity-demo.js')}}"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{asset('js/inspinia.js')}}"></script>
    <script src="{{asset('js/plugins/pace/pace.min.js')}}"></script>

    <!-- jQuery UI -->
    <script src="{{asset('js/plugins/jquery-ui/jquery-ui.min.js')}}"></script>

    <!-- GITTER -->
    <script src="{{asset('js/plugins/gritter/jquery.gritter.min.js')}}"></script>

    <!-- Sparkline -->
    <script src="{{asset('js/plugins/sparkline/jquery.sparkline.min.js')}}"></script>

    <!-- Sparkline demo data  -->
    <script src="{{asset('js/demo/sparkline-demo.js')}}"></script>

    <!-- ChartJS-->
    <script src="{{asset('js/plugins/chartJs/Chart.min.js')}}"></script>

    <!-- Toastr -->
    <script src="{{asset('js/plugins/toastr/toastr.min.js')}}"></script>


    <script>
        $(document).ready(function() {
            setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 4000
                };
                toastr.success('Controla tu colmena, mejora tu miel.', 'Bienvenido a ApicoSmart!');

            }, 1300);


            var data1 = [
                [0, 4],
                [1, 8],
                [2, 5],
                [3, 10],
                [4, 4],
                [5, 16],
                [6, 5],
                [7, 11],
                [8, 6],
                [9, 11],
                [10, 30],
                [11, 10],
                [12, 13],
                [13, 4],
                [14, 3],
                [15, 3],
                [16, 6]
            ];
            var data2 = [
                [0, 1],
                [1, 0],
                [2, 2],
                [3, 0],
                [4, 1],
                [5, 3],
                [6, 1],
                [7, 5],
                [8, 2],
                [9, 3],
                [10, 2],
                [11, 1],
                [12, 0],
                [13, 2],
                [14, 8],
                [15, 0],
                [16, 0]
            ];
            $("#flot-dashboard-chart").length && $.plot($("#flot-dashboard-chart"), [
                data1, data2
            ], {
                series: {
                    lines: {
                        show: false,
                        fill: true
                    },
                    splines: {
                        show: true,
                        tension: 0.4,
                        lineWidth: 1,
                        fill: 0.4
                    },
                    points: {
                        radius: 0,
                        show: true
                    },
                    shadowSize: 2
                },
                grid: {
                    hoverable: true,
                    clickable: true,
                    tickColor: "#d5d5d5",
                    borderWidth: 1,
                    color: '#d5d5d5'
                },
                colors: ["#1ab394", "#1C84C6"],
                xaxis: {},
                yaxis: {
                    ticks: 4
                },
                tooltip: false
            });

            var doughnutData = {
                labels: ["App", "Software", "Laptop"],
                datasets: [{
                    data: [300, 50, 100],
                    backgroundColor: ["#a3e1d4", "#dedede", "#9CC3DA"]
                }]
            };


            var doughnutOptions = {
                responsive: false,
                legend: {
                    display: false
                }
            };


            var ctx4 = document.getElementById("doughnutChart").getContext("2d");
            new Chart(ctx4, {
                type: 'doughnut',
                data: doughnutData,
                options: doughnutOptions
            });

            var doughnutData = {
                labels: ["App", "Software", "Laptop"],
                datasets: [{
                    data: [70, 27, 85],
                    backgroundColor: ["#a3e1d4", "#dedede", "#9CC3DA"]
                }]
            };


            var doughnutOptions = {
                responsive: false,
                legend: {
                    display: false
                }
            };


            var ctx4 = document.getElementById("doughnutChart2").getContext("2d");
            new Chart(ctx4, {
                type: 'doughnut',
                data: doughnutData,
                options: doughnutOptions
            });

        });
    </script>
    <script>
        (function(i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function() {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '../../www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-4625583-2', 'webapplayers.com');
        ga('send', 'pageview');
    </script>
    @yield('scripts')
</body>

<!-- Mirrored from webapplayers.com/inspinia_admin-v2.7.1/ by HTTrack Website Copier/3.x [XR&CO'2010], Wed, 02 Aug 2017 03:34:55 GMT -->

</html>