@extends('usuario.inicio')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">

        <!-- 🟠 Cantidad de Colmenas por Apiario -->
        <div class="col-lg-6">
            <div class="ibox">
                <div class="ibox-title"><h5>Cantidad de Colmenas por Apiario</h5></div>
                <div class="ibox-content">
                    <canvas id="graficoColmenas"></canvas>
                </div>
            </div>
        </div>

        <!-- 🟢 Peso Total de Cosecha por Apiario -->
        <div class="col-lg-6">
            <div class="ibox">
                <div class="ibox-title"><h5>Peso Total de Cosecha por Apiario (kg)</h5></div>
                <div class="ibox-content">
                    <canvas id="graficoCosecha"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- 🔵 Cantidad de Tratamientos por Apiario -->
        <div class="col-lg-6">
            <div class="ibox">
                <div class="ibox-title"><h5>Cantidad de Tratamientos por Apiario</h5></div>
                <div class="ibox-content">
                    <canvas id="graficoTratamientos"></canvas>
                </div>
            </div>
        </div>

        <!-- 🟣 Cantidad de Alimentaciones por Apiario -->
        <div class="col-lg-6">
            <div class="ibox">
                <div class="ibox-title"><h5>Cantidad de Alimentaciones por Apiario</h5></div>
                <div class="ibox-content">
                    <canvas id="graficoAlimentaciones"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {

    // 🟠 Colmenas por apiario
    $.get("{{ route('estadisticas.colmenas') }}", function(data) {
        new Chart(document.getElementById('graficoColmenas'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Cantidad de Colmenas',
                    data: data.values,
                    backgroundColor: '#F9B233'
                }]
            },
            options: {responsive: true, scales: {y: {beginAtZero: true}}}
        });
    });

    // 🟢 Peso de cosecha por apiario
    $.get("{{ route('estadisticas.cosecha') }}", function(data) {
        new Chart(document.getElementById('graficoCosecha'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Peso Total (kg)',
                    data: data.values,
                    backgroundColor: '#3A4F26'
                }]
            },
            options: {responsive: true, scales: {y: {beginAtZero: true}}}
        });
    });

    // 🔵 Tratamientos por apiario
    $.get("{{ route('estadisticas.tratamientos') }}", function(data) {
        new Chart(document.getElementById('graficoTratamientos'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Cantidad de Tratamientos',
                    data: data.values,
                    backgroundColor: '#9CC3DA'
                }]
            },
            options: {responsive: true, scales: {y: {beginAtZero: true}}}
        });
    });

    // 🟣 Alimentaciones por apiario
    $.get("{{ route('estadisticas.alimentaciones') }}", function(data) {
        new Chart(document.getElementById('graficoAlimentaciones'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Cantidad de Alimentaciones',
                    data: data.values,
                    backgroundColor: '#A5668B'
                }]
            },
            options: {responsive: true, scales: {y: {beginAtZero: true}}}
        });
    });

});
</script>
@endsection