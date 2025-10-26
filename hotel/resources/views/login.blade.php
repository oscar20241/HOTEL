<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inicio de Sesión - Hotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
   
</head>
<body>
  <main class="login-box text-center" style="max-width: 400px; margin: 100px auto; padding: 20px;">
    <img src="{{ asset('/img/logo.png') }}" alt="Logo" class="logo-regis" style="max-width: 150px;">
    <h2 class="mb-4">Iniciar Sesión</h2>
<<<<<<< HEAD
    <form id="formLogin">
      <div class="mb-3 text-start">
        <label for="email" class="form-label"><strong>Correo Electrónico</strong></label>
        <input type="email" class="form-control" id="email" placeholder="correo@ejemplo.com" required />
=======
    
    <!-- Mostrar errores -->
    @if ($errors->any())
      <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
>>>>>>> d3a78b76a17d842439eea092664b7c7eb0f5309e
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf
      
      <div class="mb-3 text-start">
<<<<<<< HEAD
        <label for="password" class="form-label"><strong>Contraseña</strong></label>
        <input type="password" class="form-control" id="password" placeholder="Su Contraseña" required />
=======
        <label for="email" class="form-label"><strong>Correo Electrónico</strong></label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="correo@ejemplo.com" required autofocus />
>>>>>>> d3a78b76a17d842439eea092664b7c7eb0f5309e
      </div>

      <div class="mb-3 text-start">
        <label for="password" class="form-label"><strong>Contraseña</strong></label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Su Contraseña" required />
      </div>

      <button type="submit" class="btn btn-primary w-100">
        <i class="fa fa-sign-in-alt"></i> Iniciar Sesión
      </button>

<!-- Botón de registro destacado -->
<div class="mt-3 text-center">
    <a href="{{ route('registro') }}" class="btn btn-success w-100">
        <i class="fas fa-user-plus"></i> Regístrate Aquí
    </a>
</div>

    </form>
    
    <!-- Datos de prueba -->
    <div class="mt-4 p-3 bg-light rounded">
      <h6>Datos de prueba:</h6>
      <p class="mb-1"><strong>Admin:</strong> admin@hotel.com / password</p>
      <p class="mb-1"><strong>Recepcionista:</strong> recepcion@hotel.com / password</p>
      <p class="mb-0"><strong>Huésped:</strong> huesped@ejemplo.com / password</p>
    </div>
  </main>
</body>
</html>