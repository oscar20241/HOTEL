<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reservación - Pasa el Extra Inn</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  @vite(['resources/css/estilo2.css'])
  <script src="https://kit.fontawesome.com/a2d04a4f5d.js" crossorigin="anonymous"></script>
</head>

<body>
  <img src="{{ asset('/img/logo.png') }}" alt="Logo" class="logo">
  <img src="logo.png" alt="Logo" class="logo">
  <div class="dashboard-container">
    <!-- Sidebar -->
    <!-- Contenido principal -->
    <main class="main-content">
      <div class="reservacion-container">
        <h2 class="text-center mb-4">Reservación de Habitación</h2>

        <!-- Alerta de confirmación -->
        <div class="alert alert-success d-none" id="alertaReserva">
          ¡Su reservación ha sido registrada con éxito!
        </div>

        <!-- Formulario -->
        <form id="formReservacion">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre y Apellido</label>
            <input type="text" class="form-control" id="nombre" placeholder="Ej. Juan Pérez" required>
          </div>

          <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo" placeholder="correo@ejemplo.com" required>
          </div>

          <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="tel" class="form-control" id="telefono" placeholder="Ej. 555-123-4567" required>
          </div>

          <div class="mb-3">
            <label for="habitacion" class="form-label">Tipo de Habitación</label>
            <select class="form-select" id="habitacion" required>
              <option value="">Seleccione una opción</option>
              <option value="sencilla">Sencilla - $2,200 / noche</option>
              <option value="doble">Doble - $4,800 / noche</option>
              <option value="suite">Suite - $7,800 / noche</option>
            </select>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="fechaEntrada" class="form-label">Fecha de Entrada</label>
              <input type="date" class="form-control" id="fechaEntrada" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="fechaSalida" class="form-label">Fecha de Salida</label>
              <input type="date" class="form-control" id="fechaSalida" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="comentarios" class="form-label">Comentarios o Peticiones Especiales</label>
            <textarea class="form-control" id="comentarios" rows="3" placeholder="Ej. Habitación con vista al mar"></textarea>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary me-2">
              <i class="fas fa-check-circle"></i> Confirmar Reservación
            </button>
            <button type="reset" class="btn btn-secondary">
              <i class="fas fa-undo"></i> Limpiar Formulario
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>

 <script>
document.getElementById("formReservacion").addEventListener("submit", function(e) {
  e.preventDefault(); // Evita que el formulario se envíe inmediatamente

  // Mostrar alerta
  const alerta = document.getElementById("alertaReserva");
  alerta.classList.remove("d-none");

  // Limpiar formulario si quieres
  this.reset();

  // Redirigir después de 1.5 segundos
  setTimeout(() => {
    alerta.classList.add("d-none"); // Ocultar alerta
    window.location.href = "{{ route('registro') }}"; // Cambia a tu ruta
  }, 1500);
});
</script>
</body>
</html>