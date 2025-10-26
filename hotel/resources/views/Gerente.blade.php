<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gerente | Pasa el Extra Inn</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  @vite(['resources/css/estilo.css'])
  @vite(['resources/css/gerente.css'])
  <script src="https://kit.fontawesome.com/a2d04a4f5d.js" crossorigin="anonymous"></script>
  <!-- Font Awesome 5 (versión sólida) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
  <div class="dashboard-container d-flex">
    <!-- Sidebar -->
    <aside class="sidebar p-3">
      <img src="{{ asset('/img/logo.png') }}" alt="Logo del hotel" class="logo-dash mb-3">
      <h4 class="text-center mb-4">Gerente</h4>

     <nav class="nav flex-column w-100">
  <a href="#" class="nav-link active" data-target="inicio">
    <i class="fas fa-house-user"></i> Inicio
  </a>
  <a href="#" class="nav-link" data-target="reservas">
    <i class="fas fa-calendar-check"></i> Reservaciones
  </a>
  <a href="#" class="nav-link" data-target="habitaciones">
    <i class="fas fa-door-closed"></i> Habitaciones
  </a>
  <a href="#" class="nav-link" data-target="usuarios">
    <i class="fas fa-id-badge"></i> Usuarios
  </a>
  <a href="#" class="nav-link" data-target="reportes">
    <i class="fas fa-chart-bar"></i> Reportes
  </a>
  <a href="#" class="nav-link text-danger mt-auto" data-target="cerrar">
    <i class="fas fa-power-off"></i> Cerrar sesión
  </a>
</nav>

    </aside>

    <!-- Contenido principal -->
    <main class="main-content flex-grow-1 p-4">
      <!-- Sección: Inicio -->
      <div id="inicio" class="seccion visible">
        <h2>Panel de Control</h2>
        <div class="row g-4 mt-3">
          <div class="col-md-4">
            <div class="card info-card">
              <div class="card-body">
                <h5 class="card-title"><i class="fas fa-bed text-warning"></i> Habitaciones Ocupadas</h5>
                <p class="card-text fs-4">18 / 24</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card info-card">
              <div class="card-body">
                <h5 class="card-title"><i class="fas fa-calendar-check text-warning"></i> Reservas del Día</h5>
                <p class="card-text fs-4">12</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card info-card">
              <div class="card-body">
                <h5 class="card-title"><i class="fas fa-users text-warning"></i> Clientes Activos</h5>
                <p class="card-text fs-4">43</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sección: Reservas -->
      <div id="reservas" class="seccion">
  <h2>Reservaciones</h2>
  <p>Gestión completa de todas las reservas del hotel.</p>

  <div class="reservas-container">
    <input 
      type="text" 
      id="buscarReserva" 
      class="reservas-input" 
      placeholder="Buscar por nombre o número de habitación..."
    />

    <table class="tabla-reservas">
      <thead>
        <tr>
          <th># Habitación</th>
          <th>Huésped</th>
          <th>Check-In</th>
          <th>Check-Out</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody id="listaReservas">
        <!-- Las reservaciones se cargarán automáticamente aquí -->
      </tbody>
    </table>
  </div>
</div>
      <!-- Sección: Habitaciones -->
     <div id="habitaciones" class="seccion">
  <h2>Habitaciones</h2>
  <p>Listado y estado de todas las habitaciones disponibles y ocupadas.</p>

  <div class="habitaciones-container">
    <input 
      type="text" 
      id="buscarHabitacion" 
      class="habitaciones-input" 
      placeholder="Buscar habitación..."
    />

    <table class="tabla-habitaciones">
      <thead>
        <tr>
          <th># Habitación</th>
          <th>Tipo</th>
          <th>Precio por noche</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody id="listaHabitaciones">
        <!-- Las habitaciones se cargarán automáticamente aquí -->
      </tbody>
    </table>
  </div>
</div>
      <!-- Sección: Usuarios -->
      <div id="usuarios" class="seccion">
        <h2>Usuarios</h2>
        <p>Administración de cuentas de huéspedes y personal.</p>
      </div>

      <!-- Sección: Reportes -->
    <div id="reportes" class="seccion">
  <h2>Reportes</h2>
  <p>Estadísticas, ingresos y desempeño general del hotel.</p>

  <div class="reportes-container">
    <!-- 🔹 Tarjetas de resumen -->
    <div class="reportes-resumen">
      <div class="reporte-card">
        <h3>Ocupación Actual</h3>
        <p id="ocupacionPorcentaje">--%</p>
      </div>

      <div class="reporte-card">
        <h3>Ingresos del Mes</h3>
        <p id="ingresosMes">$-- MXN</p>
      </div>

      <div class="reporte-card">
        <h3>Reservas Activas</h3>
        <p id="reservasActivas">--</p>
      </div>
    </div>

    <!-- 🔹 Gráficas -->
    <div class="graficas-reportes">
      <canvas id="graficaOcupacion"></canvas>
      <canvas id="graficaIngresos"></canvas>
    </div>
  </div>
</div>
      <!-- Sección: Cerrar sesión - CON FORMULARIO FUNCIONAL -->
      <div id="cerrar" class="seccion">
        <h2>Cerrar sesión</h2>
        <p>¿Estás seguro que deseas salir?</p>
        
        <!-- Formulario funcional de logout -->
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-danger">
            Confirmar
          </button>
        </form>
        
        <!-- Botón para cancelar y volver al inicio -->
        <button class="btn btn-secondary" onclick="mostrarSeccion('inicio')">
          Cancelar
        </button>
      </div>
    </main>
  </div>

  <script>
    const links = document.querySelectorAll('.nav-link');
    const secciones = document.querySelectorAll('.seccion');

    links.forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        links.forEach(l => l.classList.remove('active'));
        link.classList.add('active');
        const targetId = link.getAttribute('data-target');
        secciones.forEach(sec => sec.classList.remove('visible'));
        const targetSection = document.getElementById(targetId);
        if (targetSection) {
          targetSection.classList.add('visible');
        }
      });
    });

    // Función para mostrar sección específica
    function mostrarSeccion(seccionId) {
      links.forEach(l => l.classList.remove('active'));
      secciones.forEach(sec => sec.classList.remove('visible'));
      
      // Activar el link correspondiente a "Inicio"
      const inicioLink = document.querySelector('[data-target="inicio"]');
      if (inicioLink) {
        inicioLink.classList.add('active');
      }
      
      const seccion = document.getElementById(seccionId);
      if (seccion) {
        seccion.classList.add('visible');
      }
    }
  </script>
</body>
</html>