<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Recepcionista | Pasa el Extra Inn</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/a2d04a4f5d.js" crossorigin="anonymous"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  @vite(['resources/css/estilo.css'])
  @vite(['resources/css/recepcionista.css'])
</head>
<body>
  <div class="dashboard-container d-flex">
    <aside class="sidebar p-3">
      <img src="{{ asset('/img/logo.png') }}" alt="Logo del hotel" class="logo-dash mb-3">
      <h4 class="text-center mb-4">Recepcionista</h4>
      <nav class="nav flex-column w-100">
        <a href="#" class="nav-link active" data-target="inicio"><i class="fas fa-home"></i> Inicio</a>
         <a href="#" class="nav-link" data-target="nueva-reserva"><i class="fas fa-plus-circle"></i> Nueva Reservación</a>
        <a href="#" class="nav-link" data-target="reservas"><i class="fas fa-calendar-day"></i> Reservaciones del Día</a>
        <a href="#" class="nav-link" data-target="checkin"><i class="fas fa-sign-in-alt"></i> Check-In</a>
        <a href="#" class="nav-link" data-target="checkout"><i class="fas fa-sign-out-alt"></i> Check-Out</a>
        <a href="#" class="nav-link" data-target="servicios"><i class="fas fa-concierge-bell"></i> Servicios</a>
        <a href="#" class="nav-link text-danger mt-auto" data-target="cerrar"><i class="fas fa-door-open"></i> Cerrar sesión</a>
      </nav>
    </aside>

    <main class="main-content flex-grow-1 p-4">
      <div id="inicio" class="seccion visible">
        <h2>Panel del Recepcionista</h2>
        <div class="row g-4 mt-3">
          <div class="card mt-4 p-3 shadow-sm">
  <h5><i class="fas fa-filter text-warning"></i> Filtrar Fechas de Ocupación</h5>

  <div class="row g-3 align-items-end mt-2">
    <div class="col-md-4">
      <label for="fechaInicio" class="form-label">Desde:</label>
      <input type="date" id="fechaInicio" class="form-control" />
    </div>
    <div class="col-md-4">
      <label for="fechaFin" class="form-label">Hasta:</label>
      <input type="date" id="fechaFin" class="form-control" />
    </div>
    <div class="col-md-4">
      <button id="btnFiltrar" class="btn btn-primary w-100">
        <i class="fas fa-search"></i> Buscar Ocupación
      </button>
    </div>
  </div>
          <div class="col-md-6">
            <div class="card info-card">
              <div class="card-body">
                <h5 class="card-title"><i class="fas fa-calendar-day text-warning"></i> Reservas Pendientes</h5>
                <p class="card-text fs-4">8</p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card info-card">
              <div class="card-body">
                <h5 class="card-title"><i class="fas fa-bed text-warning"></i> Habitaciones Disponibles</h5>
                <p class="card-text fs-4">6</p>
              </div>
            </div>
          </div>
        </div>
      </div>
 <div id="nueva-reserva" class="seccion">
  <h2><i class="fas fa-plus-circle text-warning"></i> Generar Nueva Reservación</h2>
  <p>Completa el formulario para registrar una nueva reserva.</p>

  <div class="card mt-4 p-3 shadow-sm">
    <h5 class="mb-3"><i class="fas fa-edit text-warning"></i> Formulario de Registro</h5>
    <form class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Nombre del Huésped:</label>
        <input type="text" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Fecha de Entrada:</label>
        <input type="date" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Fecha de Salida:</label>
        <input type="date" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Tipo de Habitación:</label>
        <select class="form-select" required>
          <option value="">Seleccione...</option>
          <option>Individual</option>
          <option>Doble</option>
          <option>Suite</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Adultos:</label>
        <input type="number" class="form-control" min="1" max="8" placeholder="Ej. 2" required>
      </div>
       <div class="col-md-6">
        <label class="form-label">Niños:</label>
        <input type="number" class="form-control" min="1" max="8" placeholder="Ej. 2" required>
      </div>
      <div class="col-12">
        <label class="form-label">Comentarios o Solicitudes Especiales:</label>
        <textarea class="form-control" rows="2" placeholder="Ej. Solicita cama adicional..."></textarea>
      </div>
      <div class="col-12 text-end mt-3">
      <button type="submit" class="btn btn-warning text-dark fw-semibold">
  <i class="fas fa-save"></i> Guardar Reservación
</button>
      </div>
    </form>
  </div>
</div>
      <div id="reservas" class="seccion">
        <h2>Reservaciones del Día</h2>
        <p>Listado de todas las reservas programadas para hoy.</p>
      </div>

      <div id="checkin" class="seccion">
        <h2>Check-In</h2>
        <form class="row g-3 mt-3">
          <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Nombre del huésped" required />
          </div>
          <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Número de habitación" required />
          </div>
          <div class="col-12">
            <button class="btn btn-primary" type="submit"><i class="fas fa-check"></i> Registrar</button>
          </div>
        </form>
      </div>

      <div id="checkout" class="seccion">
  <h2>Check-Out</h2>
  <p>Procesa la salida de huéspedes y libera habitaciones.</p>

  <div class="checkout-container">
    <input 
      type="text" 
      id="roomNumber" 
      class="checkout-input" 
      placeholder="Buscar número de habitación..."
    />

    <div class="checkout-buttons">
      <button class="btn-liberar">Liberar Habitación</button>
      <button class="btn-checkout">Confirmar Check-Out</button>
    </div>
  </div>
</div>


     <div id="servicios" class="seccion">
  <h2>Servicios</h2>
  <p>Gestión de servicios activos y solicitudes especiales.</p>

  <div class="servicios-container">
    <input 
      type="text" 
      id="buscarServicio" 
      class="servicios-input" 
      placeholder="Buscar habitación..."
    />

    <table class="tabla-servicios">
      <thead>
        <tr>
          <th>Número de Habitación</th>
          <th>Servicio Requerido</th>
          <th>Estado</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>101</td>
          <td>Limpieza</td>
          <td><span class="estado-pendiente">Pendiente</span></td>
          <td><button class="btn-completar-servicio">Marcar como Listo</button></td>
        </tr>
        <tr>
          <td>203</td>
          <td>Mantenimiento de aire acondicionado</td>
          <td><span class="estado-pendiente">Pendiente</span></td>
          <td><button class="btn-completar-servicio">Marcar como Listo</button></td>
        </tr>
        <tr>
          <td>305</td>
          <td>Reemplazo de toallas</td>
          <td><span class="estado-completado">Completado</span></td>
          <td><button class="btn-completar-servicio" disabled>Listo ✓</button></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

      <!-- Sección de cierre de sesión - MANTENIENDO TU DISEÑO ORIGINAL -->
      <div id="cerrar" class="seccion">
        <h2>Cerrar sesión</h2>
        <p>¿Estás seguro que deseas salir?</p>
        
        <!-- Formulario funcional de logout -->
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-sign-out-alt"></i> Confirmar
          </button>
        </form>
        
        <!-- Botón para cancelar y volver al inicio -->
        <button class="btn btn-secondary mt-2" onclick="mostrarSeccion('inicio')">
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
  <script>
  document.querySelectorAll('.btn-completar-servicio').forEach(btn => {
    btn.addEventListener('click', () => {
      const fila = btn.closest('tr');
      fila.querySelector('.estado-pendiente').textContent = 'Completado';
      fila.querySelector('.estado-pendiente').classList.replace('estado-pendiente', 'estado-completado');
      btn.textContent = 'Listo ✓';
      btn.disabled = true;
    });
  });
</script>
<script>
document.getElementById('btnFiltrar').addEventListener('click', () => {
  const inicio = document.getElementById('fechaInicio').value;
  const fin = document.getElementById('fechaFin').value;

  if (!inicio || !fin) {
    alert('Por favor selecciona ambas fechas.');
    return;
  }

  // Aquí simulo resultados (luego lo conectarás con Laravel/AJAX)
  const resultados = [
    { habitacion: '101', estado: 'Ocupada', huesped: 'Juan Pérez', entrada: '2025-10-27', salida: '2025-10-30' },
    { habitacion: '203', estado: 'Libre', huesped: '-', entrada: '2026-01-02', salida: '2026-01-18' },
    { habitacion: '305', estado: 'Ocupada', huesped: 'María López', entrada: '2025-10-28', salida: '2025-11-02' }
  ];

  const tbody = document.querySelector('#tablaOcupacion tbody');
  tbody.innerHTML = '';

  resultados.forEach(r => {
    // Solo mostramos las ocupadas dentro del rango
    if (r.estado === 'Ocupada') {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${r.habitacion}</td>
        <td><span class="badge bg-danger">${r.estado}</span></td>
        <td>${r.huesped}</td>
        <td>${r.entrada}</td>
        <td>${r.salida}</td>
      `;
      tbody.appendChild(tr);
    }
  });
});
</script>
</body>
</html>