@extends('usuario.inicio')
@section('content')

<div class="container-fluid pt-4 px-4">

  <div class="bg-light rounded p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Detalle de Venta #{{ $venta->id }}</h5>
      <div class="d-flex gap-2">
        <a href="{{ url('/reporteUsuario') }}" class="btn btn-sm btn-secondary">Volver</a>
        <button class="btn btn-sm btn-outline-dark" onclick="window.print()">Imprimir</button>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-md-4">
      <div class="bg-light rounded p-3">
        <div><small class="text-muted">Fecha</small></div>
        <div><strong>{{ \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d H:i') }}</strong></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="bg-light rounded p-3">
        <div><small class="text-muted">Vendedor</small></div>
        <div>
          <strong>
            {{ $venta->usuario->nombre ?? 'N/D' }}
            {{ $venta->usuario->primerApellido ?? '' }}
          </strong>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="bg-light rounded p-3">
        <div><small class="text-muted">Estado</small></div>
        @php($estadoTxt = $venta->estado == 1 ? 'Confirmada' : 'Anulada')
        <span class="badge bg-{{ $venta->estado == 1 ? 'success' : 'secondary' }}">{{ $estadoTxt }}</span>
      </div>
    </div>
  </div>

  <div class="bg-light rounded p-3">
    <h6>Ítems</h6>
    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Producto</th>
            <th class="text-end">Cantidad</th>
            <th class="text-end">Precio Unit.</th>
            <th class="text-end">Subtotal</th>
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
            <th colspan="4" class="text-end">Total</th>
            <th class="text-end">{{ number_format($venta->total, 2) }}</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

</div>
@endsection
