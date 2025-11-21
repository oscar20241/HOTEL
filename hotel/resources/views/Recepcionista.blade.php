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
      <h5 class="card-title">
        <i class="fas fa-calendar-day text-warning"></i> Reservas Pendientes
      </h5>
      <p class="card-text fs-4" id="reservasPendientes">
        {{ $reservasPendientes ?? 0 }}
      </p>
    </div>
  </div>
</div>

<div class="col-md-6">
  <div class="card info-card">
    <div class="card-body">
      <h5 class="card-title">
        <i class="fas fa-bed text-warning"></i> Habitaciones Disponibles
      </h5>
      <p class="card-text fs-4" id="habitacionesDisponibles">
        {{ $habitacionesDisponibles ?? 0 }}
      </p>
    </div>
  </div>
</div>
      </div>
      </div>
      <div id="nueva-reserva" class="seccion">
        <h2><i class="fas fa-plus-circle text-warning"></i> Generar Nueva Reservación</h2>
        <p>Completa el formulario para registrar una nueva reserva.</p>
        @if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

@if (session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
@endif

        <div class="card mt-4 p-3 shadow-sm">
          <h5 class="mb-3"><i class="fas fa-edit text-warning"></i> Formulario de Registro</h5>
          <form class="row g-3" id="formNuevaReserva"
      method="POST"
      action="{{ route('recepcion.reservas.store') }}">
    @csrf

    {{-- Huésped --}}
    <div class="col-md-6">
      <label class="form-label">Huésped existente:</label>
      <select name="user_id" class="form-select" id="selectHuesped">
        <option value="">Seleccione huésped...</option>
        @foreach($huespedes as $huesped)
          <option value="{{ $huesped->id }}" {{ old('user_id') == $huesped->id ? 'selected' : '' }}>
            {{ $huesped->name }} ({{ $huesped->email }})
          </option>
        @endforeach
      </select>
      <small class="text-muted">Si el huésped no existe, habilita el registro rápido.</small>
    </div>

    <div class="col-md-6 d-flex align-items-end">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="toggleNuevoHuesped" {{ old('nuevo_nombre') || old('nuevo_email') ? 'checked' : '' }}>
        <label class="form-check-label" for="toggleNuevoHuesped">Registrar nuevo huésped</label>
      </div>
    </div>

    <div class="col-md-4">
      <label class="form-label">Nombre del nuevo huésped:</label>
      <input type="text" name="nuevo_nombre" id="nuevoNombre" class="form-control" value="{{ old('nuevo_nombre') }}" placeholder="Nombre completo" disabled>
    </div>

    <div class="col-md-4">
      <label class="form-label">Correo electrónico:</label>
      <input type="email" name="nuevo_email" id="nuevoEmail" class="form-control" value="{{ old('nuevo_email') }}" placeholder="correo@ejemplo.com" disabled>
    </div>

    <div class="col-md-4">
      <label class="form-label">Teléfono:</label>
      <input type="tel" name="nuevo_telefono" id="nuevoTelefono" class="form-control" value="{{ old('nuevo_telefono') }}" placeholder="Ej. 322-555-1234" disabled>
    </div>

    {{-- Fecha de entrada --}}
    <div class="col-md-3">
      <label class="form-label">Fecha de Entrada:</label>
      <input type="date" name="fecha_entrada"
             class="form-control"
             value="{{ old('fecha_entrada') }}"
             required>
    </div>

    {{-- Fecha de salida --}}
    <div class="col-md-3">
      <label class="form-label">Fecha de Salida:</label>
      <input type="date" name="fecha_salida"
             class="form-control"
             value="{{ old('fecha_salida') }}"
             required>
    </div>

    {{-- Tipo de habitación --}}
    <div class="col-md-6">
      <label class="form-label">Tipo de Habitación:</label>
      <select name="tipo_habitacion_id" class="form-select" id="selectTipoHabitacion" required>
        <option value="">Seleccione...</option>
        @foreach($tiposHabitacion as $tipo)
          <option value="{{ $tipo->id }}" data-capacidad="{{ $tipo->capacidad }}" {{ old('tipo_habitacion_id') == $tipo->id ? 'selected' : '' }}>
            {{ $tipo->nombre }} - Capacidad: {{ $tipo->capacidad }} - ${{ number_format($tipo->precio_base, 2) }}
          </option>
        @endforeach
      </select>
    </div>

    {{-- Adultos --}}
    <div class="col-md-3">
      <label class="form-label">Adultos:</label>
      <input type="number" name="adultos" id="inputAdultos" class="form-control"
             min="1" max="8"
             value="{{ old('adultos', 1) }}" required>
    </div>

    {{-- Niños --}}
    <div class="col-md-3">
      <label class="form-label">Niños:</label>
      <input type="number" name="ninos" id="inputNinos" class="form-control"
             min="0" max="8"
             value="{{ old('ninos', 0) }}">
    </div>

    <div class="col-12">
      <small class="text-muted" id="capacidadMensaje">Selecciona un tipo de habitación para ver la capacidad.</small>
    </div>

    {{-- Teléfono --}}
    <div class="col-md-6">
      <label class="form-label">Teléfono de contacto:</label>
      <input type="tel" name="telefono"
             class="form-control"
             placeholder="Ej. 322-555-1234"
             value="{{ old('telefono') }}">
    </div>

    {{-- Comentarios --}}
    <div class="col-12">
      <label class="form-label">Comentarios o Solicitudes Especiales:</label>
      <textarea name="notas" class="form-control" rows="2"
                placeholder="Ej. Solicita cama adicional...">{{ old('notas') }}</textarea>
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
  const csrfToken = '{{ csrf_token() }}';

  // ---------------- NAVEGACIÓN ENTRE SECCIONES ----------------
  const links = document.querySelectorAll('.nav-link');
  const secciones = document.querySelectorAll('.seccion');

  // ---------------- MODO HUÉSPED (existente o nuevo) ----------------
  const toggleNuevoHuesped = document.getElementById('toggleNuevoHuesped');
  const selectHuesped = document.getElementById('selectHuesped');
  const nuevoNombre = document.getElementById('nuevoNombre');
  const nuevoEmail = document.getElementById('nuevoEmail');
  const nuevoTelefono = document.getElementById('nuevoTelefono');

  function actualizarModoHuesped() {
    const registrarNuevo = toggleNuevoHuesped?.checked;

    [nuevoNombre, nuevoEmail, nuevoTelefono].forEach(campo => {
      if (!campo) return;
      campo.disabled = !registrarNuevo;
      campo.required = registrarNuevo && campo !== nuevoTelefono;
    });

    if (selectHuesped) {
      selectHuesped.disabled = registrarNuevo;
      selectHuesped.required = !registrarNuevo;
      if (registrarNuevo) {
        selectHuesped.value = '';
      }
    }
  }

  toggleNuevoHuesped?.addEventListener('change', actualizarModoHuesped);

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

  // ---------------- CAPACIDAD POR TIPO DE HABITACIÓN ----------------
  const selectTipoHabitacion = document.getElementById('selectTipoHabitacion');
  const inputAdultos = document.getElementById('inputAdultos');
  const inputNinos = document.getElementById('inputNinos');
  const capacidadMensaje = document.getElementById('capacidadMensaje');

  function obtenerCapacidadSeleccionada() {
    const opcion = selectTipoHabitacion?.selectedOptions[0];
    return opcion ? parseInt(opcion.dataset.capacidad || '0', 10) || 0 : 0;
  }

  function validarCapacidad() {
    const capacidad = obtenerCapacidadSeleccionada();
    const adultos = parseInt(inputAdultos?.value || '0', 10);
    const ninos = parseInt(inputNinos?.value || '0', 10);
    const total = adultos + ninos;

    if (inputAdultos) inputAdultos.max = capacidad || 8;
    if (inputNinos) inputNinos.max = capacidad || 8;

    if (!capacidad) {
      if (capacidadMensaje) capacidadMensaje.textContent = 'Selecciona un tipo de habitación para ver la capacidad.';
      capacidadMensaje?.classList.remove('text-danger');
      inputAdultos?.setCustomValidity('');
      inputNinos?.setCustomValidity('');
      return;
    }

    if (capacidadMensaje) {
      capacidadMensaje.textContent = `Capacidad máxima: ${capacidad} huésped(es).`;
    }

    if (total > capacidad) {
      const advertencia = `La capacidad máxima de esta habitación es ${capacidad} persona(s).`;
      inputAdultos?.setCustomValidity(advertencia);
      inputNinos?.setCustomValidity(advertencia);
      capacidadMensaje?.classList.add('text-danger');
    } else {
      inputAdultos?.setCustomValidity('');
      inputNinos?.setCustomValidity('');
      capacidadMensaje?.classList.remove('text-danger');
    }
  }

  selectTipoHabitacion?.addEventListener('change', validarCapacidad);
  inputAdultos?.addEventListener('input', validarCapacidad);
  inputNinos?.addEventListener('input', validarCapacidad);

  // ---------------- TOAST BOOTSTRAP ----------------
  function mostrarToast(mensaje, tipo = 'info') {
    const toastEl = document.getElementById('liveToast');
    const toastMessage = document.getElementById('toastMessage');
    const toastHeader = toastEl.querySelector('.toast-header');

    let iconClass = 'fas fa-info-circle me-2 text-primary';
    let headerClass = 'bg-primary text-white';

    switch (tipo) {
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

    toastHeader.className = `toast-header ${headerClass}`;
    toastHeader.querySelector('i').className = iconClass;
    toastMessage.textContent = mensaje;

    const toast = new bootstrap.Toast(toastEl);
    toast.show();
  }

  // ---------------- RESERVAS DEL DÍA ----------------
  function cargarReservasDelDia() {
    const tbody = document.getElementById('cuerpoTablaReservas');

    tbody.innerHTML = `
      <tr>
        <td colspan="7" class="text-center text-muted py-4">
          <i class="fas fa-spinner fa-spin me-2"></i>Cargando reservas del día...
        </td>
      </tr>
    `;

    fetch("{{ route('recepcion.reservas-dia') }}", {
      headers: {
        'Accept': 'application/json'
      }
    })
      .then(res => res.json())
      .then(data => mostrarReservasEnTabla(data))
      .catch(() => {
        tbody.innerHTML = `
          <tr>
            <td colspan="7" class="text-center text-danger py-4">
              <i class="fas fa-times-circle me-2"></i>Error al cargar reservas
            </td>
          </tr>`;
      });
  }

  function mostrarReservasEnTabla(reservas) {
    const tbody = document.getElementById('cuerpoTablaReservas');
    tbody.innerHTML = '';

    if (!Array.isArray(reservas) || reservas.length === 0) {
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
      const codigo = reserva.codigo ?? reserva.id ?? '';
      const badgeClass = ['Confirmada', 'Activa'].includes(reserva.estado)
        ? 'bg-success'
        : 'bg-warning';

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><strong>${codigo}</strong></td>
        <td>${reserva.huesped}</td>
        <td><span class="badge bg-primary">${reserva.habitacion}</span></td>
        <td>${reserva.checkin}</td>
        <td>${reserva.checkout}</td>
        <td><span class="badge ${badgeClass}">${reserva.estado}</span></td>
        <td>
          <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-outline-primary" title="Ver detalles">
              <i class="fas fa-eye"></i>
            </button>
            <button type="button" class="btn btn-outline-success"
                    title="Check-In"
                    data-action="checkin"
                    data-codigo="${codigo}">
              <i class="fas fa-sign-in-alt"></i>
            </button>
            <button type="button" class="btn btn-outline-danger"
                    title="Check-Out"
                    data-action="checkout"
                    data-codigo="${codigo}">
              <i class="fas fa-sign-out-alt"></i>
            </button>
          </div>
        </td>
      `;
      tbody.appendChild(tr);
    });
  }

  // ---------------- FILTRO DE OCUPACIÓN ----------------
  document.getElementById('btnFiltrar').addEventListener('click', () => {
    const inicio = document.getElementById('fechaInicio').value;
    const fin = document.getElementById('fechaFin').value;

    if (!inicio || !fin) {
      mostrarToast('Por favor selecciona ambas fechas.', 'warning');
      return;
    }

    const tbody = document.querySelector('#tablaOcupacion tbody');
    const resultadosDiv = document.getElementById('resultadosFiltro');

    tbody.innerHTML = `
      <tr>
        <td colspan="5" class="text-center py-3 text-muted">
          <i class="fas fa-spinner fa-spin me-2"></i>Buscando ocupación...
        </td>
      </tr>
    `;

    resultadosDiv.style.display = 'block';

    fetch(`{{ route('recepcion.ocupacion') }}?inicio=${inicio}&fin=${fin}`)
      .then(res => res.json())
      .then(data => mostrarResultadosOcupacion(data))
      .catch(() => mostrarToast('Error al obtener la ocupación.', 'error'));
  });

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

  // ---------------- OTROS (opcional liberar habitación) ----------------
  document.querySelector('.btn-liberar')?.addEventListener('click', function () {
    const roomNumber = document.getElementById('roomNumber').value;
    if (!roomNumber) {
      mostrarToast('Por favor ingresa un número de habitación', 'warning');
      return;
    }
    mostrarToast(`Habitación ${roomNumber} liberada exitosamente`, 'success');
  });

  document.addEventListener('DOMContentLoaded', function () {
    actualizarModoHuesped();
    validarCapacidad();

    if (document.getElementById('reservas').classList.contains('visible')) {
      cargarReservasDelDia();
    } else if (document.getElementById('inicio').classList.contains('visible')) {
      cargarEstadisticasInicio();
    }
  });

  function cargarEstadisticasInicio() {
    return true;
  }

  // ---------------- ACCIONES CHECKIN / CHECKOUT (GLOBAL) ----------------
  const actionEndpoints = {
    checkin: "{{ route('recepcion.checkin') }}",
    checkout: "{{ route('recepcion.checkout') }}"
  };

  async function ejecutarAccionReserva(accion, codigo, boton) {
    if (!actionEndpoints[accion]) return;

    boton.disabled = true;
    boton.classList.add('disabled');

    try {
      const res = await fetch(actionEndpoints[accion], {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          codigo_reserva: codigo   // 👈 debe coincidir con lo que esperas en el controlador
        })
      });

      const data = await res.json();

      if (!res.ok || !data.success) {
        throw new Error(data.message || 'No se pudo completar la acción');
      }

      mostrarToast(data.message, 'success');
      cargarReservasDelDia();
    } catch (err) {
      mostrarToast(err.message, 'error');
    } finally {
      boton.disabled = false;
      boton.classList.remove('disabled');
    }
  }

  // Delegación de eventos para los botones de la tabla
  document.getElementById('tablaReservasDia')?.addEventListener('click', function (event) {
    const boton = event.target.closest('button[data-action]');
    if (!boton) return;

    const accion = boton.dataset.action;   // "checkin" o "checkout"
    const codigo = boton.dataset.codigo;   // viene del data-codigo

    if (!codigo) {
      mostrarToast('La reserva no tiene un código asignado.', 'warning');
      return;
    }

    ejecutarAccionReserva(accion, codigo, boton);
  });

  // (Opcional) Si tienes un formulario manual de Check-In por código (formCheckIn)
  document.getElementById('formCheckIn')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const codigo = this.querySelector('input[name="codigo_reserva"]').value;

    if (!codigo) {
      mostrarToast('Ingresa el código de reserva', 'warning');
      return;
    }

    const submitBtn = this.querySelector('button[type="submit"]');
    ejecutarAccionReserva('checkin', codigo, submitBtn);
  });
</script>

</body>
</html>