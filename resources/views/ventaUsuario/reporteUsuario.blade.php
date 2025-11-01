@extends('usuario.inicio')

@section('content')
<div class="text-right mb-3">
    <button id="btnExportarPDF" class="btn btn-primary" disabled>
        <i class="fa fa-file-pdf-o"></i> Exportar a PDF
    </button>
</div>

<div class="container-fluid pt-4 px-4" id="reporteVentasContenido">

    {{-- Encabezado con logo y usuario --}}
    <div class="text-center mb-4">
        
        <h4>Reporte de Ventas</h4>
        <p>Fecha de emisión: {{ date('d/m/Y') }}</p>
    </div>

    {{-- Mensajes --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0;">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif

    {{-- Filtros --}}
    <div class="bg-light rounded p-3 mb-3">
        <h5 class="mb-3">Filtros</h5>
        <form method="GET" action="{{ url()->current() }}" class="row g-3">
            <div class="col-sm-3">
                <label class="form-label">Desde</label>
                <input type="date" name="from" value="{{ $from }}" class="form-control form-control-sm">
            </div>
            <div class="col-sm-3">
                <label class="form-label">Hasta</label>
                <input type="date" name="to" value="{{ $to }}" class="form-control form-control-sm">
            </div>
            <div class="col-sm-3 d-flex align-items-end">
                <button class="btn btn-primary btn-sm" type="submit">Aplicar filtros</button>
            </div>
            <div class="col-sm-3 d-flex align-items-end">
                <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm">Limpiar</a>
            </div>
        </form>
    </div>

    {{-- Resumen --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="bg-light rounded p-3">
                <div class="d-flex justify-content-between">
                    <span>Ventas</span>
                    <strong>{{ number_format($resumen['cantidadVentas']) }}</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-light rounded p-3">
                <div class="d-flex justify-content-between">
                    <span>Total vendido</span>
                    <strong>Bs. {{ number_format($resumen['totalVendido'], 2) }}</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-light rounded p-3">
                <div class="d-flex justify-content-between">
                    <span>Ítems vendidos</span>
                    <strong>{{ number_format($resumen['itemsVendidos']) }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de ventas --}}
    <div class="bg-light rounded p-3">
        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th class="text-end">Total (Bs.)</th>
                        <th class="text-center">Estado</th>
                        <th class="text-end">Ítems</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $v)
                        @php
                            $itemsCount = $v->detalles?->sum('cantidad') ?? 0;
                            $badge = ($v->estado == 1) ? 'success' : 'secondary';
                            $estadoTxt = ($v->estado == 1) ? 'Confirmada' : 'Anulada';
                        @endphp
                        <tr>
                            <td>{{ $v->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($v->fecha)->format('Y-m-d H:i') }}</td>
                            <td class="text-end">{{ number_format($v->total, 2) }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $badge }}">{{ $estadoTxt }}</span>
                            </td>
                            <td class="text-end">{{ $itemsCount }}</td>
                            <td class="text-center">
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('venta.reporte.detalle', $v->id) }}">
                                    Ver detalle
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No hay ventas en el período seleccionado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
$(document).ready(function() {
    $('#btnExportarPDF').prop('disabled', false);

    $('#btnExportarPDF').click(async function() {
        const { jsPDF } = window.jspdf;
        const contenido = document.getElementById('reporteVentasContenido');

        // 🔹 Ocultar elementos no deseados antes de capturar
        const btnsFiltros = $('button[type="submit"], a.btn-outline-secondary');
        const colAcciones = $('th:contains("Acciones"), td:nth-child(6)');
        btnsFiltros.hide();
        colAcciones.hide();

        try {
            // Captura la vista actual
            const canvas = await html2canvas(contenido, {
                scale: 1.5,
                useCORS: true,
                logging: false,
                windowWidth: contenido.scrollWidth,
                windowHeight: contenido.scrollHeight
            });

            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('p', 'mm', 'a4');
            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();

            const imgWidth = pageWidth - 20;
            const imgHeight = canvas.height * imgWidth / canvas.width;

            let heightLeft = imgHeight;
            let position = 30; // espacio para encabezado

            // Cargar logo
            const logo = new Image();
            logo.src = '{{ asset("img/logoApicoSmart.jpg") }}';
            await new Promise(resolve => { logo.onload = resolve; });

            // 🔹 Función para agregar encabezado en cada página
            function agregarEncabezado(paginaActual, totalPaginas) {
                pdf.addImage(logo, 'JPEG', 10, 5, 20, 20);
                pdf.setFontSize(12);
                pdf.text("ApicoSmart", 35, 12);
                pdf.setFontSize(9);
                pdf.text("{{ auth()->user()->nombre }} {{ explode(' ', auth()->user()->primerApellido)[0] }} | Tel: {{ auth()->user()->telefono }}", 35, 17);
                pdf.text("Fecha: {{ date('d/m/Y') }}", 35, 22);

                pdf.setFontSize(8);
                pdf.text(`Página ${paginaActual} de ${totalPaginas}`, pageWidth - 35, 22);
                pdf.line(10, 25, pageWidth - 10, 25); // línea separadora
            }

            // 🔹 Agregar primera página con espacio para encabezado
            pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);

            // Ver si el contenido excede una hoja
            let paginas = 1;
            while (heightLeft > pageHeight) {
                heightLeft -= pageHeight;
                position = -heightLeft + 30;
                pdf.addPage();
                paginas++;
                pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            }

            // 🔹 Encabezado en cada página
            const totalPaginas = pdf.internal.getNumberOfPages();
            for (let i = 1; i <= totalPaginas; i++) {
                pdf.setPage(i);
                agregarEncabezado(i, totalPaginas);
            }

            pdf.save('reporte_ventas.pdf');
        } catch (err) {
            console.error('Error al generar PDF:', err);
        } finally {
            // 🔹 Restaurar elementos visibles
            btnsFiltros.show();
            colAcciones.show();
        }
    });
});
</script>
@endsection


