@extends('usuario.inicio')
@section('content')
<div class="container-fluid pt-4 px-4">
<div class="row">
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Total apiarios</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">386,200</h1>
                        <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div>
                        <small>Total views</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        
                        <h5>total colmenas</h5>
                    </div>
                    <div class="ibox-content">
                        <i class="fa fa-archive"></i>
                        <h1 class="no-margins">80,800</h1>
                        <div class="stat-percent font-bold text-info">20% <i class="fa fa-level-up"></i></div>
                        
                        <small>New orders</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        
                        <h5>Inspecciones</h5>
                    </div>
                    <div class="ibox-content">
                        <i class="fa fa-list-alt"></i>


                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>cantidad de sensores</h5>
                        
                    </div>
                    <div class="ibox-content">
                        <i class="fa fa-thermometer"></i>
                    </div>

                </div>
            </div>
        </div>
</div>
@endsection