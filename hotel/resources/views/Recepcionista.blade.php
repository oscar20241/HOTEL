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
  <!-- Toast Container para mensajes Bootstrap -->
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <i class="fas fa-info-circle me-2 text-primary"></i>
        <strong class="me-auto">Sistema Hotel</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body" id="toastMessage">
        Mensaje del sistema
      </div>
    </div>
  </div>

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
            
            <!-- TABLA DE RESULTADOS DE FILTRADO -->
            <div class="mt-4" id="resultadosFiltro" style="display: none;">
              <h6>Resultados de Ocupación:</h6>
              <div class="table-responsive">
                <table class="table table-striped table-hover" id="tablaOcupacion">
                  <thead class="table-dark">
                    <tr>
                      <th>Habitación</th>
                      <th>Estado</th>
                      <th>Huésped</th>
                      <th>Entrada</th>
                      <th>Salida</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Los resultados se cargarán aquí dinámicamente -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="card info-card">
              <div class="card-body">
                <h5 class="card-title"><i class="fas fa-calendar-day text-warning"></i> Reservas Pendientes</h5>
                <p class="card-text fs-4" id="reservasPendientes">0</p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card info-card">
              <div class="card-body">
                <h5 class="card-title"><i class="fas fa-bed text-warning"></i> Habitaciones Disponibles</h5>
                <p class="card-text fs-4" id="habitacionesDisponibles">0</p>
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
          <form class="row g-3" id="formNuevaReserva">
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
              <label class="form-label">Personas:</label>
              <input type="number" class="form-control" min="1" max="8" placeholder="Ej. 2" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Teléfono:</label>
              <input type="tel" class="form-control" placeholder="Ej. 555-1234" required>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2><i class="fas fa-calendar-day text-primary"></i> Reservaciones del Día</h2>
          <button class="btn btn-success" onclick="cargarReservasDelDia()">
            <i class="fas fa-sync-alt"></i> Actualizar
          </button>
        </div>
        
        <div class="card shadow-sm">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover" id="tablaReservasDia">
                <thead class="table-primary">
                  <tr>
                    <th><i class="fas fa-hashtag"></i> Reserva</th>
                    <th><i class="fas fa-user"></i> Huésped</th>
                    <th><i class="fas fa-bed"></i> Habitación</th>
                    <th><i class="fas fa-calendar-check"></i> Check-In</th>
                    <th><i class="fas fa-calendar-times"></i> Check-Out</th>
                    <th><i class="fas fa-tag"></i> Estado</th>
                    <th><i class="fas fa-cog"></i> Acciones</th>
                  </tr>
                </thead>
                <tbody id="cuerpoTablaReservas">
                  <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                      <i class="fas fa-spinner fa-spin me-2"></i>Cargando reservas del día...
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div id="checkin" class="seccion">
        <h2>Check-In</h2>
        <form class="row g-3 mt-3" id="formCheckIn">
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

      <div id="cerrar" class="seccion">
        <h2>Cerrar sesión</h2>
        <p>¿Estás seguro que deseas salir?</p>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-sign-out-alt"></i> Confirmar
          </button>
        </form>
        <button class="btn btn-secondary mt-2" onclick="mostrarSeccion('inicio')">
          Cancelar
        </button>
      </div>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Navegación entre secciones
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
          
          // Cargar datos cuando se muestre la sección de reservas
          if (targetId === 'reservas') {
            cargarReservasDelDia();
          } else if (targetId === 'inicio') {
            cargarEstadisticasInicio();
          }
        }
      });
    });

    function mostrarSeccion(seccionId) {
      links.forEach(l => l.classList.remove('active'));
      secciones.forEach(sec => sec.classList.remove('visible'));
      
      const inicioLink = document.querySelector('[data-target="inicio"]');
      if (inicioLink) {
        inicioLink.classList.add('active');
      }
      
      const seccion = document.getElementById(seccionId);
      if (seccion) {
        seccion.classList.add('visible');
      }
    }

    // Función para mostrar toast de Bootstrap
    function mostrarToast(mensaje, tipo = 'info') {
      const toastEl = document.getElementById('liveToast');
      const toastMessage = document.getElementById('toastMessage');
      const toastHeader = toastEl.querySelector('.toast-header');
      
      // Configurar color según el tipo
      let iconClass = 'fas fa-info-circle me-2 text-primary';
      let headerClass = 'bg-primary text-white';
      
      switch(tipo) {
        case 'error':
          iconClass = 'fas fa-exclamation-triangle me-2 text-danger';
          headerClass = 'bg-danger text-white';
          break;
        case 'success':
          iconClass = 'fas fa-check-circle me-2 text-success';
          headerClass = 'bg-success text-white';
          break;
        case 'warning':
          iconClass = 'fas fa-exclamation-circle me-2 text-warning';
          headerClass = 'bg-warning text-dark';
          break;
      }
      
      // Actualizar contenido
      toastHeader.className = `toast-header ${headerClass}`;
      toastHeader.querySelector('i').className = iconClass;
      toastMessage.textContent = mensaje;
      
      // Mostrar toast
      const toast = new bootstrap.Toast(toastEl);
      toast.show();
    }

    // Función para cargar reservas del día desde la base de datos
    function cargarReservasDelDia() {
      const tbody = document.getElementById('cuerpoTablaReservas');
      
      // Mostrar indicador de carga
      tbody.innerHTML = `
        <tr>
          <td colspan="7" class="text-center text-muted py-4">
            <i class="fas fa-spinner fa-spin me-2"></i>Cargando reservas del día...
          </td>
        </tr>
      `;
      
      // Simular carga de datos
      setTimeout(() => {
        tbody.innerHTML = `
          <tr>
            <td colspan="7" class="text-center text-muted py-4">
              <i class="fas fa-calendar-times me-2"></i>No hay reservas para hoy
            </td>
          </tr>
        `;
      }, 1000);
    }

    // Función para mostrar las reservas en la tabla
    function mostrarReservasEnTabla(reservas) {
      const tbody = document.getElementById('cuerpoTablaReservas');
      tbody.innerHTML = '';
      
      if (reservas.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="7" class="text-center text-muted py-4">
              <i class="fas fa-calendar-times me-2"></i>No hay reservas para hoy
            </td>
          </tr>
        `;
        return;
      }

      reservas.forEach(reserva => {
        const badgeClass = reserva.estado === 'Confirmada' ? 'bg-success' : 'bg-warning';
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td><strong>${reserva.id}</strong></td>
          <td>${reserva.huesped}</td>
          <td><span class="badge bg-primary">${reserva.habitacion}</span></td>
          <td>${reserva.checkin}</td>
          <td>${reserva.checkout}</td>
          <td><span class="badge ${badgeClass}">${reserva.estado}</span></td>
          <td>
            <button class="btn btn-sm btn-outline-primary" title="Ver detalles">
              <i class="fas fa-eye"></i>
            </button>
            <button class="btn btn-sm btn-outline-success" title="Check-In">
              <i class="fas fa-sign-in-alt"></i>
            </button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    // Filtrado de ocupación
    document.getElementById('btnFiltrar').addEventListener('click', () => {
      const inicio = document.getElementById('fechaInicio').value;
      const fin = document.getElementById('fechaFin').value;
      const resultadosDiv = document.getElementById('resultadosFiltro');

      if (!inicio || !fin) {
        mostrarToast('Por favor selecciona ambas fechas.', 'warning');
        return;
      }

      const tbody = document.querySelector('#tablaOcupacion tbody');
      
      // Mostrar indicador de carga
      tbody.innerHTML = `
        <tr>
          <td colspan="5" class="text-center text-muted py-4">
            <i class="fas fa-spinner fa-spin me-2"></i>Buscando ocupación...
          </td>
        </tr>
      `;
      
      resultadosDiv.style.display = 'block';

      // Simular búsqueda
      setTimeout(() => {
        tbody.innerHTML = `
          <tr>
            <td colspan="5" class="text-center text-muted py-4">
              <i class="fas fa-search me-2"></i>No se encontraron resultados para el rango de fechas seleccionado
            </td>
          </tr>
        `;
      }, 1000);
    });

    // Función para mostrar los resultados de ocupación
    function mostrarResultadosOcupacion(resultados) {
      const tbody = document.querySelector('#tablaOcupacion tbody');
      tbody.innerHTML = '';

      if (resultados.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="5" class="text-center text-muted py-4">
              <i class="fas fa-search me-2"></i>No se encontraron resultados para el rango de fechas seleccionado
            </td>
          </tr>
        `;
        return;
      }

      resultados.forEach(r => {
        const badgeClass = r.estado === 'Ocupada' ? 'bg-danger' : 'bg-success';
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${r.habitacion}</td>
          <td><span class="badge ${badgeClass}">${r.estado}</span></td>
          <td>${r.huesped}</td>
          <td>${r.entrada}</td>
          <td>${r.salida}</td>
        `;
        tbody.appendChild(tr);
      });
    }

    // Función para cargar estadísticas en la sección de inicio
    function cargarEstadisticasInicio() {
      // Aquí deberías hacer una llamada AJAX a tu backend para obtener las estadísticas
      // Por ahora, solo valores de ejemplo
      document.getElementById('reservasPendientes').textContent = '5';
      document.getElementById('habitacionesDisponibles').textContent = '12';
    }

    // Agregar funcionalidad a los botones de checkout
    document.querySelector('.btn-liberar')?.addEventListener('click', function() {
      const roomNumber = document.getElementById('roomNumber').value;
      if (!roomNumber) {
        mostrarToast('Por favor ingresa un número de habitación', 'warning');
        return;
      }
      mostrarToast(`Habitación ${roomNumber} liberada exitosamente`, 'success');
      // Aquí iría la lógica para liberar la habitación
    });

    document.querySelector('.btn-checkout')?.addEventListener('click', function() {
      const roomNumber = document.getElementById('roomNumber').value;
      if (!roomNumber) {
        mostrarToast('Por favor ingresa un número de habitación', 'warning');
        return;
      }
      mostrarToast(`Check-out confirmado para habitación ${roomNumber}`, 'success');
      // Aquí iría la lógica para procesar el check-out
    });

    // Manejo de formularios
    document.getElementById('formNuevaReserva')?.addEventListener('submit', function(e) {
      e.preventDefault();
      mostrarToast('Reservación guardada exitosamente', 'success');
      // Aquí iría la lógica para guardar la reservación
    });

    document.getElementById('formCheckIn')?.addEventListener('submit', function(e) {
      e.preventDefault();
      mostrarToast('Check-in registrado exitosamente', 'success');
      // Aquí iría la lógica para registrar el check-in
    });

    // Cargar datos al iniciar si estamos en esa sección
    document.addEventListener('DOMContentLoaded', function() {
      if (document.getElementById('reservas').classList.contains('visible')) {
        cargarReservasDelDia();
      } else if (document.getElementById('inicio').classList.contains('visible')) {
        cargarEstadisticasInicio();
      }
    });
  </script>
</body>
</html>