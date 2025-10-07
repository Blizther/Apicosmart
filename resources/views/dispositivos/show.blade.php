@extends('usuario.inicio')
@section('content')
<div class="container py-3">
  <h3>Dispositivo #{{ $disp->id }}</h3>
  <div class="mb-3"><strong>Serial:</strong> {{ $disp->serial }}</div>
  <div class="mb-3"><strong>Nombre:</strong> {{ $disp->nombre ?? '—' }}</div>
  <div class="mb-3"><strong>API-KEY:</strong> <code>{{ $disp->api_key }}</code></div>

  <h4 class="mt-4">Últimas lecturas</h4>
  <div class="card">
    <div class="card-body p-0">
      <table class="table mb-0">
        <thead>
          <tr>
            <th>Fecha/Hora</th>
            <th>Humedad (%)</th>
            <th>Peso (kg)</th>
            <th>Temperatura (°C)</th>
          </tr>
        </thead>
        <tbody>
          @forelse($lecturas as $l)
            <tr>
              <td>{{ $l->ts }}</td>
              <td>{{ $l->humedad !== null ? number_format($l->humedad, 2) : '—' }}</td>
              <td>{{ $l->peso !== null ? number_format($l->peso, 3) : '—' }}</td>
              <td>{{ $l->temperatura !== null ? number_format($l->temperatura, 2) : '—' }}</td>
            </tr>
          @empty
            <tr><td colspan="4" class="text-center p-4">Sin lecturas registradas.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <a href="{{ route('mis.dispositivos') }}" class="btn btn-link mt-3">Volver</a>
</div>
@endsection
