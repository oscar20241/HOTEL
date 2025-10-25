<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Huésped | Pasa el Extra Inn</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Estilos personalizados -->
  @vite(['resources/css/estilo.css'])

  <!-- Iconos -->
  <script src="https://kit.fontawesome.com/a2d04a4f5d.js" crossorigin="anonymous"></script>
 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>
  <div class="dashboard-container d-flex">
    
    <!-- === SIDEBAR === -->
    <aside class="sidebar p-3">
      <img src="{{ asset('/img/logo.png') }}" alt="Logo del hotel" class="logo-dash mb-3">
      <h4 class="text-center mb-4">Huésped</h4>

      <nav class="nav flex-column">
        <a href="#" class="nav-link active" data-target="inicio">
          <i class="fas fa-house-user"></i> Inicio
        </a>
        <a href="#" class="nav-link" data-target="reservas">
          <i class="fas fa-calendar-check"></i> Mis Reservaciones
        </a>
        <a href="#" class="nav-link" data-target="nueva">
          <i class="fas fa-bed"></i> Nueva Reservación
        </a>
        <a href="#" class="nav-link" data-target="perfil">
          <i class="fas fa-user-circle"></i> Perfil
        </a>
        <a href="#" class="nav-link text-danger mt-auto" data-target="cerrar">
          <i class="fas fa-power-off"></i> Cerrar sesión
        </a>
      </nav>
    </aside>

    <!-- === CONTENIDO PRINCIPAL === -->
    <main class="main-content flex-grow-1 p-4">
      
      <!-- 🏠 INICIO -->
      <div id="inicio" class="seccion visible">
        <h2 class="mb-4">Bienvenido a Pasa el Extra Inn</h2>
        <div class="row g-4">
          
          <!-- Próxima reservación -->
          <div class="col-md-6">
            <div class="card info-card">
              <div class="card-body">
                <h5 class="card-title">
                  <i class="fas fa-calendar-check text-warning"></i> Próxima Reservación
                </h5>
                <p class="card-text">Del 25 al 28 de Octubre</p>
              </div>
            </div>
          </div>

          <!-- Servicios activos -->
          <div class="col-md-6">
            <div class="card info-card">
              <div class="card-body">
                <h5 class="card-title">
                  <i class="fas fa-concierge-bell text-warning"></i> Servicios Activos
                </h5>
                <p class="card-text">Room Service, Spa</p>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- 📅 MIS RESERVACIONES -->
      <div id="reservas" class="seccion">
        <h2>Mis Reservaciones</h2>
        <p>Aquí aparecerán tus reservaciones activas y pasadas.</p>
      </div>

      <!-- 🛏️ NUEVA RESERVACIÓN -->
      <div id="nueva" class="seccion">
        <h2>Nueva Reservación</h2>

        <div class="row g-3 align-items-end mt-3">
          <div class="col-md-8">
            <select class="form-select" required>
              <option value="">Seleccione un servicio</option>
              <option value="limpieza">Limpieza</option>
              <option value="comida">Comida a la habitación</option>
              <option value="spa">Spa</option>
              <option value="lavanderia">Lavandería</option>
            </select>
          </div>

          <div class="col-md-4">
            <button class="btn btn-primary w-100" type="submit">
              <i class="fas fa-paper-plane"></i> Enviar
            </button>
          </div>
        </div>

        <div class="text-center mt-4">
          <button id="abrirModal" class="btn btn-primary px-4 py-2">
            <i class="fa-solid fa-bed"></i> Generar Reservación
          </button>
        </div>
      </div>

      <!-- 👤 PERFIL -->
      <div id="perfil" class="seccion">
        <h2>Perfil</h2>
        <p>Información del huésped, preferencias y datos personales.</p>
      </div>

      <!-- 🔒 CERRAR SESIÓN - CON FORMULARIO FUNCIONAL -->
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

  <!-- 🪟 MODAL (fuera del contenido principal) -->
  <div id="modalReservacion" class="modal">
    <div class="modal-contenido">
      <span class="cerrar">&times;</span>
      <h2>Formulario de Reservación</h2>

      <form id="formReservacion">
        <label for="tipo">Tipo de habitación:</label>
        <select id="tipo" required>
          <option value="">Selecciona una opción</option>
          <option value="Sencilla">Sencilla</option>
          <option value="Doble">Doble</option>
          <option value="Suite">Suite</option>
        </select>

        <label for="llegada">Fecha de llegada:</label>
        <input type="date" id="llegada" required>

        <label for="salida">Fecha de salida:</label>
        <input type="date" id="salida" required>

        <label for="comentario">Comentario:</label>
        <textarea id="comentario" rows="3" placeholder="Escribe algún comentario..."></textarea>

        <button type="submit" class="btn-confirmar">
          Confirmar Reservación
        </button>
      </form>
    </div>
  </div>

  <!-- === SCRIPT PRINCIPAL === -->
  <script>
    // Modal
    const modal = document.getElementById('modalReservacion');
    const btn = document.getElementById('abrirModal');
    const span = document.getElementsByClassName('cerrar')[0];
    const form = document.getElementById('formReservacion');

    btn.onclick = () => modal.style.display = 'flex';
    span.onclick = () => modal.style.display = 'none';
    window.onclick = (event) => {
      if (event.target === modal) modal.style.display = 'none';
    };

    // Guardar reservación
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const tipo = document.getElementById('tipo').value;
      const llegada = document.getElementById('llegada').value;
      const salida = document.getElementById('salida').value;
      const comentario = document.getElementById('comentario').value;

      const nuevaReserva = {
        tipo,
        llegada,
        salida,
        comentario,
        fechaRegistro: new Date().toLocaleDateString()
      };

      let reservaciones = JSON.parse(localStorage.getItem('reservaciones')) || [];
      reservaciones.push(nuevaReserva);
      localStorage.setItem('reservaciones', JSON.stringify(reservaciones));

      alert("✅ Reservación guardada correctamente.");
      modal.style.display = 'none';
      form.reset();
    });

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
        if (targetSection) targetSection.classList.add('visible');
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