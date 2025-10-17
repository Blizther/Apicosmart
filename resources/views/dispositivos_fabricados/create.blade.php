@extends('administrador.inicio')

@section('content')
<div class="container mt-4">

    <h3 class="mb-4">Registrar nuevo dispositivo fabricado</h3>

    {{-- Mensaje de éxito y API-KEY generada --}}
    @if(session('ok'))
        <div class="alert alert-success">
            {{ session('ok') }}
            @if(session('api_key'))
                <div class="mt-2">
                    <strong>API-KEY generada:</strong>
                    <code>{{ session('api_key') }}</code><br>
                    <small class="text-muted">Guárdala en un lugar seguro. No se volverá a mostrar.</small>
                </div>
            @endif
        </div>
    @endif

    {{-- Mensajes de error --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario de creación --}}
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('fabricados.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="serial" class="form-label fw-bold">Serial del dispositivo</label>
                    <input 
                        type="text" 
                        id="serial" 
                        name="serial" 
                        class="form-control text-uppercase" 
                        placeholder="Ej: ABCD-ASER4-45AL-12PL" 
                        value="{{ old('serial') }}" 
                        required 
                        oninput="this.value=this.value.toUpperCase().trim()">
                    @error('serial')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('fabricados.index') }}" class="btn btn-secondary">Volver</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
