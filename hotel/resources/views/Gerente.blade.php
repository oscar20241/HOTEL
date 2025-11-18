<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gerente | Pasa el Extra Inn</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  @vite(['resources/css/estilo.css'])
  @vite(['resources/css/gerente.css'])
  
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <!-- Chart.js para gráficas -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
        <a href="#" class="nav-link" data-target="tarifas">
          <i class="fas fa-dollar-sign"></i> Tarifas
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

        @php
            $totalHabitaciones = $habitaciones->count();
            $ocupadas = $habitaciones->where('estado', 'ocupada')->count();
            $ocupacionPorcentaje = $totalHabitaciones > 0
                ? round(($ocupadas / $totalHabitaciones) * 100)
                : 0;

            $totalHuespedes = $huespedes->count();
        @endphp

        <div class="row g-4 mt-3">

          <!-- Habitaciones Ocupadas → sección Habitaciones -->
          <div class="col-md-4">
            <div class="card info-card card-inicio" data-target="habitaciones">
              <div class="card-body">
                <h5 class="card-title">
                  <i class="fas fa-bed text-warning"></i> Habitaciones Ocupadas
                </h5>
                <p class="card-text fs-4">
                  {{ $ocupadas }} / {{ $totalHabitaciones }}
                </p>
                <small class="text-muted">
                  Ocupación: {{ $ocupacionPorcentaje }}%
                </small>
              </div>
            </div>
          </div>

          <!-- Reservas del Día → sección Reservas -->
          <div class="col-md-4">
            <div class="card info-card card-inicio" data-target="reservas">
              <div class="card-body">
                <h5 class="card-title">
                  <i class="fas fa-calendar-check text-warning"></i> Reservas del Día
                </h5>
                <p class="card-text fs-4">
                  {{ $reservasHoy ?? 0 }}
                </p>
                <small class="text-muted">
                  Check-in / check-out con fecha de hoy
                </small>
              </div>
            </div>
          </div>

          <!-- Clientes Activos → sección Usuarios -->
          <div class="col-md-4">
            <div class="card info-card card-inicio" data-target="usuarios">
              <div class="card-body">
                <h5 class="card-title">
                  <i class="fas fa-users text-warning"></i> Clientes Activos
                </h5>
                <p class="card-text fs-4">
                  {{ $totalHuespedes }}
                </p>
                <small class="text-muted">
                  Huéspedes registrados en el sistema
                </small>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- 🔽 A partir de aquí deja tal cual tus otras secciones:
           reservas, habitaciones, tarifas, usuarios, reportes, cerrar sesión -->




     <!-- Sección: Reservas (ADMIN mejorada) -->
   <div id="reservas" class="seccion">
  <h2>Reservaciones</h2>
  <p>Gestión completa de todas las reservas del hotel.</p>

  <!-- Filtros -->
  <div class="reservas-container">
    <div class="row g-3 mb-3">
      <div class="col-md-3">
        <input type="text" id="buscarReserva" class="reservas-input" placeholder="Buscar huésped / habitación...">
      </div>
      <div class="col-md-3">
        <select id="filtroHabitacion" class="form-select" style="background:#1a1c22;color:#fff;border:1px solid #444;">
          <option value="">Todas las habitaciones</option>
          @foreach($habitaciones as $h)
            <option value="{{ $h->id }}">Hab {{ $h->numero }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <select id="filtroEstado" class="form-select" style="background:#1a1c22;color:#fff;border:1px solid #444;">
          <option value="">Todos los estados</option>
          <option value="pendiente">Pendiente</option>
          <option value="confirmada">Confirmada</option>
          <option value="activa">Activa</option>
          <option value="completada">Completada</option>
          <option value="cancelada">Cancelada</option>
        </select>
      </div>
      <div class="col-md-3">
        <!-- Rango de fechas con 2 inputs (De / A) -->
        <div class="d-flex gap-2">
          <input type="date" id="filtroDesde" class="form-control" style="background:#1a1c22;color:#fff;border:1px solid #444;">
          <input type="date" id="filtroHasta" class="form-control" style="background:#1a1c22;color:#fff;border:1px solid #444;">
        </div>
      </div>
    </div>

    <!-- Pestañas Lista / Calendario -->
    <div class="usuarios-tabs mb-3">
      <button class="tab-button active" data-res-tab="lista">Listado</button>
      <button class="tab-button" data-res-tab="calendario">Calendario</button>
    </div>

    <!-- LISTA -->
    <div id="tab-res-lista" class="tab-content active">
      <table class="tabla-reservas">
        <thead>
          <tr>
            <th># Habitación</th>
            <th>Huésped</th>
            <th>Check-In</th>
            <th>Check-Out</th>
            <th>Estado</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody id="listaReservas"></tbody>
      </table>
    </div>

    <!-- CALENDARIO -->
    <div id="tab-res-calendario" class="tab-content">
      <div id="calendarioReservas" style="background:#1a1c22;border:1px solid #333;border-radius:10px;padding:10px;"></div>
      <small class="text-muted d-block mt-2">*Arrastra/zoom con el calendario (mes/semana/día) para explorar.</small>
    </div>
  </div>
</div>

            <!-- Sección: Habitaciones MEJORADA -->
      <div id="habitaciones" class="seccion">
        <h2>Gestión de Habitaciones</h2>
        
        <!-- Mensajes dinámicos -->
        <div id="habitacion-messages"></div>

        <div class="d-flex justify-content-between align-items-center mb-4">
          <p>Administración completa del inventario de habitaciones del hotel.</p>
          <button class="btn btn-primary" onclick="mostrarModalHabitacion()">
            <i class="fas fa-plus-circle"></i> Nueva Habitación
          </button>
        </div>

        <!-- Estadísticas en tiempo real -->
        <div class="row g-4 mb-4">
          <div class="col-md-3">
            <div class="card info-card">
              <div class="card-body text-center">
                <h5 class="card-title"><i class="fas fa-bed text-warning"></i> Total</h5>
                <p class="card-text fs-4" id="total-habitaciones">{{ $habitaciones->count() }}</p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card info-card">
              <div class="card-body text-center">
                <h5 class="card-title"><i class="fas fa-check-circle text-success"></i> Disponibles</h5>
                <p class="card-text fs-4" id="habitaciones-disponibles">
                  {{ $habitaciones->where('estado', 'disponible')->count() }}
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card info-card">
              <div class="card-body text-center">
                <h5 class="card-title"><i class="fas fa-times-circle text-danger"></i> Ocupadas</h5>
                <p class="card-text fs-4" id="habitaciones-ocupadas">
                  {{ $habitaciones->where('estado', 'ocupada')->count() }}
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card info-card">
              <div class="card-body text-center">
                <h5 class="card-title"><i class="fas fa-tools text-warning"></i> Mantenimiento</h5>
                <p class="card-text fs-4" id="habitaciones-mantenimiento">
                  {{ $habitaciones->where('estado', 'mantenimiento')->count() }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Listado de habitaciones desde BD -->
        <div class="habitaciones-container">
          <input 
            type="text" 
            id="buscarHabitacion" 
            class="habitaciones-input" 
            placeholder="Buscar por número, tipo o estado..."
          />

          <table class="tabla-habitaciones">
            <thead>
              <tr>
                <th># Habitación</th>
                <th>Imagen</th>
                <th>Tipo</th>
                <th>Capacidad</th>
                <th>Precio/Noche</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="listaHabitaciones">
              @foreach($habitaciones as $habitacion)
              <tr data-habitacion-id="{{ $habitacion->id }}">
                <td><strong>{{ $habitacion->numero }}</strong></td>
                <td>
                  @php
                    $imagenPrincipal = $habitacion->imagenes->firstWhere('es_principal', true) ?? $habitacion->imagenes->first();
                  @endphp
                  @if($imagenPrincipal)
                    <img src="{{ asset('storage/' . $imagenPrincipal->ruta_imagen) }}" alt="Imagen de la habitación {{ $habitacion->numero }}" class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">
                  @else
                    <span class="text-muted small">Sin imagen</span>
                  @endif
                </td>
                <td>{{ $habitacion->tipoHabitacion->nombre }}</td>
                <td>{{ $habitacion->capacidad }} personas</td>
                <td>
                  @php
                    $hoy = \Carbon\Carbon::today();
                    $tarifaActual = $habitacion->tipoHabitacion->tarifasDinamicas
                      ->filter(fn($tarifa) => $tarifa->fecha_inicio->lte($hoy) && $tarifa->fecha_fin->gte($hoy))
                      ->sortBy(function ($tarifa) {
                        $prioridad = ['especial' => 0, 'alta' => 1, 'baja' => 2];
                        return $prioridad[$tarifa->tipo_temporada] ?? 3;
                      })
                      ->first();
                  @endphp
                  <strong>${{ number_format($habitacion->precio_actual, 2) }}</strong>
                  <div class="small text-muted">
                    @if($tarifaActual)
                      {{ ucfirst($tarifaActual->tipo_temporada) }} · {{ $tarifaActual->fecha_inicio->format('d/m') }} - {{ $tarifaActual->fecha_fin->format('d/m') }}
                    @else
                      Tarifa base
                    @endif
                  </div>
                </td>
                <td>
                  @php
                    $estadoColors = [
                      'disponible' => 'success',
                      'ocupada' => 'danger', 
                      'mantenimiento' => 'warning',
                      'limpieza' => 'info'
                    ];
                    $estadoTextos = [
                      'disponible' => 'Disponible',
                      'ocupada' => 'Ocupada',
                      'mantenimiento' => 'Mantenimiento',
                      'limpieza' => 'Limpieza'
                    ];
                  @endphp
                  <span class="badge bg-{{ $estadoColors[$habitacion->estado] ?? 'secondary' }}">
                    {{ $estadoTextos[$habitacion->estado] ?? ucfirst($habitacion->estado) }}
                  </span>
                </td>
                <td>
                  <div class="btn-group btn-group-sm">
                    <button class="btn btn-warning" onclick="editarHabitacion({{ $habitacion->id }})" title="Editar">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger" onclick="eliminarHabitacion({{ $habitacion->id }})" title="Eliminar">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        @if($habitaciones->isEmpty())
        <div class="text-center py-5">
          <i class="fas fa-door-open fa-3x text-muted mb-3"></i>
          <h4 class="text-muted">No hay habitaciones registradas</h4>
          <p class="text-muted">Comienza agregando la primera habitación del hotel.</p>
          <button class="btn btn-primary" onclick="mostrarModalHabitacion()">
            <i class="fas fa-plus-circle"></i> Agregar Primera Habitación
          </button>
        </div>
      @endif
      </div>

      <!-- Sección: Tarifas dinámicas -->
      <div id="tarifas" class="seccion">
        <h2>Tarifas dinámicas</h2>

        <div id="tarifa-messages"></div>

        <div class="d-flex justify-content-between align-items-center mb-4">
          <p class="mb-0">Configura temporadas altas, bajas o especiales para ajustar los precios automáticamente.</p>
          <button class="btn btn-primary" onclick="mostrarModalTarifa()">
            <i class="fas fa-plus-circle"></i> Nueva tarifa
          </button>
        </div>

        <div class="table-responsive">
          <table class="table table-dark table-hover align-middle">
            <thead>
              <tr>
                <th>Tipo de habitación</th>
                <th>Temporada</th>
                <th>Vigencia</th>
                <th>Precio</th>
                <th>Descripción</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody id="listaTarifas">
              @forelse($tarifasDinamicas as $tarifa)
              <tr data-tarifa-id="{{ $tarifa->id }}">
                <td>
                  <strong>{{ $tarifa->tipoHabitacion->nombre }}</strong>
                  <div class="small text-muted">Base: ${{ number_format($tarifa->tipoHabitacion->precio_base, 2) }}</div>
                </td>
                <td>
                  @php
                    $temporadasLabels = [
                      'alta' => 'Alta',
                      'baja' => 'Baja',
                      'especial' => 'Especial'
                    ];
                    $temporadasColors = [
                      'alta' => 'danger',
                      'baja' => 'info',
                      'especial' => 'warning'
                    ];
                  @endphp
                  <span class="badge bg-{{ $temporadasColors[$tarifa->tipo_temporada] ?? 'secondary' }}">
                    {{ $temporadasLabels[$tarifa->tipo_temporada] ?? ucfirst($tarifa->tipo_temporada) }}
                  </span>
                </td>
                <td>{{ $tarifa->fecha_inicio->format('d/m/Y') }} - {{ $tarifa->fecha_fin->format('d/m/Y') }}</td>
                <td>${{ number_format($tarifa->precio_modificado, 2) }}</td>
                <td>{{ $tarifa->descripcion ?? '—' }}</td>
                <td class="text-center">
                  <div class="btn-group btn-group-sm">
                    <button class="btn btn-warning" onclick="editarTarifa({{ $tarifa->id }})" title="Editar">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger" onclick="eliminarTarifa({{ $tarifa->id }})" title="Eliminar">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">
                  <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                  <p class="mb-0">Aún no hay temporadas configuradas.</p>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal para Habitaciones CORREGIDO -->
<div id="modalHabitacion" class="modal">
  <div class="modal-contenido">
    <span class="cerrar" onclick="cerrarModalHabitacion()">&times;</span>
    <h2 id="modalHabitacionTitulo">Nueva Habitación</h2>
    
    <form id="formHabitacion" enctype="multipart/form-data">
      @csrf
      <div id="method-field"></div>
      
      <div class="mb-3">
        <label for="numero" class="form-label">Número de Habitación *</label>
        <input type="text" class="form-control" id="numero" name="numero" required 
               placeholder="Ej: 101, 202, 305">
      </div>

      <div class="mb-3">
        <label for="tipo_habitacion_id" class="form-label">Tipo de Habitación *</label>
        <select class="form-select" id="tipo_habitacion_id" name="tipo_habitacion_id" required>
          <option value="">Seleccionar tipo</option>
          @foreach($tiposHabitacion as $tipo)
            <option value="{{ $tipo->id }}">
              {{ $tipo->nombre }} - ${{ number_format($tipo->precio_base, 2) }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="capacidad" class="form-label">Capacidad *</label>
            <input type="number" class="form-control" id="capacidad" name="capacidad" 
                   min="1" max="10" value="2" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="estado" class="form-label">Estado *</label>
            <select class="form-select" id="estado" name="estado" required>
              <option value="disponible">Disponible</option>
              <option value="ocupada">Ocupada</option>
              <option value="mantenimiento">Mantenimiento</option>
              <option value="limpieza">Limpieza</option>
            </select>
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label for="caracteristicas" class="form-label">Características</label>
        <textarea class="form-control" id="caracteristicas" name="caracteristicas" rows="3" 
                  placeholder="Descripción de la habitación, comodidades, vista..."></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Amenidades</label>
        <div class="row">
          @php
            $amenidades = ['TV', 'Aire Acondicionado', 'WiFi', 'Minibar', 'Caja Fuerte', 'Jacuzzi', 'Balcón', 'Vista al Mar'];
          @endphp
          @foreach($amenidades as $amenidad)
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="amenidades[]" 
                     value="{{ $amenidad }}" id="amenidad{{ $loop->index }}">
              <label class="form-check-label" for="amenidad{{ $loop->index }}">
                {{ $amenidad }}
              </label>
            </div>
          </div>
          @endforeach
        </div>
      </div>

      <div class="mb-3">
        <label for="imagenes" class="form-label">Imágenes de la habitación</label>
        <input type="file" class="form-control" id="imagenes" name="imagenes[]" accept="image/*" multiple>
        <small class="text-muted d-block mt-1">Puedes seleccionar varias imágenes; la primera será marcada como principal.</small>
      </div>

      <div class="mb-3 d-none" id="nuevasImagenesWrapper">
        <label class="form-label">Vista previa de nuevas imágenes</label>
        <div id="nuevasImagenesPreview" class="row g-2"></div>
      </div>

      <div class="mb-3 d-none" id="imagenesActualesWrapper">
        <label class="form-label">Imágenes registradas</label>
        <div id="imagenesActuales" class="row g-2"></div>
      </div>

      <div class="text-center">
        <button type="submit" class="btn-confirmar">
          <i class="fas fa-save"></i> Guardar Habitación
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal para Tarifa dinámica -->
<div id="modalTarifa" class="modal">
  <div class="modal-contenido modal-md">
    <span class="cerrar" onclick="cerrarModalTarifa()">&times;</span>
    <h2 id="modalTarifaTitulo">Nueva tarifa</h2>

    <form id="formTarifa">
      @csrf
      <div id="tarifa-method-field"></div>

      <div class="mb-3">
        <label for="tarifa_tipo_habitacion_id" class="form-label">Tipo de habitación *</label>
        <select class="form-select" id="tarifa_tipo_habitacion_id" name="tipo_habitacion_id" required>
          <option value="">Seleccionar tipo</option>
          @foreach($tiposHabitacion as $tipo)
            <option value="{{ $tipo->id }}">{{ $tipo->nombre }} - ${{ number_format($tipo->precio_base, 2) }}</option>
          @endforeach
        </select>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="tarifa_fecha_inicio" class="form-label">Fecha de inicio *</label>
            <input type="date" class="form-control" id="tarifa_fecha_inicio" name="fecha_inicio" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="tarifa_fecha_fin" class="form-label">Fecha de fin *</label>
            <input type="date" class="form-control" id="tarifa_fecha_fin" name="fecha_fin" required>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="tarifa_tipo_temporada" class="form-label">Temporada *</label>
            <select class="form-select" id="tarifa_tipo_temporada" name="tipo_temporada" required>
              <option value="alta">Temporada alta</option>
              <option value="baja">Temporada baja</option>
              <option value="especial">Temporada especial</option>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="tarifa_precio_modificado" class="form-label">Precio por noche *</label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input type="number" min="0" step="0.01" class="form-control" id="tarifa_precio_modificado" name="precio_modificado" required>
            </div>
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label for="tarifa_descripcion" class="form-label">Descripción</label>
        <textarea class="form-control" id="tarifa_descripcion" name="descripcion" rows="3" placeholder="Notas internas, por ejemplo: Evento local, vacaciones..." maxlength="500"></textarea>
      </div>

      <div class="text-center">
        <button type="submit" class="btn-confirmar">
          <i class="fas fa-save"></i> Guardar tarifa
        </button>
      </div>
    </form>
  </div>
</div>

      <!-- Sección: Usuarios -->
      <!-- Sección: Usuarios -->
<div id="usuarios" class="seccion">
  <h2>Gestión de Usuarios</h2>
  
  <!-- Mensajes -->
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-4">
    <p class="mb-0">Administración de cuentas de huéspedes y personal.</p>
    <a href="{{ route('admin.empleados.create') }}" class="btn btn-primary">
      <i class="fas fa-user-plus"></i> Nuevo Empleado
    </a>
  </div>

  <!-- Pestañas SIMPLIFICADAS -->
  <div class="usuarios-tabs mb-4">
    <button class="tab-button active" onclick="mostrarTab('huespedes')">
      <i class="fas fa-users"></i> Huéspedes ({{ $huespedes->count() }})
    </button>
    <button class="tab-button" onclick="mostrarTab('empleados')">
      <i class="fas fa-id-badge"></i> Personal Operativo ({{ $empleados->where('empleado.puesto', 'recepcionista')->count() + $empleados->where('empleado.puesto', 'limpieza')->count() }})
    </button>
    <button class="tab-button" onclick="mostrarTab('administradores')">
      <i class="fas fa-user-shield"></i> Administradores ({{ $empleados->where('empleado.puesto', 'administrador')->count() }})
    </button>
    <button class="tab-button" onclick="mostrarTab('gerentes')">
      <i class="fas fa-crown"></i> Gerentes ({{ $empleados->where('empleado.puesto', 'gerente')->count() }})
    </button>
  </div>

  <!-- Tab Huéspedes -->
  <div id="tab-huespedes" class="tab-content active">
    <div class="table-responsive">
      <table class="table table-dark table-hover">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Registro</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($huespedes as $huesped)
          <tr>
            <td>{{ $huesped->name }}</td>
            <td>{{ $huesped->email }}</td>
            <td>{{ $huesped->telefono ?? 'N/A' }}</td>
            <td>{{ $huesped->created_at->format('d/m/Y') }}</td>
            <td>
              <form action="{{ route('admin.usuarios.destroy', $huesped->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este huésped?')">
                  <i class="fas fa-trash"></i> Eliminar
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- Tab Personal Operativo (Recepcionistas y Limpieza) -->
  <div id="tab-empleados" class="tab-content">
    <div class="alert alert-info">
      <i class="fas fa-info-circle"></i> Personal operativo: recepcionistas y equipo de limpieza.
    </div>
    <div class="table-responsive">
      <table class="table table-dark table-hover">
        <thead>
          <tr>
            <th># Empleado</th>
            <th>Nombre</th>
            <th>Puesto</th>
            <th>Turno</th>
            <th>Estado</th>
            <th>Salario</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @php
            $personalOperativo = $empleados->whereIn('empleado.puesto', ['recepcionista', 'limpieza']);
          @endphp
          @foreach($personalOperativo as $empleado)
          <tr>
            <td>{{ $empleado->empleado->numero_empleado }}</td>
            <td>{{ $empleado->name }}</td>
            <td>
              @php
                $puestoColors = [
                  'recepcionista' => 'info',
                  'limpieza' => 'secondary'
                ];
                $puestoNombres = [
                  'recepcionista' => 'Recepcionista',
                  'limpieza' => 'Limpieza'
                ];
              @endphp
              <span class="badge bg-{{ $puestoColors[$empleado->empleado->puesto] ?? 'warning' }}">
                {{ $puestoNombres[$empleado->empleado->puesto] ?? ucfirst($empleado->empleado->puesto) }}
              </span>
            </td>
            <td>{{ ucfirst($empleado->empleado->turno) }}</td>
            <td>
              @php
                $estadoColors = [
                  'activo' => 'success',
                  'inactivo' => 'secondary',
                  'vacaciones' => 'info',
                  'licencia' => 'warning'
                ];
                $estadoNombres = [
                  'activo' => 'Activo',
                  'inactivo' => 'Inactivo', 
                  'vacaciones' => 'Vacaciones',
                  'licencia' => 'Licencia'
                ];
              @endphp
              <span class="badge bg-{{ $estadoColors[$empleado->empleado->estado] ?? 'secondary' }}">
                {{ $estadoNombres[$empleado->empleado->estado] ?? ucfirst($empleado->empleado->estado) }}
              </span>
            </td>
            <td>${{ number_format($empleado->empleado->salario, 2) }}</td>
            <td>
              <a href="{{ route('admin.empleados.edit', $empleado->id) }}" class="btn btn-sm btn-warning" title="Editar">
                <i class="fas fa-edit"></i>
              </a>
              <form action="{{ route('admin.empleados.cambiar-estado', $empleado->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-{{ $empleado->empleado->estado === 'activo' ? 'secondary' : 'success' }}" 
                        title="{{ $empleado->empleado->estado === 'activo' ? 'Desactivar' : 'Activar' }}">
                  <i class="fas fa-{{ $empleado->empleado->estado === 'activo' ? 'pause' : 'play' }}"></i>
                </button>
              </form>
              <form action="{{ route('admin.usuarios.destroy', $empleado->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este empleado?')" title="Eliminar">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- Tab Administradores -->
  <div id="tab-administradores" class="tab-content">
    <div class="alert alert-warning">
      <i class="fas fa-exclamation-triangle"></i> Los administradores tienen acceso completo al sistema.
    </div>
    <div class="table-responsive">
      <table class="table table-dark table-hover">
        <thead>
          <tr>
            <th># Empleado</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Turno</th>
            <th>Estado</th>
            <th>Salario</th>
            <th>Contratación</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @php
            $administradores = $empleados->where('empleado.puesto', 'administrador');
          @endphp
          @foreach($administradores as $admin)
          <tr class="{{ $admin->id === Auth::id() ? 'table-active' : '' }}">
            <td><strong>{{ $admin->empleado->numero_empleado }}</strong></td>
            <td>
              <strong>{{ $admin->name }}</strong>
              @if($admin->id === Auth::id())
                <span class="badge bg-success ms-1">
                  <i class="fas fa-user"></i> Tú
                </span>
              @else
                <span class="badge bg-primary ms-1">
                  <i class="fas fa-user-shield"></i> Administrador
                </span>
              @endif
            </td>
            <td>{{ $admin->email }}</td>
            <td>{{ ucfirst($admin->empleado->turno) }}</td>
            <td>
              <span class="badge bg-success">
                <i class="fas fa-check"></i> {{ ucfirst($admin->empleado->estado) }}
              </span>
            </td>
            <td>${{ number_format($admin->empleado->salario, 2) }}</td>
            <td>{{ $admin->empleado->fecha_contratacion->format('d/m/Y') }}</td>
            <td>
              @if($admin->id !== Auth::id())
                <a href="{{ route('admin.empleados.edit', $admin->id) }}" class="btn btn-sm btn-warning" title="Editar">
                  <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('admin.empleados.cambiar-estado', $admin->id) }}" method="POST" class="d-inline">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-{{ $admin->empleado->estado === 'activo' ? 'secondary' : 'success' }}" 
                          title="{{ $admin->empleado->estado === 'activo' ? 'Desactivar' : 'Activar' }}">
                    <i class="fas fa-{{ $admin->empleado->estado === 'activo' ? 'pause' : 'play' }}"></i>
                  </button>
                </form>
                <form action="{{ route('admin.usuarios.destroy', $admin->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este administrador?')" title="Eliminar">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              @else
                <span class="text-muted">Tu cuenta</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- Tab Gerentes (solo lectura) -->
  <div id="tab-gerentes" class="tab-content">
    <div class="alert alert-info">
      <i class="fas fa-info-circle"></i> Los gerentes son cuentas administrativas protegidas y no pueden ser eliminadas.
    </div>
    <div class="table-responsive">
      <table class="table table-dark table-hover">
        <thead>
          <tr>
            <th># Empleado</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Turno</th>
            <th>Estado</th>
            <th>Salario</th>
            <th>Contratación</th>
          </tr>
        </thead>
        <tbody>
          @php
            $gerentes = $empleados->where('empleado.puesto', 'gerente');
          @endphp
          @foreach($gerentes as $gerente)
          <tr class="{{ $gerente->id === Auth::id() ? 'table-active' : '' }}">
            <td><strong>{{ $gerente->empleado->numero_empleado }}</strong></td>
            <td>
              <strong>{{ $gerente->name }}</strong>
              @if($gerente->id === Auth::id())
                <span class="badge bg-success ms-1">
                  <i class="fas fa-user"></i> Tú
                </span>
              @else
                <span class="badge bg-warning text-dark ms-1">
                  <i class="fas fa-crown"></i> Gerente
                </span>
              @endif
            </td>
            <td>{{ $gerente->email }}</td>
            <td>{{ ucfirst($gerente->empleado->turno) }}</td>
            <td>
              <span class="badge bg-success">
                <i class="fas fa-check"></i> {{ ucfirst($gerente->empleado->estado) }}
              </span>
            </td>
            <td>${{ number_format($gerente->empleado->salario, 2) }}</td>
            <td>{{ $gerente->empleado->fecha_contratacion->format('d/m/Y') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
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
    // Navegación entre secciones principales
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
          
          // Inicializar gráficas si es la sección de reportes
          if (targetId === 'reportes') {
            setTimeout(inicializarGraficas, 100);
          }
        }
      });
    });

    // Función para mostrar sección específica
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

    // Sistema de pestañas para usuarios
    function mostrarTab(tabName) {
      // Ocultar todos los contenidos de pestañas
      document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
      });
      
      // Mostrar la pestaña seleccionada
      document.getElementById('tab-' + tabName).classList.add('active');
      
      // Actualizar botones activos
      document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
      });
      event.target.classList.add('active');
    }

    // Inicializar gráficas de reportes
    function inicializarGraficas() {
      // Actualizar tarjetas de resumen
      document.getElementById('ocupacionPorcentaje').textContent = '75%';
      document.getElementById('ingresosMes').textContent = '$125,430 MXN';
      document.getElementById('reservasActivas').textContent = '18';

      // Gráfica de Ocupación
      const ctxOcupacion = document.getElementById('graficaOcupacion').getContext('2d');
      new Chart(ctxOcupacion, {
        type: 'line',
        data: {
          labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
          datasets: [{
            label: 'Ocupación %',
            data: [65, 70, 75, 80, 78, 85, 90, 88, 82, 75, 70, 68],
            borderColor: '#d4af37',
            backgroundColor: 'rgba(212, 175, 55, 0.1)',
            tension: 0.4,
            fill: true
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              labels: {
                color: '#fff'
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              max: 100,
              ticks: {
                color: '#fff'
              },
              grid: {
                color: 'rgba(255,255,255,0.1)'
              }
            },
            x: {
              ticks: {
                color: '#fff'
              },
              grid: {
                color: 'rgba(255,255,255,0.1)'
              }
            }
          }
        }
      });

      // Gráfica de Ingresos
      const ctxIngresos = document.getElementById('graficaIngresos').getContext('2d');
      new Chart(ctxIngresos, {
        type: 'bar',
        data: {
          labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
          datasets: [{
            label: 'Ingresos (MXN)',
            data: [95000, 110000, 105000, 120000, 115000, 130000, 145000, 140000, 125000, 120000, 110000, 100000],
            backgroundColor: 'rgba(40, 167, 69, 0.8)',
            borderColor: '#28a745',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              labels: {
                color: '#fff'
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                color: '#fff',
                callback: function(value) {
                  return '$' + value.toLocaleString();
                }
              },
              grid: {
                color: 'rgba(255,255,255,0.1)'
              }
            },
            x: {
              ticks: {
                color: '#fff'
              },
              grid: {
                color: 'rgba(255,255,255,0.1)'
              }
            }
          }
        }
      });
    }

    // Búsqueda en tiempo real para habitaciones (SOLO PARA DATOS REALES)
    document.addEventListener('DOMContentLoaded', function() {
      // Búsqueda para habitaciones (datos reales desde BD)
      document.getElementById('buscarHabitacion').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#listaHabitaciones tr');
        
        rows.forEach(row => {
          const text = row.textContent.toLowerCase();
          row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
      });

      // Búsqueda para reservas (solo si hay datos reales)
document.getElementById('buscarReserva').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#listaReservas tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
    });
  </script>



<script>
// =============================================
// GESTIÓN DE HABITACIONES - FUNCIONALIDAD COMPLETA
// =============================================

let habitacionEditando = null;
let tarifaEditando = null;
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Mostrar modal para nueva/editar habitación
function mostrarModalHabitacion(habitacionId = null) {
  habitacionEditando = habitacionId;
  const modal = document.getElementById('modalHabitacion');
  const titulo = document.getElementById('modalHabitacionTitulo');
  const form = document.getElementById('formHabitacion');
  const methodField = document.getElementById('method-field');
  const inputImagenes = document.getElementById('imagenes');

  mostrarImagenesActuales([]);

  if (habitacionId) {
    titulo.textContent = 'Editar Habitación';
    cargarDatosHabitacion(habitacionId);
    methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
  } else {
    titulo.textContent = 'Nueva Habitación';
    form.reset();
    methodField.innerHTML = '';

    // Establecer valores por defecto
    document.getElementById('capacidad').value = '2';
    document.getElementById('estado').value = 'disponible';
  }

  if (inputImagenes) {
    inputImagenes.value = '';
  }
  mostrarPreviewNuevas();

  modal.style.display = 'flex';
}

// Cerrar modal
function cerrarModalHabitacion() {
  const modal = document.getElementById('modalHabitacion');
  const form = document.getElementById('formHabitacion');

  modal.style.display = 'none';
  if (form) {
    form.reset();
  }
  mostrarImagenesActuales([]);
  mostrarPreviewNuevas();
  habitacionEditando = null;
}

// Cargar datos para edición
// Cargar datos para edición - VERSIÓN MEJORADA CON DEBUG
function cargarDatosHabitacion(habitacionId) {
    console.log('🔍 Cargando datos para habitación ID:', habitacionId);
    
    // Mostrar loading
    const submitBtn = document.querySelector('#formHabitacion button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cargando...';
    submitBtn.disabled = true;

    fetch(`/gerente/habitaciones/${habitacionId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('📡 Respuesta del servidor:', response.status, response.statusText);
        
        if (!response.ok) {
            // Si la respuesta no es exitosa, obtener más detalles del error
            return response.text().then(text => {
                throw new Error(`HTTP ${response.status}: ${response.statusText}. Detalles: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('📦 Datos recibidos:', data);
        
        if (data.success === false) {
            throw new Error(data.message || 'Error en la respuesta del servidor');
        }
        
        // Cargar datos en el formulario
        document.getElementById('numero').value = data.numero || '';
        document.getElementById('tipo_habitacion_id').value = data.tipo_habitacion_id || '';
        document.getElementById('capacidad').value = data.capacidad || '2';
        document.getElementById('estado').value = data.estado || 'disponible';
        document.getElementById('caracteristicas').value = data.caracteristicas || '';

        // Cargar amenidades
        console.log('🏷️ Amenidades recibidas:', data.amenidades);
        if (data.amenidades && Array.isArray(data.amenidades)) {
            document.querySelectorAll('input[name="amenidades[]"]').forEach(checkbox => {
                checkbox.checked = data.amenidades.includes(checkbox.value);
                console.log(`✅ Checkbox ${checkbox.value}: ${checkbox.checked}`);
            });
        } else {
            // Limpiar checkboxes si no hay amenidades
            document.querySelectorAll('input[name="amenidades[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
        }

        mostrarImagenesActuales(data.imagenes || []);
        mostrarPreviewNuevas();

        console.log('✅ Formulario cargado correctamente');
        mostrarMensaje('Datos cargados correctamente', 'success');
    })
    .catch(error => {
        console.error('❌ Error al cargar datos:', error);
        mostrarMensaje('Error al cargar los datos de la habitación: ' + error.message, 'danger');
    })
    .finally(() => {
        // Restaurar botón
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Guardar Habitación';
        submitBtn.disabled = false;
        console.log('🔚 Finalizado proceso de carga');
    });
}
// Enviar formulario de habitación
// =============================================
// FORMULARIO HABITACIONES - VERSIÓN CORREGIDA
// =============================================

document.getElementById('formHabitacion').addEventListener('submit', function(e) {
  e.preventDefault();

  const formElement = e.target;
  const submitBtn = formElement.querySelector('button[type="submit"]');
  const originalHtml = submitBtn.innerHTML;

  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
  submitBtn.disabled = true;

  const formData = new FormData(formElement);

  if (habitacionEditando) {
    formData.append('_method', 'PUT');
  }

  const url = habitacionEditando
    ? `/gerente/habitaciones/${habitacionEditando}`
    : '/gerente/habitaciones';

  fetch(url, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'
    },
    body: formData
  })
  .then(async response => {
    const data = await response.json().catch(() => null);

    if (!response.ok) {
      let message = 'Error al procesar la solicitud.';

      if (data && data.errors) {
        const errores = Object.values(data.errors).flat();
        message = errores.join(' ');
      } else if (data && data.message) {
        message = data.message;
      }

      throw new Error(message);
    }

    return data;
  })
  .then(data => {
    if (data && data.success) {
      mostrarMensaje(data.message, 'success');
      cerrarModalHabitacion();
      setTimeout(() => location.reload(), 1200);
    } else {
      const message = (data && (data.message || data.error)) || 'Error desconocido.';
      mostrarMensaje(message, 'danger');
    }
  })
  .catch(error => {
    console.error('❌ Error en fetch:', error);
    mostrarMensaje('Error al procesar la solicitud: ' + error.message, 'danger');
  })
  .finally(() => {
    submitBtn.innerHTML = originalHtml;
    submitBtn.disabled = false;
  });
});

// Eliminar habitación - VERSIÓN MEJORADA
function eliminarHabitacion(habitacionId) {
  if (!confirm('¿Estás seguro de que deseas eliminar esta habitación?\nEsta acción no se puede deshacer.')) {
    return;
  }

  // Mostrar loading en el botón
  const deleteBtn = document.querySelector(`button[onclick="eliminarHabitacion(${habitacionId})"]`);
  const originalHtml = deleteBtn.innerHTML;
  deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
  deleteBtn.disabled = true;

  fetch(`/gerente/habitaciones/${habitacionId}`, {
    method: 'DELETE',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'Accept': 'application/json'
    }
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Error en la respuesta del servidor');
    }
    return response.json();
  })
  .then(data => {
    if (data.success) {
      mostrarMensaje(data.message, 'success');
      // Eliminar la fila de la tabla
      const fila = document.querySelector(`tr[data-habitacion-id="${habitacionId}"]`);
      if (fila) {
        fila.remove();
      }
      // Actualizar estadísticas
      actualizarEstadisticas();
    } else {
      mostrarMensaje(data.message || 'Error al eliminar la habitación', 'danger');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    mostrarMensaje('Error al eliminar la habitación: ' + error.message, 'danger');
  })
  .finally(() => {
    // Restaurar botón
    deleteBtn.innerHTML = originalHtml;
    deleteBtn.disabled = false;
  });
}

// Función alias para editar
function editarHabitacion(habitacionId) {
  mostrarModalHabitacion(habitacionId);
}

// Mostrar mensajes
function mostrarMensaje(mensaje, tipo) {
  const container = document.getElementById('habitacion-messages');
  const alert = document.createElement('div');
  alert.className = `alert alert-${tipo} alert-dismissible fade show`;
  alert.innerHTML = `
    ${mensaje}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  `;
  container.innerHTML = ''; // Limpiar mensajes anteriores
  container.appendChild(alert);

  setTimeout(() => {
    if (alert.parentNode) {
      alert.remove();
    }
  }, 5000);
}

function mostrarMensajeTarifas(mensaje, tipo) {
  const container = document.getElementById('tarifa-messages');
  if (!container) {
    return;
  }

  const alert = document.createElement('div');
  alert.className = `alert alert-${tipo} alert-dismissible fade show`;
  alert.innerHTML = `
    ${mensaje}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  `;
  container.innerHTML = '';
  container.appendChild(alert);

  setTimeout(() => {
    if (alert.parentNode) {
      alert.remove();
    }
  }, 5000);
}

function mostrarModalTarifa(tarifaId = null) {
  tarifaEditando = tarifaId;
  const modal = document.getElementById('modalTarifa');
  const titulo = document.getElementById('modalTarifaTitulo');
  const form = document.getElementById('formTarifa');
  const methodField = document.getElementById('tarifa-method-field');

  if (!modal || !form) {
    return;
  }

  form.reset();
  methodField.innerHTML = '';

  if (tarifaId) {
    titulo.textContent = 'Editar tarifa';
    methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';

    fetch(`/gerente/tarifas/${tarifaId}`, {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(response => {
        if (!response.ok) {
          throw new Error('No se pudieron cargar los datos de la tarifa.');
        }
        return response.json();
      })
      .then(data => {
        document.getElementById('tarifa_tipo_habitacion_id').value = data.tipo_habitacion_id;
        document.getElementById('tarifa_fecha_inicio').value = data.fecha_inicio;
        document.getElementById('tarifa_fecha_fin').value = data.fecha_fin;
        document.getElementById('tarifa_tipo_temporada').value = data.tipo_temporada;
        document.getElementById('tarifa_precio_modificado').value = data.precio_modificado;
        document.getElementById('tarifa_descripcion').value = data.descripcion || '';
      })
      .catch(error => {
        console.error('Error al cargar la tarifa:', error);
        mostrarMensajeTarifas(error.message, 'danger');
      });
  } else {
    titulo.textContent = 'Nueva tarifa';
  }

  modal.style.display = 'flex';
}

function cerrarModalTarifa() {
  const modal = document.getElementById('modalTarifa');
  const form = document.getElementById('formTarifa');
  const methodField = document.getElementById('tarifa-method-field');

  if (modal) {
    modal.style.display = 'none';
  }

  if (form) {
    form.reset();
  }

  if (methodField) {
    methodField.innerHTML = '';
  }

  tarifaEditando = null;
}

const formTarifa = document.getElementById('formTarifa');
if (formTarifa) {
  formTarifa.addEventListener('submit', function(e) {
    e.preventDefault();

    const submitBtn = formTarifa.querySelector('button[type="submit"]');
    const originalHtml = submitBtn.innerHTML;

    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    submitBtn.disabled = true;

    const payload = {
      tipo_habitacion_id: document.getElementById('tarifa_tipo_habitacion_id').value,
      fecha_inicio: document.getElementById('tarifa_fecha_inicio').value,
      fecha_fin: document.getElementById('tarifa_fecha_fin').value,
      tipo_temporada: document.getElementById('tarifa_tipo_temporada').value,
      precio_modificado: document.getElementById('tarifa_precio_modificado').value,
      descripcion: document.getElementById('tarifa_descripcion').value
    };

    const url = tarifaEditando ? `/gerente/tarifas/${tarifaEditando}` : '/gerente/tarifas';
    const method = tarifaEditando ? 'PUT' : 'POST';

    fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify(payload)
    })
      .then(async response => {
        const data = await response.json().catch(() => null);

        if (!response.ok) {
          let message = 'Error al guardar la tarifa.';

          if (data && data.errors) {
            const errores = Object.values(data.errors).flat();
            message = errores.join(' ');
          } else if (data && data.message) {
            message = data.message;
          }

          throw new Error(message);
        }

        return data;
      })
      .then(data => {
        if (data && data.success) {
          mostrarMensajeTarifas(data.message, 'success');
          cerrarModalTarifa();
          setTimeout(() => location.reload(), 1200);
        } else {
          const message = (data && (data.message || data.error)) || 'Error desconocido.';
          mostrarMensajeTarifas(message, 'danger');
        }
      })
      .catch(error => {
        console.error('Error al guardar tarifa:', error);
        mostrarMensajeTarifas(error.message, 'danger');
      })
      .finally(() => {
        submitBtn.innerHTML = originalHtml;
        submitBtn.disabled = false;
      });
  });
}

function editarTarifa(tarifaId) {
  mostrarModalTarifa(tarifaId);
}

function eliminarTarifa(tarifaId) {
  if (!confirm('¿Eliminar esta tarifa dinámica?')) {
    return;
  }

  const deleteBtn = document.querySelector(`button[onclick="eliminarTarifa(${tarifaId})"]`);
  const originalHtml = deleteBtn ? deleteBtn.innerHTML : null;

  if (deleteBtn) {
    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    deleteBtn.disabled = true;
  }

  fetch(`/gerente/tarifas/${tarifaId}`, {
    method: 'DELETE',
    headers: {
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': csrfToken
    }
  })
    .then(response => {
      if (!response.ok) {
        throw new Error('Error al eliminar la tarifa.');
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        mostrarMensajeTarifas(data.message, 'success');
        const fila = document.querySelector(`tr[data-tarifa-id="${tarifaId}"]`);
        if (fila) {
          fila.remove();
        }
        setTimeout(() => location.reload(), 1200);
      } else {
        mostrarMensajeTarifas(data.message || 'No se pudo eliminar la tarifa.', 'danger');
      }
    })
    .catch(error => {
      console.error('Error al eliminar tarifa:', error);
      mostrarMensajeTarifas(error.message, 'danger');
    })
    .finally(() => {
      if (deleteBtn) {
        deleteBtn.innerHTML = originalHtml;
        deleteBtn.disabled = false;
      }
    });
}

function mostrarImagenesActuales(imagenes = []) {
  const wrapper = document.getElementById('imagenesActualesWrapper');
  const container = document.getElementById('imagenesActuales');

  if (!wrapper || !container) {
    return;
  }

  container.innerHTML = '';

  if (!Array.isArray(imagenes) || imagenes.length === 0) {
    wrapper.classList.add('d-none');
    return;
  }

  wrapper.classList.remove('d-none');

  imagenes.forEach(imagen => {
    const col = document.createElement('div');
    col.className = 'col-4';

    const badge = imagen.es_principal
      ? '<span class="badge bg-warning text-dark position-absolute top-0 start-0 m-1">Principal</span>'
      : '';

    col.innerHTML = `
      <div class="position-relative">
        ${badge}
        <img src="${imagen.url}" alt="Imagen existente" class="img-fluid rounded border" style="height: 90px; width: 100%; object-fit: cover;">
      </div>
    `;

    container.appendChild(col);
  });
}

function mostrarPreviewNuevas(filesList) {
  const wrapper = document.getElementById('nuevasImagenesWrapper');
  const container = document.getElementById('nuevasImagenesPreview');

  if (!wrapper || !container) {
    return;
  }

  const files = filesList ? Array.from(filesList) : [];
  container.innerHTML = '';

  if (files.length === 0) {
    wrapper.classList.add('d-none');
    return;
  }

  wrapper.classList.remove('d-none');

  files.forEach((file, index) => {
    const col = document.createElement('div');
    col.className = 'col-4';

    const objectUrl = URL.createObjectURL(file);

    col.innerHTML = `
      <div class="position-relative">
        ${index === 0 ? '<span class="badge bg-info text-dark position-absolute top-0 start-0 m-1">Principal</span>' : ''}
        <img src="${objectUrl}" alt="Nueva imagen" class="img-fluid rounded border" style="height: 90px; width: 100%; object-fit: cover;">
      </div>
    `;

    const imgElement = col.querySelector('img');
    imgElement.onload = () => URL.revokeObjectURL(objectUrl);

    container.appendChild(col);
  });
}

const inputImagenesHabitacion = document.getElementById('imagenes');
if (inputImagenesHabitacion) {
  inputImagenesHabitacion.addEventListener('change', event => {
    mostrarPreviewNuevas(event.target.files);
  });
}

// Actualizar estadísticas
function actualizarEstadisticas() {
  // Recargar la página para actualizar estadísticas
  setTimeout(() => location.reload(), 1000);
}

// Búsqueda en tiempo real
document.getElementById('buscarHabitacion').addEventListener('input', function(e) {
  const searchTerm = e.target.value.toLowerCase();
  const rows = document.querySelectorAll('#listaHabitaciones tr');
  
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(searchTerm) ? '' : 'none';
  });
});

// Cerrar modal al hacer clic fuera
window.addEventListener('click', function(e) {
  const modal = document.getElementById('modalHabitacion');
  const modalTarifa = document.getElementById('modalTarifa');
  if (e.target === modal) {
    cerrarModalHabitacion();
  }
  if (e.target === modalTarifa) {
    cerrarModalTarifa();
  }
});

</script>



<!-- FullCalendar + ES locale -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/locales-all.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // --- Tabs Lista/Calendario
  const tabBtns = document.querySelectorAll('[data-res-tab]');
  const tabLista = document.getElementById('tab-res-lista');
  const tabCal   = document.getElementById('tab-res-calendario');
  tabBtns.forEach(btn=>{
    btn.addEventListener('click', ()=>{
      tabBtns.forEach(b=>b.classList.remove('active'));
      btn.classList.add('active');
      if (btn.dataset.resTab === 'lista') {
        tabLista.classList.add('active');  tabCal.classList.remove('active');
      } else {
        tabCal.classList.add('active');    tabLista.classList.remove('active');
        calendar.render(); // asegura render cuando se muestra
      }
    });
  });

  // --- Filtros/inputs
  const buscarInput = document.getElementById('buscarReserva');
  const selHab      = document.getElementById('filtroHabitacion');
  const selEstado   = document.getElementById('filtroEstado');
  const inpDesde    = document.getElementById('filtroDesde');
  const inpHasta    = document.getElementById('filtroHasta');
  const tbody       = document.getElementById('listaReservas');

  // --- Cargar LISTA
  async function cargarLista() {
    const params = new URLSearchParams({
      q: (buscarInput.value || '').trim(),
      habitacion_id: selHab.value || '',
      estado: selEstado.value || '',
      desde: inpDesde.value || '',
      hasta: inpHasta.value || ''
    });
    const res = await fetch(`{{ route('admin.reservas.list') }}?`+params.toString(), {headers:{'Accept':'application/json'}});
    const json = await res.json();

    const badge = (estado) => {
      const map = {
        pendiente:  'estado-pendiente',
        confirmada: 'text-info fw-bold',
        activa:     'estado-activa',
        completada: 'text-secondary fw-bold',
        cancelada:  'estado-finalizada'
      };
      const cls = map[estado] || 'text-muted';
      return `<span class="${cls}">${(estado||'').charAt(0).toUpperCase()+estado.slice(1)}</span>`;
    };

    tbody.innerHTML = (json.items || []).map(r => `
      <tr>
        <td><strong>${r.habitacion ?? '-'}</strong></td>
        <td>${r.huesped ?? '-'}</td>
        <td>${r.check_in}</td>
        <td>${r.check_out}</td>
        <td>${badge(r.estado)}</td>
        <td>$${r.precio}</td>
      </tr>
    `).join('') || `
      <tr><td colspan="6" class="text-muted text-center py-3">Sin resultados</td></tr>
    `;
  }

  [buscarInput, selHab, selEstado, inpDesde, inpHasta].forEach(el => {
    el.addEventListener('input', () => { cargarLista(); calendar.refetchEvents(); });
    el.addEventListener('change', () => { cargarLista(); calendar.refetchEvents(); });
  });

  // Inicial
  cargarLista();

  // --- CALENDARIO (FullCalendar)
  const calendarEl = document.getElementById('calendarioReservas');
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    height: 'auto',
    locale: 'es',       // << español
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
    },
    navLinks: true,
    nowIndicator: true,
    eventTimeFormat: { hour: '2-digit', minute: '2-digit', meridiem: false },

    // Pasa filtros actuales al backend cada vez que el calendario pida eventos
    events: (info, success, failure) => {
      const params = new URLSearchParams({
        start: info.startStr,
        end: info.endStr,
        habitacion_id: selHab.value || '',
        estado: selEstado.value || ''
      });
      fetch(`{{ route('admin.reservas.events') }}?`+params.toString(), {headers:{'Accept':'application/json'}})
        .then(r => r.json())
        .then(data => success(data))
        .catch(err => failure(err));
    },

    eventDidMount: (arg) => {
      // Tooltip simple
      const p = arg.event.extendedProps;
      arg.el.title = `${arg.event.title}\n${p.estado.toUpperCase()} · $${(p.precio||0).toFixed(2)}`;
    },

    // Opcional: click para ver detalle (puedes dirigir a una vista si quieres)
    eventClick: (info) => {
      const p = info.event.extendedProps;
      alert(
        `Reserva #${info.event.id}\n` +
        `Habitación: ${p.habitacion}\n` +
        `Huésped: ${p.huesped}\n` +
        `Estado: ${p.estado}\n` +
        `Entrada: ${info.event.startStr}\n` +
        `Salida: ${info.event.endStr}\n` +
        `Total: $${(p.precio||0).toFixed(2)}`
      );
    }
  });

  // Render diferido (cuando abras la pestaña Calendario)
  // Si tu página abre con la pestaña de Reservas visible, puedes llamar aquí:
  // calendar.render();


  // Navegación entre secciones principales
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
      
      // Inicializar gráficas si es la sección de reportes
      if (targetId === 'reportes') {
        setTimeout(inicializarGraficas, 100);
      }
    }
  });
});


// --- Hacer clickeables las tarjetas del inicio ---
const cardsInicio = document.querySelectorAll('.card-inicio[data-target]');

cardsInicio.forEach(card => {
  card.style.cursor = 'pointer'; // para que salga la manita

  card.addEventListener('click', () => {
    const targetId = card.dataset.target;

    // Buscar el link del sidebar que apunte a esa sección
    const navLink = document.querySelector(`.nav-link[data-target="${targetId}"]`);

    if (navLink) {
      // Disparamos el click del sidebar para reutilizar toda la lógica que ya tienes
      navLink.click();
    } else {
      // Por si acaso, fallback directo
      secciones.forEach(sec => sec.classList.remove('visible'));
      const targetSection = document.getElementById(targetId);
      if (targetSection) {
        targetSection.classList.add('visible');
      }
    }
  });
});
});
</script>





</body>
</html>