@extends('usuario.inicio')

@section('content')
<div class="text-right mb-3">
    <button id="btnExportarPDF" class="btn btn-primary">
        <i class="fa fa-file-pdf-o"></i> Exportar a PDF
    </button>
</div>

<div class="container-fluid pt-4 px-4" id="reporteVentasContenido">
    {{-- Encabezado --}}
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
            <table class="table table-sm align-middle" id="tablaVentasPDF">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th class="text-end">Total (Bs.)</th>
                        <th class="text-center">Estado</th>
                        <th class="text-end">Ítems</th>
                        <th class="text-center">Acciones</th> {{-- 👈 esta columna no irá al PDF --}}
                    </tr>
                </thead>
                <tbody>
                    @php $correlativo = 1; @endphp
                    @forelse($ventas as $v)
                        @php
                            $itemsCount = $v->detalles?->sum('cantidad') ?? 0;
                            $badge = ($v->estado == 1) ? 'success' : 'secondary';
                            $estadoTxt = ($v->estado == 1) ? 'Confirmada' : 'Anulada';
                        @endphp
                        <tr>
                            <td>{{ $correlativo }}</td>
                            <td>{{ \Carbon\Carbon::parse($v->fecha)->format('Y-m-d') }}</td>
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
                        @php $correlativo++; @endphp
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script>
$(document).ready(function() {
    $('#btnExportarPDF').prop('disabled', false);

    $('#btnExportarPDF').click(function() {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');
        const pageWidth = pdf.internal.pageSize.getWidth();

        // Encabezado y logo
        const logo = new Image();
        logo.src = '{{ asset("img/logoApicoSmart.jpg") }}';
        logo.onload = function() {
            const margenSuperior = 35;

            // Datos usuario y fecha
            const nombreUsuario = "{{ auth()->user()->nombre }} {{ explode(' ', auth()->user()->primerApellido)[0] }}";
            const telefono = "{{ auth()->user()->telefono }}";
            const fecha = "{{ date('d/m/Y') }}";

            function agregarEncabezado(pdfDoc, paginaActual, totalPaginas) {
                pdfDoc.addImage(logo, 'JPEG', 10, 5, 20, 20);
                pdfDoc.setFontSize(12);
                pdfDoc.text("ApicoSmart", 35, 12);
                pdfDoc.setFontSize(9);
                pdfDoc.text(`${nombreUsuario} | Tel: ${telefono}`, 35, 17);
                pdfDoc.text(`Fecha: ${fecha}`, 35, 22);
                pdfDoc.setFontSize(8);
                pdfDoc.text(`Página ${paginaActual} `, pageWidth - 35, 22);
                pdfDoc.line(10, 25, pageWidth - 10, 25);
            }

            // Resumen de ventas
            pdf.setFontSize(10);
            pdf.text(`Ventas: {{ number_format($resumen['cantidadVentas']) }}`, 10, margenSuperior);
            pdf.text(`Total vendido: Bs. {{ number_format($resumen['totalVendido'],2) }}`, 60, margenSuperior);
            pdf.text(`Ítems vendidos: {{ number_format($resumen['itemsVendidos']) }}`, 140, margenSuperior);

            // Preparar datos de tabla para jsPDF-autoTable
            const columnas = ["#", "Fecha", "Total (Bs.)", "Estado", "Ítems"];
            const filas = [];
            indice=1;
            @foreach($ventas as $v)
                filas.push([
                    indice++,
                    "{{ \Carbon\Carbon::parse($v->fecha)->format('Y-m-d H:i') }}",
                    "{{ number_format($v->total, 2) }}",
                    "{{ $v->estado==1?'Confirmada':'Anulada' }}",
                    "{{ $v->detalles?->sum('cantidad') ?? 0 }}"
                ]);
            @endforeach

            pdf.autoTable({
                head: [columnas],
                body: filas,
                startY: margenSuperior + 8,
                theme: 'striped',
                headStyles: { fillColor: [41, 128, 185], textColor: 255 },
                alternateRowStyles: { fillColor: [240, 240, 240] },
                margin: { top: margenSuperior + 8 },
                didDrawPage: function (data) {
                    // Se llama en cada página
                    const page = pdf.internal.getCurrentPageInfo().pageNumber;
                    const total = pdf.internal.getNumberOfPages();
                    agregarEncabezado(pdf, page, total);
                }
            });

            pdf.save('reporte_ventas.pdf');
        };
    });
});
</script>
@endsection
