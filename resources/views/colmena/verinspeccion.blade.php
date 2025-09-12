@extends('usuario.inicio')
@section('content')
<!-- Sale & Revenue Start -->
<div class="container-fluid pt-4 px-4">


    <div class="row g-4">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <a href="{{ route('colmenas.index')}}">
                    <button type="submit" class="btn btn-info">Volver</button>
                </a>
                <a href="{{ route('colmenas.agregarinspeccion',['id'=>$id])}}">
                    <button type="submit" class="btn btn-success">AGREGAR INSPECCIÓN</button>
                </a>
            </div>
            
        </div>
    </div>


    <div class="row g-4">
        <div class="col-sm-12">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <h1>Control de inspección</h1>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">NRO</th>
                            <th scope="col">Fecha de inspección</th>
                            <th scope="col">Temperamento</th>
                            <th scope="col">Intensidad de Importación</th>
                            <th scope="col">Estado de la Reyna</th>
                            <th scope="col">Celdas Reales</th>
                            <th scope="col">Patron de Postura</th>
                            <th scope="col">Enfermedades/Plaga</th>
                            <th scope="col">Reserva de miel</th>
                            <th scope="col">Reserva de polen</th>
                            <th scope="col">Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $correlativo=1;
                        ?>
                        @foreach ($colmenas as $colmena)
                            <tr>
                                <th scope="row"><?php echo $correlativo; ?></th>
                                <td>{{ \Carbon\Carbon::parse($colmena->fechaInspección)->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $colmena->temperamento }}</td>
                                <td>{{ $colmena->intensidadImportacion }}</td>
                                <td>{{ $colmena->estadoReyna }}</td>
                                <td>{{ $colmena->celdasReales }}</td>
                                <td>{{ $colmena->patronPostura }}</td>
                                <td>{{ $colmena->enfermedadPlaga }}</td>
                                <td>{{ $colmena->reservaMiel }}</td>
                                <td>{{ $colmena->reservaPolen }}</td>
                                <td>{{ $colmena->estadoReyna }}</td>
                                <td>{{ $colmena->notas }}</td>
                                
                               
                            </tr>
                        <?php
                        $correlativo++;
                        ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Sale & Revenue End -->

@endsection