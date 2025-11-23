<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Editar Empleado - Hotel</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a2d04a4f5d.js" crossorigin="anonymous"></script>
  @vite(['resources/css/estilo2.css'])
</head>
<body>
  <div class="dashboard-container">
    <main class="main-content" id="main">
      <article class="card-form" role="article" aria-labelledby="title-form">
        <header class="card-header-gradient">
          <h2 id="title-form"><i class="fa-solid fa-user-pen me-2"></i> Editar Empleado</h2>
        </header>

        <div class="card-body">
          @if ($errors->any())
            <div class="alert alert-danger" role="alert">
              <strong>❌ Errores encontrados:</strong>
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          @if (session('success'))
            <div class="alert alert-success" role="alert">
              <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
          @endif

          <form id="formEmpleado" method="POST" action="{{ route('admin.empleados.update', $user->id) }}" novalidate>
            @csrf
            @method('PUT')

            <section aria-labelledby="personal-title" class="mb-4">
              <h5 id="personal-title" class="section-title">Información Personal</h5>
              <div class="row g-3">
                <div class="col-12">
                  <label for="name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="col-12">
                  <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                  <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="col-md-6">
                  <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                  <input type="tel" class="form-control" id="telefono" name="telefono" value="{{ old('telefono', $user->telefono) }}" required>
                </div>

                <div class="col-md-6">
                  <label for="direccion" class="form-label">Dirección</label>
                  <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion', $user->direccion) }}">
                </div>

                <div class="col-md-6">
                  <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                  <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', optional($user->fecha_nacimiento)->format('Y-m-d')) }}">
                </div>
              </div>
            </section>

            <hr />

            <section aria-labelledby="laboral-title" class="mb-3">
              <h5 id="laboral-title" class="section-title">Información Laboral</h5>

              <div class="row g-3">
                <div class="col-md-6">
                  <label for="numero_empleado" class="form-label">Número de Empleado <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="numero_empleado" name="numero_empleado" value="{{ old('numero_empleado', $user->empleado->numero_empleado) }}" required>
                </div>

                <div class="col-md-6">
                  <label for="puesto" class="form-label">Puesto <span class="text-danger">*</span></label>
                  <select class="form-select" id="puesto" name="puesto" required>
                    <option value="">Seleccionar puesto</option>
                    <option value="recepcionista" {{ old('puesto', $user->empleado->puesto) == 'recepcionista' ? 'selected' : '' }}>Recepcionista</option>
                    <option value="limpieza" {{ old('puesto', $user->empleado->puesto) == 'limpieza' ? 'selected' : '' }}>Limpieza</option>
                    <option value="administrador" {{ old('puesto', $user->empleado->puesto) == 'administrador' ? 'selected' : '' }}>Administrador</option>
                    <option value="gerente" {{ old('puesto', $user->empleado->puesto) == 'gerente' ? 'selected' : '' }}>Gerente</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label for="fecha_contratacion" class="form-label">Fecha de Contratación <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="fecha_contratacion" name="fecha_contratacion" value="{{ old('fecha_contratacion', optional($user->empleado->fecha_contratacion)->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-6">
                  <label for="salario" class="form-label">Salario ($) <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" id="salario" name="salario" value="{{ old('salario', $user->empleado->salario) }}" step="0.01" min="0" required>
                </div>

                <div class="col-md-6">
                  <label for="turno" class="form-label">Turno <span class="text-danger">*</span></label>
                  <select class="form-select" id="turno" name="turno" required>
                    <option value="">Seleccionar turno</option>
                    <option value="matutino" {{ old('turno', $user->empleado->turno) == 'matutino' ? 'selected' : '' }}>Matutino</option>
                    <option value="vespertino" {{ old('turno', $user->empleado->turno) == 'vespertino' ? 'selected' : '' }}>Vespertino</option>
                    <option value="nocturno" {{ old('turno', $user->empleado->turno) == 'nocturno' ? 'selected' : '' }}>Nocturno</option>
                    <option value="mixto" {{ old('turno', $user->empleado->turno) == 'mixto' ? 'selected' : '' }}>Mixto</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                  <select class="form-select" id="estado" name="estado" required>
                    <option value="activo" {{ old('estado', $user->empleado->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado', $user->empleado->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    <option value="vacaciones" {{ old('estado', $user->empleado->estado) == 'vacaciones' ? 'selected' : '' }}>Vacaciones</option>
                    <option value="licencia" {{ old('estado', $user->empleado->estado) == 'licencia' ? 'selected' : '' }}>Licencia</option>
                  </select>
                </div>

                <div class="col-12">
                  <label for="observaciones" class="form-label">Observaciones</label>
                  <textarea class="form-control" id="observaciones" name="observaciones" rows="3">{{ old('observaciones', $user->empleado->observaciones) }}</textarea>
                </div>
              </div>
            </section>

            <div class="d-flex justify-content-center gap-3 mt-4">
              <button type="submit" class="btn btn-primary" id="submitBtn">
                <i class="fas fa-save me-2"></i> Guardar Cambios
              </button>
              <a href="{{ route('admin.usuarios') }}" class="btn btn-outline-muted">
                <i class="fas fa-arrow-left me-1"></i> Volver al Listado
              </a>
            </div>
          </form>
        </div>
      </article>
    </main>
  </div>
</body>
</html>
