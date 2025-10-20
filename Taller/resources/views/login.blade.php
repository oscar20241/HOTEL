<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
       <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
       @vite(['resources/css/estilo.css', 'resources/js/app.js'])
       <img src="{{ asset('IMG/logo.png') }}" alt="Logo de registro" Class="logo-registro">
    <title>Inicio de Sesión</title>
</head>
<body>
    <div class="registro-container">
        <h2 class="text-center">Iniciar Sesión</h2>
        <form action="#" method="get">
           <div class="mb-3">
        <label for="nombre" class="form-label"><strong>Nombre</strong></label>
        <input type="text" class="form-control" id="nombre" placeholder="Ingresa tu nombre" required>
      </div>

          <div class="mb-3">
                <label for="email" class="form-label"><strong>Correo Electrónico</strong></label>
                <input type="email" name="email" class="form-control" id="email" placeholder="Ingresa tu Correo" required>
            </div>
            <button type="submit" class="btn btn-primary mb-3">Iniciar Sesión</button>
            <a href="{{ route('registro') }}" class="btn btn-link mb-2">¿No tienes una cuenta? Regístrate</a>
        </form>
    </div>
</body>
</html>
