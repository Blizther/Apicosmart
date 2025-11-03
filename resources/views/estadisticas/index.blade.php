@extends('usuario.inicio')

@section('content')
<div class="text-right mb-3">
    <button id="btnExportarPDF" class="btn btn-primary" disabled>
        <i class="fa fa-file-pdf-o"></i> Exportar a PDF
    </button>
</div>

<div class="wrapper wrapper-content animated fadeInRight" id="reporteContenido">
    <!-- 🟡 ENCABEZADO DEL REPORTE -->
    <div class="text-center mb-4">
        <img src="{{ asset('img/logoApicoSmart.jpg') }}" alt="Logo" style="height: 80px;">
        <h2 class="mt-2 mb-0">ApicoSmart</h2>
        <p class="mb-0">Cochabamba, Bolivia</p>
        <!--agregar datos del usuario autenticado-->
        <p class="mb-0">{{ Auth::user()->nombre }} {{ Auth::user()->primerApellido }}| Email: {{ Auth::user()->email }}</p>
        <hr>
        <h4>Reporte General de Apiarios</h4>
        <p>Fecha de emisión: {{ date('d/m/Y') }}</p>
    </div>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
$(document).ready(function() {

    let graficosCargados = 0;
    const totalGraficos = 4;

    function graficoListo() {
        graficosCargados++;
        if (graficosCargados === totalGraficos) {
            $('#btnExportarPDF').prop('disabled', false);
        }
    }

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
        graficoListo();
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
        graficoListo();
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
        graficoListo();
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
        graficoListo();
    });

    // 🧾 EXPORTAR A PDF
    $('#btnExportarPDF').click(function() {
        const { jsPDF } = window.jspdf;
        const contenido = document.getElementById('reporteContenido');

        // Quitar animaciones antes de capturar
        $('.wrapper').removeClass('animated fadeInRight');

        html2canvas(contenido, {
            scale: 1.5,
            useCORS: true,
            logging: false,
            windowWidth: contenido.scrollWidth,
            windowHeight: contenido.scrollHeight
        }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('p', 'mm', 'a4');
            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();

            const imgWidth = pageWidth - 20;
            const imgHeight = canvas.height * imgWidth / canvas.width;

            let heightLeft = imgHeight;
            let position = 10;

            pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);

            // Si el contenido excede una hoja, agregar más páginas
            while (heightLeft > pageHeight) {
                heightLeft -= pageHeight;
                position = -heightLeft + 10;
                pdf.addPage();
                pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            }

            pdf.save('reporte_apicola.pdf');
        });
    });

});
</script>
@endsection
