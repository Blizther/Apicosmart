@extends('administrador.inicio')
@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row">
        {{-- TARJETA: Total usuarios --}}
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Total usuarios</h5>
                </div>
                <div class="ibox-content">
                    <img src="{{ asset('img/usuario.png') }}" alt="Logo" style="width:60px; height:60px;">
                    <h1 class="no-margins">
                        {{ \App\Models\User::count() }}
                    </h1>
                </div>
            </div>
        </div>

        {{-- TARJETA: Total sensores --}}
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Total sensores</h5>
                </div>
                <div class="ibox-content">
                    <img src="{{ asset('img/sensorTemperatura.png') }}" alt="Logo" style="width:60px; height:60px;">
                    <h1 class="no-margins">
                        {{ \App\Models\Dispositivo::count() }}
                    </h1>
                </div>
            </div>
        </div>

        {{-- TARJETA: Total apiarios (global) --}}
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Total apiarios</h5>
                </div>
                <div class="ibox-content">
                    <img src="{{ asset('img/colmenar.png') }}" alt="Logo" style="width:60px; height:60px;">
                    <h1 class="no-margins">
                        {{ \App\Models\Apiario::count() }}
                    </h1>
                </div>
            </div>
        </div>


        
    </div>
</div>
@endsection

