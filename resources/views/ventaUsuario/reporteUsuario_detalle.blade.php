@extends('usuario.inicio')

@section('content')

@php
    // Cliente: ajusta si tu columna se llama distinto
    $clienteNombre = trim(
        $venta->cliente_nombre
        ?? $venta->clienteNombre
        ?? $venta->cliente
        ?? $venta->nombre_cliente
        ?? ''
    );
    if ($clienteNombre === '') $clienteNombre = 'SIN NOMBRE';

    $fechaEmision = date('d/m/Y'); // fecha actual (una vez)
    $fechaVenta = \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d H:i');

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
        <div><strong>{{ \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d H:i') }}</strong></div>
      </div>
=======

        <button id="btnExportarPDF" class="btn btn-primary btn-sm">
            <i class="fa fa-file-pdf-o"></i> Exportar a PDF
        </button>

    </div>

    {{-- Panel NOTA centrado ~60% --}}
    <div class="nota-card mx-auto bg-white">

        {{-- HEADER igual al PDF --}}
        <div class="nota-header">
            

            <div class="nota-header-center">
                <div class="nota-title">NOTA DE VENTA</div>
                <div class="nota-subline"><strong>No:</strong> {{ $venta->id }}</div>
            </div>

            <div class="nota-header-right">
                <div class="nota-subline"><strong>Fecha emisión:</strong> {{ $fechaEmision }}</div>
            </div>
        </div>

        <hr class="nota-hr">

        {{-- Datos básicos igual al PDF (sin duplicar) --}}
        <div class="nota-datos small">
            <div><strong>Fecha venta:</strong> {{ $fechaVenta }}</div>
            <div><strong>Cliente:</strong> {{ $clienteNombre }}</div>
            <div><strong>Vendedor:</strong> {{ $vendedorNombre }}</div>
        </div>

        {{-- Tabla productos --}}
        <div class="table-responsive mt-3">
            <table class="table table-sm align-middle table-nota" id="tablaNotaPDF">
                <thead>
                    <tr>
                        <th style="width:50px">Nro</th>
                        <th>Producto</th>
                        <th class="text-end" style="width:100px">Cantidad</th>
                        <th class="text-end" style="width:140px">Precio Unit. (Bs.)</th>
                        <th class="text-end" style="width:140px">Subtotal (Bs.)</th>
                    </tr>
                </thead>
                <tbody>
                    @php($n=1)
                    @foreach($venta->detalles as $d)
                        <tr>
                            <td>{{ $n++ }}</td>
                            <td>{{ $d->producto->descripcion ?? "ID {$d->idProducto}" }}</td>
                            <td class="text-end">{{ number_format($d->cantidad) }}</td>
                            <td class="text-end">{{ number_format($d->precio_unitario, 2) }}</td>
                            <td class="text-end">{{ number_format($d->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">TOTAL</th>
                        <th class="text-end">{{ number_format($venta->total, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Pie igual al PDF --}}
        <div class="nota-footer small text-muted text-center mt-3">
            ApicoSmart | apicosmart@gmail.com | Tel: {{ $vendedorTelefono }}
        </div>

    </div>
</div>
@endsection


@section('styles')
<style>
    /* Panel centrado 60% */
    .nota-card{
        width: 60%;
        max-width: 820px;
        min-width: 320px;
        padding: 18px 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,.08);
        border: 1px solid #eee;
    }

    /* Header estilo PDF */
    .nota-header{
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .nota-header-left{
        width: 70px;
        flex: 0 0 auto;
    }
    .nota-logo{
        width: 60px !important;
        max-width: 60px !important;
        height: 60px !important;
        max-height: 60px !important;
        object-fit: contain;
        border-radius: 6px;
        display:block;
    }
    .nota-header-center{
        flex: 1 1 auto;
    }
    .nota-header-right{
        flex: 0 0 auto;
        text-align: right;
        min-width: 180px;
    }
    .nota-title{
        font-weight: 700;
        font-size: 18px;
    }
    .nota-subline{
        font-size: 13px;
        margin-top: 2px;
    }
    .nota-hr{
        margin: 10px 0 8px;
        border-top: 1px solid #bbb;
    }

    /* Datos */
    .nota-datos > div{
        margin: 4px 0;
    }

    /* Cebra verde clarito */
    .table-nota thead th{
        background: #198754;
        color: #fff;
        border: none;
    }
    .table-nota tbody tr:nth-child(odd){
        background: #e9f7ef;
    }
    .btn-volver {
    background-color: #ff9800 !important;   /* naranja */
    color: #ffffff !important;              /* texto blanco */
    border: none !important;
    font-weight: 600;
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

            const notaNo = "{{ $venta->id }}";
            const fechaEmision = "{{ date('d/m/Y') }}";
            const fechaVenta = "{{ $fechaVenta }}";
            const cliente = "{{ $clienteNombre }}";
            const vendedor = "{{ $vendedorNombre }}";
            const telVendedor = "{{ $vendedorTelefono }}";

            function headerFooter(pdfDoc, pageNum){
                pdfDoc.addImage(logo,'JPEG',10,6,18,18);
                pdfDoc.setFontSize(12);
                pdfDoc.text("NOTA DE VENTA",32,14);

                pdfDoc.setFontSize(9);
                pdfDoc.text(`No: ${notaNo}`, 32, 20);
                pdfDoc.text(`Fecha emisión: ${fechaEmision}`, 80, 20);

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
            a.download = `nota_venta_${notaNo}.pdf`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        };
    });

});
</script>
@endsection
