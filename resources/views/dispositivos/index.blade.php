@extends('usuario.inicio')
@section('content')
<div class="container py-3">
  <h3>Mis dispositivos</h3>

  <div class="card mb-4">
    <div class="card-header">Vincular nuevo dispositivo</div>
    <div class="card-body">
      <form method="POST" action="{{ route('mis.dispositivos.store') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label">Serial (código único del sensor)</label>
          <input type="text" name="serial" class="form-control" value="{{ old('serial') }}" required>
          @error('serial') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Nombre (opcional)</label>
          <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}">
          @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Vincular</button>
      </form>
    </div>
  </div>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <div class="card">
    <div class="card-header">Dispositivos vinculados</div>
    <div class="card-body p-0">
      <table class="table mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Serial</th>
            <th>Nombre</th>
            <th>ESTADO</th>
            <th>API-KEY</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($dispositivos as $d)
            <tr>
              <td>{{ $d->id }}</td>
              <td>{{ $d->serial }}</td>
              <td>{{ $d->nombre ?? '—' }}</td>
              <td>{{ $d->estado ? 'Sí' : 'No' }}</td>
              <td><code>{{ $d->api_key }}</code></td>
              <td>
                <a href="{{ route('mis.dispositivos.show', $d->id) }}" class="btn btn-sm btn-outline-primary">Ver lecturas</a>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center p-4">Aún no tienes dispositivos vinculados.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection
