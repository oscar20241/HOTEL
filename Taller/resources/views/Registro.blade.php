<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Agendar Cita y Servicios</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  @vite(['resources/css/estilo.css', 'resources/js/app.js'])
</head>
<body>
  <main class="registro-container">
    <img src="{{ asset('IMG/logo.png') }}" alt="Logo de registro" Class="logo-registro">
    <h2 class="titulo-registro">Agendar Cita y Servicios</h2>

    <!-- Mensaje oculto al inicio -->
    <div id="alertaCita" class="alert alert-success text-center d-none" role="alert">
      <i class="bi bi-check-circle-fill"></i>
      Su cita ha sido agendada con exito.
    </div>

    <form id="formCita" class="registro-form">

      <div class="mb-3">
        <label for="nombre" class="form-label"><strong>Nombre</strong></label>
        <input type="text" class="form-control" id="nombre" placeholder="Ingresa tu nombre" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label"><strong>Correo Electrónico</strong></label>
        <input type="email" class="form-control" id="email" placeholder="correo@ejemplo.com" required>
      </div>

      <div class="mb-3">
        <label for="tel" class="form-label"><strong>Teléfono</strong></label>
        <input type="tel" class="form-control" id="tel" placeholder="Número de teléfono" required pattern="[0-9]{10}" inputmode="numeric" maxlength="10" title="Debe contener 10 dígitos numéricos">
      </div>

      <div class="mb-3">
        <label for="fecha" class="form-label"><strong>Fecha de Cita</strong></label>
        <input type="date" class="form-control" id="fecha" required>
      </div>

      <div class="mb-3">
        <h5><strong>Detalles del Servicio</strong></h5>
        <div class="mb-3">
          <label for="marca" class="form-label"><strong>Marca</strong></label>
          <input type="text" class="form-control" id="marca" placeholder="Marca del vehículo" required>
        </div>
        <label for="modelo" class="form-label"><strong>Modelo</strong></label>
        <input type="text" class="form-control" id="modelo" placeholder="Modelo del vehículo" required>
      </div>

      <div class="mb-3">
        <label for="servicio" class="form-label"><strong>Servicio</strong></label>
        <select class="form-select" id="servicio" required>
          <option value="" disabled selected>Selecciona un servicio</option>
          <option value="garantia">Garantía</option>
          <option value="aceite">Cambio de aceite y filtro</option>
          <option value="luces">Revisión de luces</option>
          <option value="bateria">Nivelar la batería</option>
          <option value="bujias">Cambio de bujías</option>
          <option value="clutch">Revisión de clutch</option>
          <option value="seguro">Contratar Seguro</option>
          <option value="otro">Otro</option>
        </select>
      </div>

      <div class="form-floating mb-3">
        <textarea class="form-control" placeholder="Describe brevemente el servicio" id="descripcion"></textarea>
        <label for="descripcion">Descripción Breve del Servicio</label>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary mb-2">
          <i class="bi bi-person-plus-fill"></i> Confirmar Cita
        </button>
        <button type="reset" class="btn btn-secondary">
          <i class="bi bi-x-circle"></i> Cancelar
        </button>
      </div>

    </form>
  </main>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script para mostrar el mensaje -->
  <script>
    document.getElementById("formCita").addEventListener("submit", function(event) {
      event.preventDefault(); // evita que recargue o cambie de página
      const alerta = document.getElementById("alertaCita");
      alerta.classList.remove("d-none"); // muestra el mensaje
      alerta.scrollIntoView({ behavior: "smooth" }); // hace scroll hacia el mensaje

      // Ocultar el mensaje después de 4 segundos
      setTimeout(() => {
        alerta.classList.add("d-none");
        // Redirigir después del mensaje si lo deseas
        window.location.href = "{{ route('registro') }}";
      }, 1500);
    });
  </script>
</body>
</html>