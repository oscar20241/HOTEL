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
   <input type="date" name="fecha_entrada" id="fechaEntrada"
       class="form-control"
       value="{{ old('fecha_entrada') }}"
       required>
    </div>

    {{-- Fecha de salida --}}
    <div class="col-md-3">
      <label class="form-label">Fecha de Salida:</label>
    <input type="date" name="fecha_salida" id="fechaSalida"
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

    {{-- Personas --}}
    <div class="col-md-3">
      <label class="form-label">Personas:</label>
      <input type="number" name="personas" id="inputPersonas" class="form-control"
             min="1" max="8"
             value="{{ old('personas', 1) }}" required>
    </div>


    <div class="col-12">
      <small class="text-muted" id="capacidadMensaje">Selecciona un tipo de habitación para ver la capacidad.</small>
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
                    <th><i class="fas fa-wallet"></i> Saldo</th>
                    <th><i class="fas fa-tag"></i> Estado</th>
                    <th><i class="fas fa-cog"></i> Acciones</th>
                  </tr>
                </thead>
                <tbody id="cuerpoTablaReservas">
                  <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                      <i class="fas fa-spinner fa-spin me-2"></i>Cargando reservas del día...
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="card shadow-sm mt-4">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
              <h5 class="mb-0"><i class="fas fa-history text-primary"></i> Historial de reservaciones</h5>
              <small class="text-muted">Busca por nombre o correo del huésped</small>
            </div>

            <form id="formHistorial" class="row g-2 align-items-end">
              <div class="col-md-9">
                <label for="inputHistorialCliente" class="form-label">Huésped</label>
                <input type="text" id="inputHistorialCliente" class="form-control" placeholder="Ej. cliente@correo.com o nombre completo" />
              </div>
              <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                  <i class="fas fa-search"></i> Buscar historial
                </button>
              </div>
            </form>

            <div class="table-responsive mt-3">
              <table class="table table-bordered align-middle" id="tablaHistorial">
                <thead class="table-light">
                  <tr>
                    <th>Reserva</th>
                    <th>Huésped</th>
                    <th>Habitación</th>
                    <th>Estancia</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Pendiente</th>
                  </tr>
                </thead>
                <tbody id="cuerpoHistorial">
                  <tr>
                    <td colspan="7" class="text-center text-muted py-3">
                      Ingresa el nombre o correo del huésped para consultar su historial.
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

  <!-- Modal: Registrar pago en efectivo -->
  <div class="modal fade" id="modalPagoEfectivo" tabindex="-1" aria-labelledby="modalPagoEfectivoLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalPagoEfectivoLabel"><i class="fas fa-cash-register me-2 text-success"></i>Registrar pago en efectivo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="mb-1"><strong>Reserva:</strong> <span id="pagoCodigoReserva">--</span></p>
          <p class="mb-1"><strong>Huésped:</strong> <span id="pagoHuesped">--</span></p>
          <p class="mb-3"><strong>Total:</strong> <span id="pagoTotal">$0.00</span> · <strong>Saldo pendiente:</strong> <span id="pagoSaldo">$0.00</span></p>
          <div class="mb-3">
            <label for="pagoMonto" class="form-label">Monto a cobrar en efectivo</label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input type="number" step="0.01" min="0.01" class="form-control" id="pagoMonto" value="0.00">
            </div>
            <div class="form-text">Si ingresas un monto mayor al saldo, solo se cobrará el pendiente.</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" id="btnConfirmarPagoEfectivo">
            <i class="fas fa-check"></i> Confirmar cobro
          </button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  const csrfToken = '{{ csrf_token() }}';
  const historialEndpoint = "{{ route('recepcion.reservas.historial') }}";

  // ---------------- NAVEGACIÓN ENTRE SECCIONES ----------------
  const links = document.querySelectorAll('.nav-link');
  const secciones = document.querySelectorAll('.seccion');

  const modalPagoEfectivoEl = document.getElementById('modalPagoEfectivo');
  const modalPagoEfectivo = modalPagoEfectivoEl ? new bootstrap.Modal(modalPagoEfectivoEl) : null;
  const pagoCodigoReserva = document.getElementById('pagoCodigoReserva');
  const pagoHuesped = document.getElementById('pagoHuesped');
  const pagoTotal = document.getElementById('pagoTotal');
  const pagoSaldo = document.getElementById('pagoSaldo');
  const pagoMonto = document.getElementById('pagoMonto');
  let reservaPagoSeleccionada = null;

  function formatearMoneda(valor) {
    const numero = Number(valor ?? 0);
    return `$${numero.toFixed(2)}`;
  }

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
  const inputPersonas = document.getElementById('inputPersonas');
  const capacidadMensaje = document.getElementById('capacidadMensaje');

  function obtenerCapacidadSeleccionada() {
    const opcion = selectTipoHabitacion?.selectedOptions[0];
    return opcion ? parseInt(opcion.dataset.capacidad || '0', 10) || 0 : 0;
  }

  function validarCapacidad() {
    const capacidad = obtenerCapacidadSeleccionada();
    const personas = parseInt(inputPersonas?.value || '0', 10);

    if (inputPersonas) inputPersonas.max = capacidad || 8;

    if (!capacidad) {
      if (capacidadMensaje) capacidadMensaje.textContent = 'Selecciona un tipo de habitación para ver la capacidad.';
      capacidadMensaje?.classList.remove('text-danger');
      inputPersonas?.setCustomValidity('');
      return;
    }

    if (capacidadMensaje) {
      capacidadMensaje.textContent = `Capacidad máxima: ${capacidad} huésped(es).`;
    }

    if (personas > capacidad) {
      const advertencia = `La capacidad máxima de esta habitación es ${capacidad} persona(s).`;
      inputPersonas?.setCustomValidity(advertencia);
      capacidadMensaje?.classList.add('text-danger');
    } else {
      inputPersonas?.setCustomValidity('');
      capacidadMensaje?.classList.remove('text-danger');
    }
  }

  selectTipoHabitacion?.addEventListener('change', validarCapacidad);
  inputPersonas?.addEventListener('input', validarCapacidad);




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
        <td colspan="8" class="text-center text-muted py-4">
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
            <td colspan="8" class="text-center text-danger py-4">
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
          <td colspan="8" class="text-center text-muted py-4">
            <i class="fas fa-calendar-times me-2"></i>No hay reservas para hoy
          </td>
        </tr>
      `;
      return;
    }

    reservas.forEach(reserva => {
      const codigo = reserva.codigo ?? reserva.id ?? '';
      const estado = (reserva.estado || 'pendiente').toLowerCase();
      const saldoPendiente = Number(reserva.saldo_pendiente ?? 0);
      const precioTotal = Number(reserva.precio_total ?? 0);

      const badgeClass = {
        pendiente: 'bg-warning text-dark',
        confirmada: 'bg-primary',
        activa: 'bg-success',
        completada: 'bg-success',
        cancelada: 'bg-secondary'
      }[estado] || 'bg-secondary';

      const saldoBadge = saldoPendiente > 0
        ? `<span class="badge bg-warning text-dark">Pendiente ${formatearMoneda(saldoPendiente)}</span>`
        : '<span class="badge bg-success">Pagado</span>';

      const acciones = [];

    if (['pendiente', 'confirmada'].includes(estado)) {

  // ⛔ Si aún tiene saldo, NO permitir check-in
  if (saldoPendiente <= 0) {
    acciones.push(`
      <button type="button" class="btn btn-outline-success"
              title="Check-In"
              data-action="checkin"
              data-codigo="${codigo}">
        <i class="fas fa-sign-in-alt"></i>
      </button>
    `);
  } else {
    // Mostrar botón deshabilitado
    acciones.push(`
      <button type="button" class="btn btn-outline-secondary" disabled
              title="Debe pagar antes de hacer Check-In">
        <i class="fas fa-lock"></i>
      </button>
    `);
  }


        acciones.push(`
          <button type="button" class="btn btn-outline-danger"
                  title="Cancelar reservación"
                  data-action="cancelar"
                  data-codigo="${codigo}">
            <i class="fas fa-ban"></i>
          </button>
        `);
      }

      if (estado === 'activa') {
        acciones.push(`
          <button type="button" class="btn btn-outline-danger"
                  title="Check-Out"
                  data-action="checkout"
                  data-codigo="${codigo}">
            <i class="fas fa-sign-out-alt"></i>
          </button>
        `);
      }

      if (saldoPendiente > 0 && estado !== 'cancelada') {
        acciones.push(`
          <button type="button" class="btn btn-outline-warning"
                  title="Registrar pago en efectivo"
                  data-action="pago-efectivo"
                  data-codigo="${codigo}"
                  data-huesped="${reserva.huesped ?? ''}"
                  data-saldo="${saldoPendiente}"
                  data-total="${precioTotal}">
            <i class="fas fa-money-bill-wave"></i>
          </button>
        `);
      }

      const accionesHtml = acciones.length
        ? `<div class="btn-group btn-group-sm" role="group">${acciones.join('')}</div>`
        : '<span class="text-muted">Sin acciones</span>';

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><strong>${codigo}</strong></td>
        <td>${reserva.huesped}</td>
        <td><span class="badge bg-primary">${reserva.habitacion}</span></td>
        <td>${reserva.checkin}</td>
        <td>${reserva.checkout}</td>
        <td>${saldoBadge}<div class="text-muted small">Total ${formatearMoneda(precioTotal)}</div></td>
        <td><span class="badge ${badgeClass}">${reserva.estado}</span></td>
        <td>${accionesHtml}</td>
      `;
      tbody.appendChild(tr);
    });
  }

  function abrirModalPagoEfectivo({ codigo, huesped, saldo, total }) {
    if (!modalPagoEfectivo) return;

    reservaPagoSeleccionada = {
      codigo,
      saldo: Number(saldo ?? 0),
      total: Number(total ?? 0),
      huesped: huesped || 'Huésped'
    };

    pagoCodigoReserva.textContent = reservaPagoSeleccionada.codigo || '--';
    pagoHuesped.textContent = reservaPagoSeleccionada.huesped;
    pagoTotal.textContent = formatearMoneda(reservaPagoSeleccionada.total);
    pagoSaldo.textContent = formatearMoneda(reservaPagoSeleccionada.saldo);
    pagoMonto.value = reservaPagoSeleccionada.saldo.toFixed(2);

    modalPagoEfectivo.show();
  }

  async function registrarPagoEfectivo() {
    if (!reservaPagoSeleccionada) {
      mostrarToast('No se encontró la reservación seleccionada.', 'warning');
      return;
    }

    const monto = parseFloat(pagoMonto?.value || '0');

    if (!monto || monto <= 0) {
      mostrarToast('Ingresa un monto válido para cobrar.', 'warning');
      return;
    }

    try {
      const res = await fetch("{{ route('recepcion.pago-efectivo') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          codigo_reserva: reservaPagoSeleccionada.codigo,
          monto
        })
      });

      const data = await res.json();

      if (!res.ok || !data.success) {
        throw new Error(data.message || 'No se pudo registrar el pago.');
      }

      mostrarToast(data.message, 'success');
      modalPagoEfectivo?.hide();
      cargarReservasDelDia();
    } catch (error) {
      mostrarToast(error.message || 'Error al registrar el pago.', 'error');
    }
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

  // ---------------- HISTORIAL DE RESERVAS ----------------
  const formHistorial = document.getElementById('formHistorial');
  const cuerpoHistorial = document.getElementById('cuerpoHistorial');

  formHistorial?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const criterio = document.getElementById('inputHistorialCliente')?.value.trim();

    if (!criterio) {
      mostrarToast('Ingresa el nombre o correo del huésped para buscar.', 'warning');
      return;
    }

    cuerpoHistorial.innerHTML = `
      <tr>
        <td colspan="7" class="text-center text-muted py-3">
          <i class="fas fa-spinner fa-spin me-2"></i>Buscando historial...
        </td>
      </tr>
    `;

    try {
      const res = await fetch(`${historialEndpoint}?cliente=${encodeURIComponent(criterio)}`, {
        headers: { 'Accept': 'application/json' }
      });

      const data = await res.json();

      if (!res.ok || !data.success) {
        throw new Error(data.message || 'No se pudo obtener el historial.');
      }

      mostrarHistorialReservas(data.reservas || [], criterio);
    } catch (error) {
      cuerpoHistorial.innerHTML = `
        <tr>
          <td colspan="7" class="text-center text-danger py-3">
            ${error.message || 'Error al consultar el historial.'}
          </td>
        </tr>
      `;
    }
  });

  function mostrarHistorialReservas(reservas, criterio) {
    cuerpoHistorial.innerHTML = '';

    if (!reservas.length) {
      cuerpoHistorial.innerHTML = `
        <tr>
          <td colspan="7" class="text-center text-muted py-3">
            No se encontraron reservaciones para "${criterio}".
          </td>
        </tr>
      `;
      return;
    }

    reservas.forEach(reserva => {
      const estado = (reserva.estado || 'Pendiente').toLowerCase();
      const badgeClass = {
        pendiente: 'bg-warning text-dark',
        confirmada: 'bg-primary',
        activa: 'bg-success',
        completada: 'bg-success',
        cancelada: 'bg-secondary'
      }[estado] || 'bg-secondary';

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><strong>${reserva.codigo}</strong></td>
        <td>${reserva.huesped}<div class="small text-muted">${reserva.email ?? ''}</div></td>
        <td>${reserva.habitacion ?? 'N/A'}<div class="small text-muted">${reserva.tipo ?? ''}</div></td>
        <td><div>${reserva.entrada}</div><div class="text-muted small">al ${reserva.salida}</div></td>
        <td><span class="badge ${badgeClass}">${reserva.estado}</span></td>
        <td>${formatearMoneda(reserva.total ?? 0)}</td>
        <td>${formatearMoneda(reserva.pendiente ?? 0)}</td>
      `;
      cuerpoHistorial.appendChild(tr);
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


      const inputEntrada = document.querySelector('input[name="fecha_entrada"]');
  const inputSalida  = document.querySelector('input[name="fecha_salida"]');

  if (inputEntrada && inputSalida) {
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0); // limpiar horas

    const mañana = new Date(hoy);
    mañana.setDate(hoy.getDate() + 1);

    const toInputDate = (d) => d.toISOString().split('T')[0];

    // 🔒 Fecha mínima de ENTRADA = hoy
    const hoyStr = toInputDate(hoy);
    inputEntrada.min = hoyStr;
    if (!inputEntrada.value || inputEntrada.value < hoyStr) {
      inputEntrada.value = hoyStr;
    }

    // 🔒 Fecha mínima de SALIDA = mañana
    const mañanaStr = toInputDate(mañana);
    inputSalida.min = mañanaStr;
    if (!inputSalida.value || inputSalida.value < mañanaStr) {
      inputSalida.value = mañanaStr;
    }

    // 🧠 Si cambia la fecha de entrada, ajustamos la salida
    inputEntrada.addEventListener('change', () => {
      if (!inputEntrada.value) return;

      const entrada = new Date(inputEntrada.value);
      if (isNaN(entrada)) return;

      entrada.setHours(0, 0, 0, 0);
      const minSalida = new Date(entrada);
      minSalida.setDate(entrada.getDate() + 1);

      const minSalidaStr = toInputDate(minSalida);
      inputSalida.min = minSalidaStr;

      // Si la salida actual es menor que el mínimo, la movemos
      if (!inputSalida.value || inputSalida.value < minSalidaStr) {
        inputSalida.value = minSalidaStr;
      }
    });
  }




  });

  function cargarEstadisticasInicio() {
    return true;
  }

  // ---------------- ACCIONES CHECKIN / CHECKOUT (GLOBAL) ----------------
  const actionEndpoints = {
    checkin: "{{ route('recepcion.checkin') }}",
    checkout: "{{ route('recepcion.checkout') }}",
    cancelar: "{{ route('recepcion.reservas.cancelar') }}"
  };

  document.getElementById('btnConfirmarPagoEfectivo')?.addEventListener('click', registrarPagoEfectivo);

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

    const accion = boton.dataset.action;   // "checkin", "checkout" o "pago-efectivo"
    const codigo = boton.dataset.codigo;   // viene del data-codigo

    if (!codigo) {
      mostrarToast('La reserva no tiene un código asignado.', 'warning');
      return;
    }

    if (accion === 'pago-efectivo') {
      abrirModalPagoEfectivo({
        codigo,
        huesped: boton.dataset.huesped,
        saldo: boton.dataset.saldo,
        total: boton.dataset.total
      });
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