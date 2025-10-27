<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro - Pasa el Extra Inn</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  @vite(['resources/css/estilo2.css'])
  <script src="https://kit.fontawesome.com/a2d04a4f5d.js" crossorigin="anonymous"></script>
</head>

<body>
  <img src="{{ asset('/img/logo.png') }}" alt="Logo" class="logo">
  <div class="dashboard-container">
    <main class="main-content">
      <div class="reservacion-container">
        <h2 class="text-center mb-4">Registro de Usuario</h2>

        <!-- Mostrar errores -->
        @if ($errors->any())
          <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
              <p>{{ $error }}</p>
            @endforeach
          </div>
        @endif

        <!-- Mostrar éxito -->
        @if (session('success'))
          <div class="alert alert-success">
            {{ session('success') }}
          </div>
        @endif

        <!-- Formulario -->
        <form method="POST" action="{{ route('registro.store') }}" id="formReservacion">
    @csrf
          
          <div class="mb-3">
            <label for="name" class="form-label">Nombre Completo</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Ej. Juan Pérez" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="correo@ejemplo.com" required>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
          </div>

          <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmar contraseña" required>
          </div>

          <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="tel" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}" placeholder="Ej. 555-123-4567" required>
          </div>

          <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}" placeholder="Ej. Calle Principal #123">
          </div>

          <div class="mb-3">
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}">
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary me-2">
              <i class="fas fa-check-circle"></i> Registrarse
            </button>
            <a href="{{ route('login') }}" class="btn btn-secondary">
              <i class="fas fa-sign-in-alt"></i> Volver al Login
            </a>
          </div>
        </form>
      </div>
    </main>
  </div>
</body>
</html>