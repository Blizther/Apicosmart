@extends('administrador.inicio')

@section('content')
<div class="container mt-4">
  <h3>Inventario de Dispositivos Fabricados</h3>

  @if(session('ok'))
  <div class="alert alert-success mt-3">
    {{ session('ok') }}<br>
    @if(session('api_key'))
    <strong>API-KEY (copiar ahora, no se mostrará de nuevo):</strong>
    <code>{{ session('api_key') }}</code>
    @endif
  </div>
  @endif

  <div class="my-3">
    <a href="{{ route('fabricados.create') }}" class="btn btn-success">Nuevo dispositivo</a>
  </div>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Serial</th>
        <th>API-KEY</th> {{-- ← nueva columna --}}
        <th>Estado</th>
        <th>Creado</th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $it)
      <tr>
        <td>{{ $it->id }}</td>
        <td><code>{{ $it->serial }}</code></td>
        <td>
          @if($it->api_key_hash)
          <code>{{ $it->api_key_hash }}</code>
          @else
          <span class="text-muted">—</span>
          @endif
        </td>

        <td>{{ $it->estado ? 'Activo' : 'Inactivo' }}</td>
        <td>{{ $it->created_at }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="5" class="text-center">Sin registros</td>
      </tr>
      @endforelse
    </tbody>
  </table>


  {{ $items->links() }}
</div>
@endsection