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
                    <th><i class="fas fa-hashtag"></i> # Reserva</th>
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

      <!-- Las otras secciones (checkin, checkout, servicios, cerrar) se mantienen igual -->
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
            <tbody id="cuerpoTablaServicios">
              <!-- Los servicios se cargarán aquí dinámicamente -->
            </tbody>
          </table>
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
          } else if (targetId === 'servicios') {
            cargarServicios();
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
      
      // Aquí deberías hacer una llamada AJAX a tu backend para obtener las reservas del día
      // Ejemplo con fetch:
      /*
      fetch('/api/reservas-del-dia')
        .then(response => response.json())
        .then(reservas => {
          mostrarReservasEnTabla(reservas);
        })
        .catch(error => {
          console.error('Error al cargar reservas:', error);
          tbody.innerHTML = `
            <tr>
              <td colspan="7" class="text-center text-danger py-4">
                <i class="fas fa-exclamation-triangle me-2"></i>Error al cargar las reservas
              </td>
            </tr>
          `;
        });
      */
      
      // Por ahora, dejamos la tabla vacía para que se llene con datos reales
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

    // Función para mostrar las reservas en la tabla (se llamará desde la respuesta AJAX)
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
        alert('Por favor selecciona ambas fechas.');
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

      // Aquí deberías hacer una llamada AJAX a tu backend para obtener los datos de ocupación
      // Ejemplo con fetch:
      /*
      fetch(`/api/ocupacion?inicio=${inicio}&fin=${fin}`)
        .then(response => response.json())
        .then(resultados => {
          mostrarResultadosOcupacion(resultados);
        })
        .catch(error => {
          console.error('Error al cargar ocupación:', error);
          tbody.innerHTML = `
            <tr>
              <td colspan="5" class="text-center text-danger py-4">
                <i class="fas fa-exclamation-triangle me-2"></i>Error al cargar la ocupación
              </td>
            </tr>
          `;
        });
      */
      
      // Por ahora, dejamos la tabla vacía para que se llene con datos reales
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

    // Función para mostrar los resultados de ocupación (se llamará desde la respuesta AJAX)
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

    // Función para cargar servicios desde la base de datos
    function cargarServicios() {
      const tbody = document.getElementById('cuerpoTablaServicios');
      
      // Mostrar indicador de carga
      tbody.innerHTML = `
        <tr>
          <td colspan="4" class="text-center text-muted py-4">
            <i class="fas fa-spinner fa-spin me-2"></i>Cargando servicios...
          </td>
        </tr>
      `;
      
      // Aquí deberías hacer una llamada AJAX a tu backend para obtener los servicios
      // Ejemplo con fetch:
      /*
      fetch('/api/servicios')
        .then(response => response.json())
        .then(servicios => {
          mostrarServiciosEnTabla(servicios);
        })
        .catch(error => {
          console.error('Error al cargar servicios:', error);
          tbody.innerHTML = `
            <tr>
              <td colspan="4" class="text-center text-danger py-4">
                <i class="fas fa-exclamation-triangle me-2"></i>Error al cargar los servicios
              </td>
            </tr>
          `;
        });
      */
      
      // Por ahora, dejamos la tabla vacía para que se llene con datos reales
      setTimeout(() => {
        tbody.innerHTML = `
          <tr>
            <td colspan="4" class="text-center text-muted py-4">
              <i class="fas fa-concierge-bell me-2"></i>No hay servicios pendientes
            </td>
          </tr>
        `;
      }, 1000);
    }

    // Función para mostrar servicios en la tabla (se llamará desde la respuesta AJAX)
    function mostrarServiciosEnTabla(servicios) {
      const tbody = document.getElementById('cuerpoTablaServicios');
      tbody.innerHTML = '';
      
      if (servicios.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="4" class="text-center text-muted py-4">
              <i class="fas fa-concierge-bell me-2"></i>No hay servicios pendientes
            </td>
          </tr>
        `;
        return;
      }

      servicios.forEach(servicio => {
        const estadoClass = servicio.estado === 'Pendiente' ? 'estado-pendiente' : 'estado-completado';
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${servicio.habitacion}</td>
          <td>${servicio.servicio}</td>
          <td><span class="${estadoClass}">${servicio.estado}</span></td>
          <td>
            <button class="btn-completar-servicio" ${servicio.estado === 'Completado' ? 'disabled' : ''}>
              ${servicio.estado === 'Completado' ? 'Listo ✓' : 'Marcar como Listo'}
            </button>
          </td>
        `;
        tbody.appendChild(tr);
      });

      // Agregar event listeners a los botones de completar servicio
      document.querySelectorAll('.btn-completar-servicio').forEach(btn => {
        btn.addEventListener('click', () => {
          const fila = btn.closest('tr');
          const habitacion = fila.cells[0].textContent;
          
          // Aquí deberías hacer una llamada AJAX para marcar el servicio como completado
          /*
          fetch(`/api/servicios/${habitacion}/completar`, { method: 'POST' })
            .then(response => response.json())
            .then(resultado => {
              if (resultado.exito) {
                fila.querySelector('.estado-pendiente').textContent = 'Completado';
                fila.querySelector('.estado-pendiente').classList.replace('estado-pendiente', 'estado-completado');
                btn.textContent = 'Listo ✓';
                btn.disabled = true;
              }
            })
            .catch(error => {
              console.error('Error al completar servicio:', error);
              alert('Error al marcar el servicio como completado');
            });
          */
          
          // Por ahora, solo actualizamos la interfaz
          fila.querySelector('.estado-pendiente').textContent = 'Completado';
          fila.querySelector('.estado-pendiente').classList.replace('estado-pendiente', 'estado-completado');
          btn.textContent = 'Listo ✓';
          btn.disabled = true;
        });
      });
    }

    // Función para cargar estadísticas en la sección de inicio
    function cargarEstadisticasInicio() {
      // Aquí deberías hacer una llamada AJAX a tu backend para obtener las estadísticas
      // Ejemplo con fetch:
      /*
      fetch('/api/estadisticas')
        .then(response => response.json())
        .then(estadisticas => {
          document.getElementById('reservasPendientes').textContent = estadisticas.reservasPendientes;
          document.getElementById('habitacionesDisponibles').textContent = estadisticas.habitacionesDisponibles;
        })
        .catch(error => {
          console.error('Error al cargar estadísticas:', error);
        });
      */
    }

    // Cargar datos al iniciar si estamos en esa sección
    document.addEventListener('DOMContentLoaded', function() {
      if (document.getElementById('reservas').classList.contains('visible')) {
        cargarReservasDelDia();
      } else if (document.getElementById('servicios').classList.contains('visible')) {
        cargarServicios();
      } else if (document.getElementById('inicio').classList.contains('visible')) {
        cargarEstadisticasInicio();
      }
    });
  </script>
</body>
</html>