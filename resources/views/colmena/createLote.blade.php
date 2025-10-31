@extends('usuario.inicio')

@section('content')
<div class="container-fluid pt-4 px-4">

    {{-- Alertas de error --}}
    <div class="row g-4">
        <div class="col-sm-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Error:</strong> Corrige los siguientes campos:<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>

    {{-- Botón volver --}}
    <div class="row g-4 mb-3">
        <div class="col-sm-12">
            <a href="{{ route('colmenas.index')}}" class="btn btn-warning">
                <i class="fa fa-arrow-left"></i> Volver a la lista
            </a>
        </div>
    </div>

    {{-- Encabezado --}}
    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Agregar colmenas por lote</h1>
            </div>
        </div>
    </div>

    {{-- Formulario --}}
    <form action="{{ route('colmenas.storeLote') }}" method="POST">
        @csrf
        <div class="bg-light rounded p-4 mt-3">
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="tablaColmenas">
                    <thead class="table-secondary">
                        <tr>
                            <th>Código</th>
                            <th>Apiario</th>
                            <th>Fecha Instalación</th>
                            <th>Cantidad de Marcos</th>
                            <th>Modelo</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="filas">
                        <tr>
                            <td>
                                <input type="text" name="colmenas[0][codigo]" class="form-control" placeholder="Código" required readonly>
                            </td>
                            <td>
                                <select name="colmenas[0][apiario]" class="form-control" required>
                                    <option value="">-- Selecciona --</option>
                                    @foreach($apiarios as $apiario)
                                        <option value="{{ $apiario->idApiario }}">
                                            {{ $apiario->nombre }} ({{ $apiario->colmenas_count }} colmenas)
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="date" name="colmenas[0][fechaInstalacionFisica]" class="form-control"
                                    max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            </td>
                            <td>
                                <input type="number" name="colmenas[0][cantidadMarco]" class="form-control" min="0" max="10" value="0">
                            </td>
                            <td>
                                <select name="colmenas[0][modelo]" class="form-control">
                                    <option value="Langstroth">Langstroth</option>
                                    <option value="Dadant">Dadant</option>
                                    <option value="Warre">Warre</option>
                                    <option value="Layens">Layens</option>
                                    <option value="Top Bar">Top Bar</option>
                                    <option value="Flow Hive">Flow Hive</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm eliminar">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <button type="button" id="agregarFila" class="btn btn-success">
                    <i class="fa fa-plus"></i> Agregar fila
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Guardar todas
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Script para agregar/eliminar filas --}}

<script>
let index = 1;

document.getElementById('agregarFila').addEventListener('click', function() {
    const primeraFila = document.querySelector('#filas tr');
    const nuevaFila = primeraFila.cloneNode(true);

    nuevaFila.querySelectorAll('input, select').forEach(el => {
        el.name = el.name.replace(/\d+/, index);
        if (el.tagName === 'INPUT') el.value = '';
        if (el.tagName === 'SELECT') el.selectedIndex = 0;
    });

    document.getElementById('filas').appendChild(nuevaFila);
    index++;
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('eliminar') || e.target.closest('.eliminar')) {
        const filas = document.querySelectorAll('#filas tr');
        if (filas.length > 1) {
            e.target.closest('tr').remove();
        } else {
            alert('Debe haber al menos una fila.');
        }
    }
});


// 🧠 NUEVO: autocompletar código según apiario
document.addEventListener('change', async function(e) {
    if (e.target.matches('select[name*="[apiario]"]')) {
        const fila = e.target.closest('tr');
        const idApiario = e.target.value;

        if (!idApiario) return;

        try {
            const response = await fetch(`/colmenas/proximo-codigo/${idApiario}`);
            if (!response.ok) throw new Error('Error al obtener el código');
            const data = await response.json();

            const inputCodigo = fila.querySelector('input[name*="[codigo]"]');
            if (inputCodigo && !inputCodigo.value) {
                inputCodigo.value = data.codigo;
            }
        } catch (error) {
            console.error('Error al autocompletar código:', error);
        }
    }
});
</script>

@endsection
