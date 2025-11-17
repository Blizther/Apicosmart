<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('title', 'Sistema de Sensores')</title>

    <link rel="icon" type="image/png" href="{{ asset('img/abeja.png') }}">

    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <link href="{{asset('css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">

    <style>
        /* ===== Sidebar ApicoSmart (solo nave izquierda) ===== */

        .navbar-default.navbar-static-side {
            background: #3A4F26;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
        }

        .navbar-static-side .sidebar-collapse {
            background: #3A4F26;
        }

        #side-menu .nav-header {
            background: linear-gradient(180deg, #3A4F26 0%, #334522 100%);
            padding: 25px 20px 20px;
            border-bottom: 1px solid rgba(0, 0, 0, .35);
        }

        #side-menu .nav-header .img-circle {
            border: 3px solid #F9B233;
            padding: 2px;
        }

        #side-menu .nav-header .font-bold,
        #side-menu .nav-header .text-muted {
            color: #EDD29C;
        }

        #side-menu .nav-header .text-muted {
            opacity: .85;
        }

        #side-menu>li>a {
            color: #EDD29C;
            font-size: 16px;
            background-color: transparent;
            padding: 12px 20px;
            border-left: 4px solid transparent;
            transition:
                background-color 0.18s ease,
                color 0.18s ease,
                border-color 0.18s ease,
                padding-left 0.18s ease;
            display: block;
            text-decoration: none;
        }

        #side-menu>li>a>i {
            color: inherit;
            width: 18px;
            text-align: center;
            margin-right: 8px;
            opacity: .95;
            font-size: 25px;
        }

        #side-menu>li>a:hover,
        #side-menu>li>a:focus {
            background: rgba(0, 0, 0, .12);
            color: #FFFFFF;
            border-left-color: #F9B233;
            padding-left: 24px;
        }

        #side-menu>li.active>a,
        #side-menu>li.mm-active>a {
            background: rgba(0, 0, 0, .22);
            color: #FFFFFF;
            border-left-color: #F9B233;
            font-weight: 600;
        }

        #side-menu .fa.arrow {
            color: #EDD29C;
            transition: transform .18s ease, color .18s ease;
        }

        #side-menu li.mm-active>a .fa.arrow {
            transform: rotate(90deg);
            color: #F9B233;
        }

        #side-menu .nav-second-level {
            background: #334522;
            padding: 6px 0;
        }

        #side-menu .nav-second-level li a {
            color: #EAD7AA;
            padding: 9px 20px 9px 44px;
            font-size: 13.5px;
            border-left: 4px solid transparent;
        }

        #side-menu .nav-second-level li a:hover {
            background: rgba(0, 0, 0, .14);
            color: #FFFFFF;
            border-left-color: #F9B233;
        }

        #side-menu>li {
            border-bottom: 1px solid rgba(0, 0, 0, .18);
        }

        #side-menu>li:last-child {
            border-bottom: none;
        }

        #side-menu .nav-label::after {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            margin-left: 8px;
            background: transparent;
        }

        #side-menu li.has-alert>a .nav-label::after {
            background: #F9B233;
            box-shadow: 0 0 0 3px rgba(249, 178, 51, .15);
        }

        #side-menu .dropdown-menu {
            background: #2B3B1E;
            border: 1px solid rgba(0, 0, 0, .35);
        }

        #side-menu .dropdown-menu>li>a {
            color: #EAD7AA;
        }

        #side-menu .dropdown-menu>li>a:hover {
            background: rgba(0, 0, 0, .25);
            color: #FFF;
        }

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

        .navbar-static-side #side-menu>li.active>a,
        .navbar-static-side #side-menu>li.mm-active>a {
            background: rgba(0, 0, 0, .22) !important;
            color: #FFFFFF !important;
            border-left-color: #F9B233 !important;
        }

        .navbar-static-side #side-menu .nav-second-level {
            background: #334522 !important;
        }

        .navbar-static-side #side-menu .nav-second-level>li>a {
            background: transparent !important;
            color: #EAD7AA !important;
            border-left: 4px solid transparent !important;
        }

        .navbar-static-side #side-menu .nav-second-level>li>a:hover,
        .navbar-static-side #side-menu .nav-second-level>li.active>a,
        .navbar-static-side #side-menu .nav-second-level>li.mm-active>a {
            background: rgba(0, 0, 0, .14) !important;
            color: #FFFFFF !important;
            border-left-color: #F9B233 !important;
        }

        .navbar-static-side .nav>li.active>a {
            background-color: rgba(0, 0, 0, .22) !important;
            color: #FFF !important;
        }

        .navbar-default.navbar-static-side,
        .navbar-default.navbar-static-side .sidebar-collapse,
        .navbar-default.navbar-static-side #side-menu,
        .navbar-default.navbar-static-side .nav.metismenu {
            background: #3A4F26 !important;
        }

        .navbar-default.navbar-static-side #side-menu>li.active>a,
        .navbar-default.navbar-static-side #side-menu>li.mm-active>a {
            background: rgba(0, 0, 0, .22) !important;
            color: #fff !important;
            border-left-color: #F9B233 !important;
        }

        .navbar-default.navbar-static-side #side-menu>li.active,
        .navbar-default.navbar-static-side #side-menu>li.mm-active {
            background: transparent !important;
        }

        .navbar-default.navbar-static-side #side-menu .nav-second-level,
        .navbar-default.navbar-static-side #side-menu .nav-second-level.collapse,
        .navbar-default.navbar-static-side #side-menu .nav-second-level.collapse.in,
        .navbar-default.navbar-static-side #side-menu .nav-second-level.mm-collapse,
        .navbar-default.navbar-static-side #side-menu .nav-second-level.mm-show {
            background: #334522 !important;
            border-left: 0 !important;
        }

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

        .navbar-default.navbar-static-side .nav>li.active>a,
        .navbar-default.navbar-static-side .nav>li>a.active {
            background: rgba(0, 0, 0, .22) !important;
            color: #fff !important;
        }

        .navbar-default.navbar-static-side .nav {
            min-height: 100%;
            background: #3A4F26 !important;
        }

        .welcome-banner {
            width: 100%;
            height: 120px;
            background-image: url("{{ asset('img/fondo2.png') }}");
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
            z-index: 0;
        }

        .welcome-text {
            position: relative;
            z-index: 1;
            color: #3A4F26;
            font-size: 36px;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 3px 3px 6px rgba(249, 178, 51, 0.6);
            font-family: "Segoe UI", Arial, sans-serif;
        }

        #map {
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            border: 2px solid #3A4F26;
        }
        /* Centrar el texto "Bienvenido a ApicoSmart" en la barra superior */
        .navbar.navbar-static-top {
            position: relative;
        }

        .navbar-top-links .welcome-container {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
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
                                <span class="text-muted text-xs block">{{ auth()->user()->rol }} <b class="caret"></b></span>
                            </span>
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a href="profile.html">Profile</a></li>
                            <li><a href="contacts.html">Contacts</a></li>
                            <li><a href="mailbox.html">Mailbox</a></li>
                            <li class="divider"></li>
                        </ul>
                    </div>
                    <div class="logo-element"></div>
                </li>

               {{-- ======== MÓDULOS PARA USUARIO Y COLABORADOR ======== --}}

                {{-- PRODUCTOS: solo para usuario --}}
                @if(auth()->user()->rol == 'usuario')
                <li>
                    <a href="{{ url('productos') }}" title="Productos">
                        <i class="fa fa-cubes"></i> <span class="nav-label">Productos</span>
                    </a>
                </li>
                @endif

                {{-- APIARIOS: usuario y colaborador --}}
                @if(auth()->user()->rol == 'usuario' || auth()->user()->rol == 'colaborador')
                <li>
                    <a href="{{ route('apiario.index') }}" title="Apiarios">
                        <i class="fa fa-pagelines"></i> <span class="nav-label">Apiarios</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('colmenas.index') }}" title="Colmenas">
                        <i class="fa fa-archive"></i> <span class="nav-label">Colmenas</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('cosechas.index') }}" title="Cosechas">
                        <i class="fa fa-align-center"></i> <span class="nav-label">Cosechas</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('tratamiento.index') }}" title="Tratamientos">
                        <i class="fa fa-plus-circle"></i> <span class="nav-label">Tratamientos</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('alimentacion.index') }}" title="Alimentación">
                        <i class="fa fa-tint"></i> <span class="nav-label">Alimentación</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('tarea.index') }}" title="Tareas pendientes">
                        <i class="fa fa-check-square-o"></i> <span class="nav-label">Tareas pendientes</span>
                    </a>
                </li>

                <li>
                    <a href="#"><i class="fa fa-line-chart"></i> 
                        <span class="nav-label">Estadísticas</span> <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{route('estadisticas.index')}}"><i class="fa fa-bar-chart"></i> General</a></li>
                        <li><a href="{{route('estadisticas.colmenas.index')}}"><i class="fa fa-pie-chart"></i> Por Colmena</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('mis.dispositivos') }}" title="Dispositivos">
                        <i class="fa fa-thermometer-half"></i> <span class="nav-label">Dispositivos</span>
                    </a>
                </li>
                @endif


                {{-- ======== MÓDULOS SOLO PARA USUARIO (NO COLABORADOR) ======== --}}
                @if(auth()->user()->rol == 'usuario')
                <li>
                    <a href="{{ url('ventaUsuario') }}" title="Realizar venta">
                        <i class="fa fa-pencil-square-o"></i><span class="nav-label">Realizar venta</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('reporteUsuario') }}" title="Reporte venta">
                        <i class="fa fa-bar-chart"></i> <span class="nav-label">Reportes de venta</span>
                    </a>
                </li>
                @endif


                {{-- ADMINISTRAR USUARIOS: solo admin o usuario --}}
                @if(auth()->user()->rol === 'administrador' || auth()->user()->rol === 'usuario')
                <li>
                    <a href="{{ route('users.index') }}">
                        <i class="fa fa-user"></i> <span class="nav-label">Administrar Usuarios</span>
                    </a>
                </li>
                @endif

                {{-- DISPOSITIVOS FABRICADOS: solo administrador --}}
                @if(auth()->user()->rol === 'administrador')
                <li>
                    <a href="{{ route('fabricados.index') }}">
                        <i class="fa fa-cogs"></i> <span class="nav-label">Ver dispositivos fabricados</span>
                    </a>
                </li>
                @endif

            </ul>
        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <!-- botón hamburguesa (verde) -->
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="#">
                        <i class="fa fa-bars"></i>
                    </a>

                    <!-- botón casita (amarillo) que lleva al dashboard admin -->
                    <a class="minimalize-styl-only btn btn-success" href="{{ url('/administrador/inicio') }}">
                        <i class="fa fa-home"></i>
                    </a>
                </div>

                <ul class="nav navbar-top-links navbar-right">
                    <li class="welcome-container">
                        <span class="welcome-text">Bienvenido a ApicoSmart</span>
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


<script src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
<script src="{{asset('js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

<script src="{{asset('js/inspinia.js')}}"></script>
<script src="{{asset('js/plugins/pace/pace.min.js')}}"></script>

<script src="{{asset('js/plugins/jquery-ui/jquery-ui.min.js')}}"></script>

@yield('scripts')
</body>
</html>
