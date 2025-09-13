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

    {{-- Filtros --}}
    <div class="bg-light rounded p-3 mb-3">
        <h5 class="mb-3">Reporte de ventas</h5>
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
                        <th class="text-center">Acciones</th> {{-- 👈 --}}
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
                            <a class="btn btn-outline-primary btn-sm"
                                href="{{ route('venta.reporte.detalle', $v->id) }}">
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