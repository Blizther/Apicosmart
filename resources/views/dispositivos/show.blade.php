@extends('usuario.inicio')

@section('content')
<div class="container mt-4">
  <h3>Lecturas del dispositivo</h3>
  <p class="text-muted mb-2">
    Serial: <code>{{ $dispositivo->fabricado->serial ?? '—' }}</code> |
    Nombre: {{ $dispositivo->nombre ?? '—' }}
  </p>

  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-sm">
          <thead>
            <tr>
              <th>ID</th>
              <th>Fecha/Hora</th>
              <th>Temperatura (°C)</th>
              <th>Humedad (%)</th>
              <th>Peso (kg)</th>
            </tr>
          </thead>
          <tbody>
            @forelse($lecturas as $l)
              <tr>
                <td>{{ $l->id }}</td>
                <td>{{ $l->ts ?? $l->created_at }}</td>
                <td>{{ $l->temperatura }}</td>
                <td>{{ $l->humedad }}</td>
                <td>{{ $l->peso }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted">Sin lecturas registradas.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{ $lecturas->links() }}
    </div>
  </div>

  <div class="mt-3">
    <a href="{{ route('mis.dispositivos') }}" class="btn btn-secondary">Volver</a>
  </div>
</div>
@endsection
