@extends('usuario.inicio')

@section('content')
<div class="text-right mb-3">
    <button id="btnExportarPDF" class="btn btn-primary" disabled>
        <i class="fa fa-file-pdf-o"></i> Exportar a PDF
    </button>
</div>

<!-- FILTROS -->
<div class="card p-3 mb-4" id="filtros">
    <div class="row">
        <div class="col-md-4">
            <label>Seleccione Apiario</label>
            <select id="apiarioSelect" class="form-control">
                <option value="">-- Todos --</option>
                @foreach($apiarios as $apiario)
                    <option value="{{ $apiario->idApiario }}">{{ $apiario->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label>Desde</label>
            <input
                type="date"
                id="fechaInicio"
                class="form-control"
                max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
            >
        </div>

        <div class="col-md-3">
            <label>Hasta</label>
            <input
                type="date"
                id="fechaFin"
                class="form-control"
                max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
            >
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button id="btnAplicarFiltro" class="btn btn-success w-100">Aplicar Filtros</button>
        </div>
    </div>
</div>

<!-- CONTENIDO DEL REPORTE -->
<div class="wrapper wrapper-content animated fadeInRight" id="reporteContenido">
    <div class="text-center mb-4">
        <img src="{{ asset('img/logoApicoSmart.jpg') }}" alt="Logo" style="height: 80px;">
        <h2 class="mt-2 mb-0">ApicoSmart</h2>
        <p class="mb-0">Cochabamba, Bolivia</p>
        <p class="mb-0">
            {{ Auth::user()->nombre }} {{ Auth::user()->primerApellido }}
            | Email: {{ Auth::user()->email }}
        </p>
        <hr>
        <h4>Reporte Estadístico por Colmenas</h4>
        <span id="nombreApiarioSeleccionado" style="font-weight: normal; font-size: 0.9em;"></span>
        <p>Fecha de emisión: {{ date('d/m/Y') }}</p>
    </div>

    <div class="row">
        <!-- Inspecciones -->
        <div class="col-lg-6">
            <div class="ibox">
                <div class="ibox-title"><h5>Cantidad de Inspecciones por Colmena</h5></div>
                <div class="ibox-content">
                    <canvas id="graficoInspecciones"></canvas>
                </div>
            </div>
        </div>
        <!-- Alimentaciones -->
        <div class="col-lg-6">
            <div class="ibox">
                <div class="ibox-title"><h5>Cantidad de Alimentaciones por Colmena</h5></div>
                <div class="ibox-content">
                    <canvas id="graficoAlimentaciones"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Tratamientos -->
        <div class="col-lg-6">
            <div class="ibox">
                <div class="ibox-title"><h5>Cantidad de Tratamientos por Colmena</h5></div>
                <div class="ibox-content">
                    <canvas id="graficoTratamientos"></canvas>
                </div>
            </div>
        </div>
        <!-- Cosecha -->
        <div class="col-lg-6">
            <div class="ibox">
                <div class="ibox-title"><h5>Porcentaje y Total de Cosecha por Colmena</h5></div>
                <div class="ibox-content">
                    <canvas id="graficoCosecha"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>

// Validar rango de fechas
function validarFechas() {
    const inicio = $('#fechaInicio').val();
    const fin = $('#fechaFin').val();

    // Sin fechas: se permite
    if (!inicio && !fin) {
        return true;
    }

    // Solo una de las dos: se permite (filtro abierto)
    if (inicio && !fin) return true;
    if (!inicio && fin) return true;

    // Ambas fechas: validar que inicio <= fin
    if (inicio > fin) {
        alert('La fecha "Desde" no puede ser mayor que la fecha "Hasta".');
        return false;
    }

    return true;
}

$(document).ready(function() {

    const totalGraficos = 4;
    let graficosCargados = 0;
    const charts = {};

    function graficoListo() {
        graficosCargados++;
        if (graficosCargados === totalGraficos) {
            $('#btnExportarPDF').prop('disabled', false);
        }
    }

    function cargarGraficos() {
        graficosCargados = 0;
        $('#btnExportarPDF').prop('disabled', true); // mientras se cargan

        const apiarioId = $('#apiarioSelect').val();
        const inicio = $('#fechaInicio').val();
        const fin = $('#fechaFin').val();

        // Nombre del apiario
        let nombreApiario = $('#apiarioSelect option:selected').text();
        if (nombreApiario === '-- Todos --') nombreApiario = 'Todos los Apiarios';

        // Formatear fechas (dd/mm/yyyy)
        let fechaTexto = '';
        if (inicio && fin) {
            const fechaDesde = new Date(inicio);
            const fechaHasta = new Date(fin);
            fechaTexto = ` | ${fechaDesde.getDate().toString().padStart(2,'0')}/` +
                         `${(fechaDesde.getMonth()+1).toString().padStart(2,'0')}/` +
                         `${fechaDesde.getFullYear()} a ` +
                         `${fechaHasta.getDate().toString().padStart(2,'0')}/` +
                         `${(fechaHasta.getMonth()+1).toString().padStart(2,'0')}/` +
                         `${fechaHasta.getFullYear()}`;
        }

        // Actualizar el título con apiario y fechas
        $('#nombreApiarioSeleccionado').text(`- ${nombreApiario}${fechaTexto}`);

        const params = { apiario: apiarioId, desde: inicio, hasta: fin };

        // Inspecciones
        $.get("{{ route('estadisticas.colmenas.inspecciones') }}", params, function(data) {
            charts.inspecciones = new Chart($('#graficoInspecciones'), {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Inspecciones',
                        data: data.values,
                        backgroundColor: '#F9B233'
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } }
                }
            });
            graficoListo();
        });

        // Cosecha
        $.get("{{ route('estadisticas.colmenas.cosecha') }}", params, function(data) {
            charts.cosecha = new Chart($('#graficoCosecha'), {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Cosecha (kg)',
                        data: data.values,
                        backgroundColor: ['#3A4F26','#5C7F38','#A0C13F','#CBE86B']
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } }
                }
            });
            graficoListo();
        });

        // Tratamientos
        $.get("{{ route('estadisticas.colmenas.tratamientos') }}", params, function(data) {
            charts.tratamientos = new Chart($('#graficoTratamientos'), {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Tratamientos',
                        data: data.values,
                        backgroundColor: '#9CC3DA'
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } }
                }
            });
            graficoListo();
        });

        // Alimentaciones
        $.get("{{ route('estadisticas.colmenas.alimentaciones') }}", params, function(data) {
            charts.alimentaciones = new Chart($('#graficoAlimentaciones'), {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Alimentaciones',
                        data: data.values,
                        backgroundColor: '#A5668B'
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } }
                }
            });
            graficoListo();
        });
    }

    // Evento aplicar filtro
    $('#btnAplicarFiltro').click(function() {

        // Validar fechas antes de recargar gráficos
        if (!validarFechas()) {
            return;
        }

        // Destruir gráficos existentes si ya fueron creados
        for (let key in charts) {
            if (charts[key]) {
                charts[key].destroy();
            }
        }

        cargarGraficos();
    });

    // Carga inicial
    cargarGraficos();

    // Exportar PDF
    $('#btnExportarPDF').click(function() {
        const { jsPDF } = window.jspdf;
        const contenido = document.getElementById('reporteContenido');

        $('#filtros').hide(); // No incluir filtros
        $('.wrapper').removeClass('animated fadeInRight');

        html2canvas(contenido, {
            scale: 1.5,
            useCORS: true,
        }).then(canvas => {
            const pdf = new jsPDF('p', 'mm', 'a4');
            const imgData = canvas.toDataURL('image/png');
            const imgWidth = 190;
            const imgHeight = canvas.height * imgWidth / canvas.width;
            let position = 10;
            let heightLeft = imgHeight;

            pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);

            while (heightLeft > pdf.internal.pageSize.getHeight()) {
                heightLeft -= pdf.internal.pageSize.getHeight();
                position = -heightLeft + 10;
                pdf.addPage();
                pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            }

            pdf.save('reporte_colmenas.pdf');
            $('#filtros').show();
        });
    });

});
</script>
@endsection
