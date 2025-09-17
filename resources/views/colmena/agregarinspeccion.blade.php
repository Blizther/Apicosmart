@extends('usuario.inicio')
@section('content')
<!-- Sale & Revenue Start -->
<div class="container-fluid pt-4 px-4">

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
        </div>
    </div>

    <div class="row g-4">
        <div class="col-sm-12">
            <a href="{{ route('colmenas.verinspeccion',['id'=>$id])}}">
                <button type="submit" class="btn btn-warning">VOLVER A LA LISTA DE INSPECCIONES</button>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Agregar nueva Inspeccion</h1>
            </div>
        </div>
    </div>

        <form action="{{ route('inspeccion.store')}}" method="POST" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="colmena_id" value="{{ $id }}">
                
            <div class="col-12">
                <div class="row">
                    <h6 class="col-12">Complete el formulario</h6>
                    

                    <div class="col-12 col-md-6">
                        <label for="temperamento">Temperamento</label>
                        <select name="temperamento" id="temperamento" class="form-control" required>
                            <option value="Manso" {{ old('temperamento') == 'Manso' ? 'selected' : '' }}>Manso</option>
                            <option value="Moderado" {{ old('temperamento') == 'Moderado' ? 'selected' : '' }}>Moderado</option>
                            <option value="Agresivo" {{ old('temperamento') == 'Agresivo' ? 'selected' : '' }}>Agresivo</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="intensidadImportacion">Intensidad de Importación</label>
                        <select name="intensidadImportacion" id="intensidadImportacion" class="form-control" required>
                            <option value="Alta" {{ old('intensidadImportacion') == 'Alta' ? 'selected' : '' }}>Alta</option>
                            <option value="Media" {{ old('intensidadImportacion') == 'Media' ? 'selected' : '' }}>Media</option>
                            <option value="Baja" {{ old('intensidadImportacion') == 'Baja' ? 'selected' : '' }}>Baja</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="estadoReyna">Estado de la Reina</label>
                        <select name="estadoReyna" id="estadoReyna" class="form-control" required>
                            <option value="Activa" {{ old('estadoReyna') == 'Activa' ? 'selected' : '' }}>Activa / Buena postura</option>
                            <option value="Debil" {{ old('estadoReyna') == 'Debil' ? 'selected' : '' }}>Débil</option>
                            <option value="Ausente" {{ old('estadoReyna') == 'Ausente' ? 'selected' : '' }}>Ausente</option>
                        </select>
                    </div>

                    
                    <div class="col-12 col-md-6">
                        <label for="reservaMiel">Reserva de Miel</label>
                        <select name="reservaMiel" id="reservaMiel" class="form-control" required>
                            <option value="Alta" {{ old('reservaMiel') == 'Alta' ? 'selected' : '' }}>Alta / Muchas reservas</option>
                            <option value="Media" {{ old('reservaMiel') == 'Media' ? 'selected' : '' }}>Media / Moderada</option>
                            <option value="Baja" {{ old('reservaMiel') == 'Baja' ? 'selected' : '' }}>Baja / Poca</option>
                            <option value="Limitada" {{ old('reservaMiel') == 'Limitada' ? 'selected' : '' }}>Limitada</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-12">
                        <label for="reservaPolen">Reserva de Polen</label>
                        <select name="reservaPolen" id="reservaPolen" class="form-control" required>
                            <option value="Alta" {{ old('reservaPolen') == 'Alta' ? 'selected' : '' }}>Alta / Mucho polen</option>
                            <option value="Media" {{ old('reservaPolen') == 'Media' ? 'selected' : '' }}>Media / Moderada</option>
                            <option value="Baja" {{ old('reservaPolen') == 'Baja' ? 'selected' : '' }}>Baja / Poca</option>
                            <option value="Nula" {{ old('reservaPolen') == 'Nula' ? 'selected' : '' }}>Nula</option>
                        </select>
                    </div>
                    

                    <div class="col-12 col-md-6">
                        <label>Celdas Reales</label><br>
                        <div class="form-check">
                            <input type="checkbox" name="celdasReales[]" value="No hay" class="form-check-input">
                            <label class="form-check-label">No hay</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="celdasReales[]" value="En progresion" class="form-check-input">
                            <label class="form-check-label">En progresión (con huevo / desarrollo)</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="celdasReales[]" value="Tapadas" class="form-check-input">
                            <label class="form-check-label">Tapadas (operculadas)</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="celdasReales[]" value="Destruidas" class="form-check-input">
                            <label class="form-check-label">Destruidas / incompletas</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="celdasReales[]" value="Varias" class="form-check-input">
                            <label class="form-check-label">Varias (abundantes, enjambrazón)</label>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label>Patrón de Postura</label><br>
                        <div class="form-check">
                            <input type="checkbox" name="patronPostura[]" value="Patron cerrado" class="form-check-input">
                            <label class="form-check-label">Patrón cerrado</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="patronPostura[]" value="Larvas amarillas" class="form-check-input">
                            <label class="form-check-label">Larvas amarillas</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="patronPostura[]" value="Huecos en un marco" class="form-check-input">
                            <label class="form-check-label">Huecos en un marco</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="patronPostura[]" value="Cria de zangano" class="form-check-input">
                            <label class="form-check-label">Cría de zángano abundante</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="patronPostura[]" value="Mucha cria operculada" class="form-check-input">
                            <label class="form-check-label">Mucha cría operculada</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="patronPostura[]" value="Sin corona de miel" class="form-check-input">
                            <label class="form-check-label">Sin corona de miel</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="patronPostura[]" value="Sospecha loque" class="form-check-input">
                            <label class="form-check-label">Sospecha de loque</label>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label>Enfermedades / Plagas</label><br>
                        <div class="form-check">
                            <input type="checkbox" name="enfermedadPlaga[]" value="Ninguna" class="form-check-input">
                            <label class="form-check-label">Ninguna</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="enfermedadPlaga[]" value="Loque europea" class="form-check-input">
                            <label class="form-check-label">Loque europea (larvas amarillas, olor fermentado)</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="enfermedadPlaga[]" value="Loque americana" class="form-check-input">
                            <label class="form-check-label">Loque americana</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="enfermedadPlaga[]" value="Pesticidas" class="form-check-input">
                            <label class="form-check-label">Contaminación por pesticidas</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="enfermedadPlaga[]" value="Humedad" class="form-check-input">
                            <label class="form-check-label">Exceso de humedad</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="enfermedadPlaga[]" value="Otra" class="form-check-input">
                            <label class="form-check-label">Otra (especificar en notas)</label>
                        </div>
                    </div>


                    <div class="col-12 form-group">
                        <div><label class="mb-3 bg-warning">Notas</label></div>
                        
                        <textarea name="notas" id="notas" class="form-control" rows="3">{{ old('notas') }}</textarea>
                    </div>

                    
                    
                    <div class="col-12 form-group">
                        <button type="submit" class="submit btn btn-primary w-100">GUARDAR INSPECCIÓN</button>
                    </div>
                </div>
            </div>
        </form>

    </div>

<!-- Sale & Revenue End -->
@endsection