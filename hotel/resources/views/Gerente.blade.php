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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <!-- Chart.js para gráficas -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    // Sistema de pestañas para usuarios (SIMPLIFICADO)
    // Sistema de pestañas para usuarios (ACTUALIZADO)
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

    // Datos de ejemplo para reservas y habitaciones
    document.addEventListener('DOMContentLoaded', function() {
      // Datos de ejemplo para reservas
      const reservas = [
        { habitacion: '101', huesped: 'Juan Pérez', checkin: '2024-01-15', checkout: '2024-01-20', estado: 'Activa' },
        { habitacion: '205', huesped: 'María García', checkin: '2024-01-14', checkout: '2024-01-18', estado: 'Activa' },
        { habitacion: '312', huesped: 'Carlos López', checkin: '2024-01-16', checkout: '2024-01-22', estado: 'Activa' }
      ];

      // Datos de ejemplo para habitaciones
      const habitaciones = [
        { numero: '101', tipo: 'Sencilla', precio: '$800', estado: 'Ocupada' },
        { numero: '102', tipo: 'Sencilla', precio: '$800', estado: 'Disponible' },
        { numero: '201', tipo: 'Doble', precio: '$1200', estado: 'Ocupada' },
        { numero: '202', tipo: 'Doble', precio: '$1200', estado: 'Disponible' },
        { numero: '301', tipo: 'Suite', precio: '$2000', estado: 'Ocupada' }
      ];

      // Llenar tabla de reservas
      const listaReservas = document.getElementById('listaReservas');
      reservas.forEach(reserva => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${reserva.habitacion}</td>
          <td>${reserva.huesped}</td>
          <td>${reserva.checkin}</td>
          <td>${reserva.checkout}</td>
          <td><span class="badge bg-success">${reserva.estado}</span></td>
        `;
        listaReservas.appendChild(tr);
      });

      // Llenar tabla de habitaciones
      const listaHabitaciones = document.getElementById('listaHabitaciones');
      habitaciones.forEach(habitacion => {
        const tr = document.createElement('tr');
        const estadoBadge = habitacion.estado === 'Ocupada' ? 'bg-warning' : 'bg-success';
        tr.innerHTML = `
          <td>${habitacion.numero}</td>
          <td>${habitacion.tipo}</td>
          <td>${habitacion.precio}</td>
          <td><span class="badge ${estadoBadge}">${habitacion.estado}</span></td>
        `;
        listaHabitaciones.appendChild(tr);
      });

      // Funcionalidad de búsqueda
      document.getElementById('buscarReserva').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = listaReservas.getElementsByTagName('tr');
        
        for (let row of rows) {
          const text = row.textContent.toLowerCase();
          row.style.display = text.includes(searchTerm) ? '' : 'none';
        }
      });

      document.getElementById('buscarHabitacion').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = listaHabitaciones.getElementsByTagName('tr');
        
        for (let row of rows) {
          const text = row.textContent.toLowerCase();
          row.style.display = text.includes(searchTerm) ? '' : 'none';
        }
      });
    });
  </script>
</body>
</html>