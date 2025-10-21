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
    <button onclick="showSection('Citas')">Citas Asignadas</button><br>
    <button onclick="showSection('Estatus')">Registrar Estatus</button><br>
    <button onclick="showSection('Observaciones')">Subir Observaciones</button><br>
  </div>

  <!-- Contenido principal -->
  <div class="content">
    <h1><strong>Bienvenido, Juan</strong></h1>
    <img src="{{ asset('IMG/logo.png') }}" alt="logo" Class="logo4" id="mainLogo">
    <!-- Mensaje de bienvenida inicial -->
    <div id="welcomeMessage" class="welcome-message4">
      <h2>Bienvenido a tu Panel de Control</h2>
      <p>Selecciona una opción del menú lateral para comenzar.</p>
    </div>

    <div id="Citas" class="section4">
      <h2>Citas Asignadas</h2>
      <p>Aquí se mostrarán todas tus citas Asignadas.</p>
      <div class="section-content">
        <!-- Contenido adicional para mis citas puede ir aquí -->
      </div>
    </div>

    <div id="Estatus" class="section4">
      <h2>Registrar Estatus</h2>
      <p>Desde aquí podrás cambiar la fecha o detalles de una cita.</p>
      <div class="section-content">
        <!-- Contenido adicional para modificar cita puede ir aquí -->
      </div>
    </div>

    <div id="Observaciones" class="section4">
      <h2>Subir Observaciones</h2>
      <p>Desde aquí podrás crear una nueva cita.</p>
      <div class="section-content">
        <!-- Contenido adicional para nueva cita puede ir aquí -->
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
      const sections = document.querySelectorAll(".section4");
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
      const sections = document.querySelectorAll(".section4");
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