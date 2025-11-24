<div class="modal fade" id="modalPerfil" tabindex="-1" role="dialog" aria-labelledby="modalPerfilLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <form method="POST" action="{{ route('perfil.update') }}">
        @csrf
        @method('PUT')

        <div class="modal-header" style="background:#3A4F26;color:#EDD29C;">
          <h5 class="modal-title" id="modalPerfilLabel">
            <i class="fa fa-user-circle"></i> Editar mi perfil
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#EDD29C;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">

          {{-- Errores --}}
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul style="margin:0;padding-left:18px;">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="row">
            <div class="col-md-4 form-group">
              <label>Nombre</label>
              <input type="text" name="nombre" class="form-control"
                     value="{{ old('nombre', auth()->user()->nombre) }}" required>
            </div>

            <div class="col-md-4 form-group">
              <label>Primer Apellido</label>
              <input type="text" name="primerApellido" class="form-control"
                     value="{{ old('primerApellido', auth()->user()->primerApellido) }}">
            </div>

            <div class="col-md-4 form-group">
              <label>Segundo Apellido</label>
              <input type="text" name="segundoApellido" class="form-control"
                     value="{{ old('segundoApellido', auth()->user()->segundoApellido) }}">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label>Correo</label>
              <input type="email" name="email" class="form-control"
                     value="{{ old('email', auth()->user()->email) }}" required>
            </div>

            <div class="col-md-6 form-group">
              <label>Teléfono</label>
              <input type="text" name="telefono" class="form-control"
                     value="{{ old('telefono', auth()->user()->telefono) }}">
            </div>
          </div>

          {{-- ✅ Ya NO se edita nombreUsuario --}}

          <hr>

          <p class="text-muted" style="margin-bottom:10px;">
            Si quieres cambiar tu contraseña, completa estos campos:
          </p>

          <div class="form-group">
            <label>Contraseña actual</label>
            <input type="password" name="current_password" class="form-control" minlength="6">
          </div>

          <div class="form-group">
            <label>Nueva contraseña</label>
            <input type="password" name="password" class="form-control" minlength="6">
          </div>

          <div class="form-group">
            <label>Confirmar nueva contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" minlength="6">
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" class="btn btn-primary"
                  style="background:#F9B233;border:none;color:#3A4F26;font-weight:600;">
            Guardar cambios
          </button>
        </div>

      </form>

    </div>
  </div>
</div>
