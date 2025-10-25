<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inicio de Sesión</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  @vite(['resources/css/estilo2.css'])
</head>
<body>
  <main class="login-box text-center">
    <img src="{{ asset('/img/logo.png') }}" alt="Logo2" class="logo-regis">
    <h2 class="mb-4">Iniciar Sesión</h2>
    <form id="formLogin">
      <div class="mb-3 text-start">
        <label for="nombre" class="form-label"><strong>Nombre</strong></label>
        <input type="text" class="form-control" id="nombre" placeholder="Ingresa tu nombre" required />
      </div>

      <div class="mb-3 text-start">
        <label for="email" class="form-label"><strong>Correo Electrónico</strong></label>
        <input type="email" class="form-control" id="email" placeholder="correo@ejemplo.com" required />
      </div>

     <form onsubmit="event.preventDefault(); window.location.href='Huesped.html';">
  <a href="Huesped.html" class="btn btn-primary w-100">
  <i class="fa fa-sign-in-alt"></i> Iniciar
</a>
</form>
    </form>
  </main>
</body>
</html>
