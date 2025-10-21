<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Usuario</title>
  @vite(['resources/css/estilo.css', 'resources/js/app.js'])
</head>


<body>
  <!-- Panel lateral -->
  <div class="sidebar">
    <button onclick="showSection('misCitas')"><i class="fas fa-calendar-check"></i>Mis Citas</button><br>
    <button onclick="showSection('NuevaCita')"><i class="fas fa-plus-circle"></i>Nueva Cita</button><br>
    <button onclick="showSection('modificarCita')"><i class="fas fa-edit"></i>Modificar Cita</button><br>
    <button onclick="showSection('historial')"><i class="fas fa-history"></i>Historial Citas</button><br>
    <button onclick="showSection('cancelar')"><i class="fas fa-times-circle"></i>Cancelar Cita</button><br>
    <button onclick="showSection('recordatorios')"><i class="fas fa-bell"></i>Recordatorios</button><br>
  </div>

  <!-- Contenido principal -->
  <div class="content">
    <h1><strong>Bienvenido, Juan</strong></h1>
    <img src="logo.PNG" alt="logo" class="logo" id="mainLogo">
    <img src="{{ asset('IMG/logo.png') }}" alt="logo" Class="logo">
    <!-- Mensaje de bienvenida inicial -->
    <div id="welcomeMessage" class="welcome-message">
      <h2>Bienvenido a tu Panel de Control</h2>
      <p>Selecciona una opción del menú lateral para comenzar.</p>
    </div>

    <div id="misCitas" class="section">
      <h2>Mis Citas</h2>
      <p>Aquí se mostrarán todas tus citas programadas.</p>
      <div class="section-content">
        <!-- Contenido adicional para mis citas puede ir aquí -->
      </div>
    </div>

    <div id="modificarCita" class="section">
      <h2>Modificar Cita</h2>
      <p>Desde aquí podrás cambiar la fecha o detalles de una cita.</p>
      <div class="section-content">
        <!-- Contenido adicional para modificar cita puede ir aquí -->
      </div>
    </div>

    <div id="NuevaCita" class="section">
      <h2>Nueva Cita</h2>
      <p>Desde aquí podrás crear una nueva cita.</p>
      <div class="section-content">
        <!-- Contenido adicional para nueva cita puede ir aquí -->
      </div>
    </div>

    <div id="historial" class="section">
      <h2>Historial de Citas</h2>
      <p>Consulta el historial de todas las citas anteriores.</p>
      <div class="section-content">
        <!-- Contenido adicional para historial puede ir aquí -->
      </div>
    </div>

    <div id="cancelar" class="section">
      <h2>Cancelar Cita</h2>
      <p>Puedes cancelar una cita próxima desde este panel.</p>
      <div class="section-content">
        <!-- Contenido adicional para cancelar cita puede ir aquí -->
      </div>
    </div>

    <div id="recordatorios" class="section">
      <h2>Recordatorios</h2>
      <p>Aquí podrás ver y configurar tus recordatorios.</p>
      <div class="section-content">
        <!-- Contenido adicional para recordatorios puede ir aquí -->
      </div>
    </div>
  </div>

  <script>
    function showSection(sectionId) {
      // Ocultar mensaje de bienvenida
      const welcomeMessage = document.getElementById("welcomeMessage");
      if (welcomeMessage) {
        welcomeMessage.style.opacity = "0";
        setTimeout(() => {
          welcomeMessage.style.display = "none";
        }, 300);
      }

      // Ocultar logo con transición suave
      const logo = document.getElementById("mainLogo");
      logo.style.opacity = "0";
      setTimeout(() => {
        logo.style.display = "none";
      }, 300);

      // Ocultar todas las secciones con transición
      const sections = document.querySelectorAll(".section");
      sections.forEach(sec => {
        sec.style.opacity = "0";
        sec.style.transform = "translateY(20px)";
        setTimeout(() => {
          sec.style.display = "none";
        }, 300);
      });

      // Mostrar la sección seleccionada con animación
      const selected = document.getElementById(sectionId);
      if (selected) {
        setTimeout(() => {
          selected.style.display = "block";
          setTimeout(() => {
            selected.style.opacity = "1";
            selected.style.transform = "translateY(0)";
          }, 50);
        }, 300);
      }
    }

    // Mostrar elementos al cargar la página con animación
    window.addEventListener('load', function() {
      const logo = document.getElementById("mainLogo");
      const welcomeMessage = document.getElementById("welcomeMessage");
      
      // Animación para el logo
      logo.style.opacity = "0";
      logo.style.display = "block";
      setTimeout(() => {
        logo.style.opacity = "1";
        logo.style.transition = "opacity 0.5s ease";
      }, 100);

      // Mostrar mensaje de bienvenida
      if (welcomeMessage) {
        welcomeMessage.style.display = "block";
        welcomeMessage.style.opacity = "1";
        welcomeMessage.style.transition = "opacity 0.5s ease";
      }
    });

    // Función para volver al inicio (puedes agregar un botón si lo necesitas)
    function showWelcome() {
      // Ocultar todas las secciones
      const sections = document.querySelectorAll(".section");
      sections.forEach(sec => {
        sec.style.opacity = "0";
        sec.style.transform = "translateY(20px)";
        setTimeout(() => {
          sec.style.display = "none";
        }, 300);
      });

      // Mostrar logo y mensaje de bienvenida
      const logo = document.getElementById("mainLogo");
      const welcomeMessage = document.getElementById("welcomeMessage");
      
      setTimeout(() => {
        logo.style.display = "block";
        welcomeMessage.style.display = "block";
        
        setTimeout(() => {
          logo.style.opacity = "1";
          logo.style.transform = "scale(1)";
          welcomeMessage.style.opacity = "1";
          welcomeMessage.style.transform = "translateY(0)";
        }, 50);
      }, 300);
    }
  </script>
</body>
</html>