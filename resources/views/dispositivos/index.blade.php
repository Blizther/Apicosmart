@extends('usuario.inicio')

@section('content')
<div class="container mt-4">
  <h3>Mis dispositivos</h3>

  {{-- Mensajes --}}
  @if(session('ok'))
    <div class="alert alert-success mt-3">{{ session('ok') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger mt-3">
      <ul class="mb-0">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Formulario de vinculación --}}
  <div class="card mt-3">
    <div class="card-body">
      <h6 class="mb-3">Vincular nuevo dispositivo</h6>
      <form method="POST" action="{{ route('mis.dispositivos.store') }}">
        @csrf
        <div class="row g-2">
          <div class="col-md-6">
            <label class="form-label">Serial (código único del sensor)</label>
            <input type="text" name="serial" class="form-control" value="{{ old('serial') }}" required>
          </div>
          <!--select para seleccionar la colmena  activa del usuario autenticado con la que se vinculará el dispositivo-->
          <div class="col-md-6">
            <label class="form-label">Colmena a vincular</label>
            <div class="col-md-12">
              <select name="idColmena" class="form-select" required>
                <option value="" disabled selected>Seleccione una colmena</option>
                @foreach($colmenas as $colmena)
                  <option value="{{ $colmena->idColmena }}" {{ old('idColmena') == $colmena->idColmena ? 'selected' : '' }}>
                  Colmena #{{ $colmena->codigo }} - {{ $colmena->apiario->nombre }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          
        </div>
        <div class="mt-3">
          <button type="submit" class="btn btn-primary">Vincular</button>
        </div>
      </form>
    </div>
  </div>

  {{-- Listado de vinculados --}}
  <div class="card mt-4">
    <div class="card-body">
      <h6 class="mb-3">Dispositivos vinculados</h6>

      <div class="table-responsive">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              
              <th>Serial</th>
              <th>Colmena</th>
              <th>Estado</th>
              <th style="width:140px;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse($dispositivos as $d)
              <tr>
                
                <td><code>{{ $d->fabricado->serial ?? '—' }}</code></td>
                <!--<td>{{ $d->colmena->codigo ?? '—' }} - {{ $d->colmena->apiario->nombre ?? '—' }}</td>-->
                <td>
                  @if($d->idColmena)
                   Colmena # {{ $d->colmena->codigo ?? '—' }} - {{ $d->colmena->apiario->nombre ?? '—' }}
                  @else
                    <span class="text-muted">No vinculado a colmena</span>
                  @endif
                </td>
                <td>{{ $d->estado ? 'Sí' : 'No' }}</td>
                <td>
                  <a class="btn btn-link p-0" href="{{ route('mis.dispositivos.show', $d->id) }}">Ver lecturas</a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted">Aún no has vinculado dispositivos.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>
@endsection
