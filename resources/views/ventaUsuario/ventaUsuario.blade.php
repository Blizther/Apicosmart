@extends('usuario.inicio')
@section('content')

<div class="container-fluid pt-4 px-4">
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

  <div class="row g-4">
    {{-- Mis productos --}}
    <div class="col-lg-7">
      <div class="bg-light rounded p-3">
        <h5>Mis productos</h5>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr>
                <th>Descripción</th>
                <th>UM</th>
                <th>Stock</th>
                <th>Precio</th>
                <th style="width:220px;">Agregar</th>
              </tr>
            </thead>
            <tbody>
              @php($i=1)
              @forelse($productos as $p)
                <tr>
                  <td>{{ $p->descripcion }}</td>
                  <td>{{ $p->unidadMedida }}</td>
                  <td>{{ $p->stock }}</td>
                  <td>{{ number_format($p->precio,2) }}</td>
                  <td>
                    <form method="POST" action="{{ route('venta.cart.add') }}" class="d-inline-flex align-items-center" style="gap:8px;">
                      @csrf
                      <input type="hidden" name="producto_id" value="{{ $p->id }}">
                      <input type="number" class="form-control form-control-sm" name="cantidad" min="1" value="1" style="width:90px;">
                      <button type="submit" class="btn btn-primary btn-sm">Agregar</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="5">No tienes productos registrados.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Carrito --}}
    <div class="col-lg-5">
      <div class="bg-light rounded p-3">
        <h5>Mi carrito</h5>
        <div class="table-responsive">
          <table class="table table-sm">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Cant.</th>
                <th>Unit.</th>
                <th>Subt.</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            @forelse($cart as $item)
              @php($sub = $item['precio'] * $item['cantidad'])
              <tr>
                <td>{{ $item['descripcion'] }}</td>
                <td>
                  <form method="POST" action="{{ route('venta.cart.update') }}" class="d-inline-flex align-items-center" style="gap:6px;">
                    @csrf
                    <input type="hidden" name="producto_id" value="{{ $item['id'] }}">
                    <input type="number" name="cantidad" min="1" value="{{ $item['cantidad'] }}" class="form-control form-control-sm" style="width:80px;">
                    <button class="btn btn-secondary btn-sm" type="submit">OK</button>
                  </form>
                </td>
                <td>{{ number_format($item['precio'],2) }}</td>
                <td>{{ number_format($sub,2) }}</td>
                <td>
                  <form method="POST" action="{{ route('venta.cart.remove') }}">
                    @csrf
                    <input type="hidden" name="producto_id" value="{{ $item['id'] }}">
                    <button class="btn btn-danger btn-sm" type="submit">Quitar</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="5">Tu carrito está vacío.</td></tr>
            @endforelse
            </tbody>
            <tfoot>
              <tr>
                <th colspan="3" class="text-end">Total:</th>
                <th>{{ number_format($total,2) }}</th>
                <th></th>
              </tr>
            </tfoot>
          </table>
        </div>

        {{-- Datos del cliente + Checkout --}}
        <form method="POST" action="{{ route('venta.checkout') }}" class="mt-2">
          @csrf
          <div class="mb-2">
            <label>Cliente</label>
            <input type="text" name="cliente_nombre" class="form-control form-control-sm" value="{{ old('cliente_nombre') }}">
          </div>
          <div class="mb-2">
            <label>Documento</label>
            <input type="text" name="cliente_doc" class="form-control form-control-sm" value="{{ old('cliente_doc') }}">
          </div>
          <div class="mb-3">
            <label>Método de pago</label>
            <select name="metodo_pago" class="form-control form-control-sm">
              <option value="efectivo">Efectivo</option>
              <option value="qr">QR</option>
              <option value="tarjeta">Tarjeta</option>
            </select>
          </div>
          <button class="btn btn-success btn-sm" type="submit" @if(empty($cart)) disabled @endif>Confirmar venta</button>
        </form>

      </div>
    </div>
  </div>
</div>

@endsection
