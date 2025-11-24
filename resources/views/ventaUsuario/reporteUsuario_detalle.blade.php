@extends('usuario.inicio')

@section('content')

@php
    $clienteNombre = trim(
        $venta->cliente_nombre
        ?? $venta->clienteNombre
        ?? $venta->cliente
        ?? $venta->nombre_cliente
        ?? ''
    );
    if ($clienteNombre === '') $clienteNombre = 'SIN NOMBRE';

    $fechaEmision = now()->format('d/m/Y');
    $fechaVenta = \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i');

    $vendedorNombre = trim(($venta->usuario->nombre ?? 'N/D').' '.($venta->usuario->primerApellido ?? ''));
    $vendedorTelefono = $venta->usuario->telefono ?? 'N/D';
@endphp

<div class="container-fluid pt-4 px-4">

    {{-- Botones (correctos como pediste) --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ url('/reporteUsuario') }}" class="btn btn-sm btn-warning text-white">
        Volver
        </a>

  <div class="row g-3 mb-3">
    <div class="col-md-4">
      <div class="bg-light rounded p-3">
        <div><small class="text-muted">Fecha</small></div>
        <div><strong>{{ \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d') }}</strong></div>
      </div>

        <button id="btnExportarPDF" class="btn btn-primary btn-sm">
            <i class="fa fa-file-pdf-o"></i> Exportar a PDF
        </button>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div class="d-flex gap-2">
            <a href="{{ url('/reporteUsuario') }}" class="btn btn-sm btn-volver">
                Volver
            </a>

            <button id="btnExportarPDF" class="btn btn-sm btn-primary">
                <i class="fa fa-file-pdf-o"></i> Exportar a PDF
            </button>
        </div>
    </div>

    <div class="nota-card mx-auto">

        <div class="nota-header">
            <div class="nota-header-center text-center w-100">
                <div class="nota-title">NOTA DE VENTA</div>
            </div>

            <div class="nota-header-right">
                <div class="nota-subline">
                    <strong>Fecha emisión:</strong> {{ $fechaEmision }}
                </div>
            </div>
        </div>

        <hr class="nota-hr">

        <div class="nota-datos">
            <div><strong>Fecha venta:</strong> {{ $fechaVenta }}</div>
            <div><strong>Cliente:</strong> {{ $clienteNombre }}</div>
            <div><strong>Vendedor:</strong> {{ $vendedorNombre }}</div>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-bordered align-middle table-nota table-nota-xs" id="tablaNotaPDF">
                <thead>
                    <tr>
                        <th style="width:28px" class="text-center">Nro</th>
                        <th>Producto</th>
                        <th style="width:70px" class="text-end">Cantidad</th>
                        <th style="width:90px" class="text-end">Costo Unit (Bs.)</th>
                        <th style="width:95px" class="text-end">Subtotal (Bs.)</th>
                    </tr>
                </thead>
                <tbody>
                    @php($n=1)
                    @foreach($venta->detalles as $d)
                        <tr>
                            <td class="text-center">{{ $n++ }}</td>
                            <td class="text-truncate" style="max-width:320px">
                                {{ $d->producto->descripcion ?? "ID {$d->idProducto}" }}
                            </td>
                            <td class="text-end">{{ number_format($d->cantidad) }}</td>
                            <td class="text-end">{{ number_format($d->precio_unitario, 2) }}</td>
                            <td class="text-end">{{ number_format($d->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <th colspan="4" class="text-end">TOTAL</th>
                        <th class="text-end">{{ number_format($venta->total, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="nota-footer text-muted text-center mt-3">
            ApicoSmart | apicosmart@gmail.com | Tel: {{ $vendedorTelefono }}
        </div>

    </div>
</div>
@endsection


@section('styles')
<style>
    /* Panel más grande */
    .nota-card{
        width: 70%;
        max-width: 900px;
        min-width: 360px;
        padding: 28px 32px;
        border-radius: 14px;
        box-shadow: 0 3px 14px rgba(0,0,0,.12);
        border: 1px solid #cfe9d9;
        background: #f5fff8;
    }

    .nota-header{
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .nota-header-right{
        text-align: right;
        min-width: 180px;
    }

    .nota-title{
        font-weight: 900;
        font-size: 30px;
        letter-spacing: 1px;
        color: #145c32;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .nota-subline{
        font-size: 13px;
        margin-top: 2px;
    }

    .nota-hr{
        margin: 10px 0 8px;
        border-top: 1px solid #8fc7a7;
    }

    .nota-datos > div{
        margin: 6px 0;
        font-size: 1rem;
    }

    /* ✅ FORZAR encabezados visibles SI O SI */
    .table-nota thead th{
        background-color: #198754 !important;
        color: #ffffff !important;
        border-color: #198754 !important;
        font-weight: 700 !important;
        font-size: 0.90rem !important;
    }
    .table-nota thead{
        display: table-header-group !important;
    }

    .table-nota tbody tr:nth-child(odd){
        background: #e9f7ef;
    }
    .table-nota tbody tr:nth-child(even){
        background: #f7fffb;
    }

    /* Compacta, pero legible */
    .table-nota-xs th,
    .table-nota-xs td{
        padding: 0.22rem 0.35rem !important;
        font-size: 0.85rem !important;
        line-height: 1.25 !important;
        vertical-align: middle !important;
    }

    .nota-footer{
        font-size: 0.85rem;
    }

    .btn-volver {
        background-color: #ff9800 !important;
        color: #ffffff !important;
        border: none !important;
        font-weight: 700;
    }
    .btn-volver:hover{
        background-color:#e68900 !important;
        color:#fff !important;
    }
</style>
@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script>
$(document).ready(function() {

    $('#btnExportarPDF').click(function() {

        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p','mm','a4');
        const pageWidth  = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();

        const logo = new Image();
        logo.src = '{{ asset("img/logoApicoSmart.jpg") }}';

        logo.onload = function() {

            const margenSuperior = 28;
            const margenInferior = 18;

            const fechaEmision = "{{ $fechaEmision }}";
            const fechaVenta = "{{ $fechaVenta }}";
            const cliente = "{{ $clienteNombre }}";
            const vendedor = "{{ $vendedorNombre }}";
            const telVendedor = "{{ $vendedorTelefono }}";

            function headerFooter(pdfDoc, pageNum){
                pdfDoc.addImage(logo,'JPEG',10,6,18,18);
                pdfDoc.setFontSize(12);
                pdfDoc.text("NOTA DE VENTA",32,14);

                pdfDoc.setFontSize(9);
                pdfDoc.text(`Fecha emisión: ${fechaEmision}`, 32, 20);

                pdfDoc.line(10,24,pageWidth-10,24);

                pdfDoc.setFontSize(8);
                pdfDoc.line(10, pageHeight - margenInferior, pageWidth-10, pageHeight - margenInferior);
                pdfDoc.text(`ApicoSmart | apicosmart@gmail.com | Tel: ${telVendedor}`, 10, pageHeight-10);
                pdfDoc.text(`Página ${pageNum}`, pageWidth-30, pageHeight-10);
            }

            pdf.setFontSize(9);
            pdf.text(`Fecha venta: ${fechaVenta}`, 10, margenSuperior);
            pdf.text(`Cliente: ${cliente}`, 10, margenSuperior+6);
            pdf.text(`Vendedor: ${vendedor}`, 10, margenSuperior+12);

            const columnas = ["Nro","Producto","Cantidad","Precio Unit. (Bs.)","Subtotal (Bs.)"];
            const filas = [];
            let i=1;

            @foreach($venta->detalles as $d)
                filas.push([
                    i++,
                    "{{ $d->producto->descripcion ?? "ID {$d->idProducto}" }}",
                    "{{ number_format($d->cantidad) }}",
                    "{{ number_format($d->precio_unitario, 2) }}",
                    "{{ number_format($d->subtotal, 2) }}"
                ]);
            @endforeach

            filas.push(["","TOTAL","","","{{ number_format($venta->total, 2) }}"]);

            pdf.autoTable({
                head:[columnas],
                body:filas,
                startY: margenSuperior + 18,
                theme:'striped',
                headStyles:{ fillColor:[25,135,84], textColor:255 },
                alternateRowStyles:{ fillColor:[233,247,239] },
                margin:{ top:margenSuperior+10, bottom:margenInferior },
                didDrawPage:function(){
                    const page = pdf.internal.getCurrentPageInfo().pageNumber;
                    headerFooter(pdf,page);
                },
                didParseCell:function(data){
                    if(data.row.index === filas.length-1){
                        data.cell.styles.fontStyle='bold';
                    }
                }
            });

            const blob = pdf.output('blob');
            const blobUrl = URL.createObjectURL(blob);
            window.open(blobUrl, '_blank');

            const a = document.createElement('a');
            a.href = blobUrl;
            a.download = `nota_venta.pdf`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        };
    });

});
</script>
@endsection
