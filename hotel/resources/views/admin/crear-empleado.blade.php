<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Nuevo Empleado - Hotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  @vite(['resources/css/estilo2.css'])
  <script src="https://kit.fontawesome.com/a2d04a4f5d.js" crossorigin="anonymous"></script>
  <style>
    .is-invalid {
      border: 2px solid #dc3545 !important;
      background-color: #fff5f5 !important;
    }
    .is-valid {
      border: 2px solid #198754 !important;
      background-color: #f8fff9 !important;
    }
    .invalid-feedback {
      display: none;
      color: #dc3545;
      font-size: 0.875em;
      margin-top: 0.25rem;
    }
    .valid-feedback {
      display: none;
      color: #198754;
      font-size: 0.875em;
      margin-top: 0.25rem;
    }
    .form-control:read-only {
      background-color: #e9ecef !important;
      opacity: 1;
      cursor: not-allowed;
    }
  </style>
</head>

<body>
  <div class="dashboard-container">
    <main class="main-content">
      <div class="reservacion-container">
        <h2 class="text-center mb-4">Registro de Nuevo Empleado</h2>

        <!-- Mostrar errores del servidor -->
        @if ($errors->any())
          <div class="alert alert-danger">
            <strong>❌ Errores encontrados:</strong>
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <!-- Mostrar éxito -->
        @if (session('success'))
          <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
          </div>
        @endif

        <!-- Formulario -->
        <form method="POST" action="{{ route('admin.empleados.store') }}" id="formEmpleado" novalidate>
          @csrf
          
          <h5 class="text-warning mb-3">📋 Información Personal</h5>
          
          <!-- Nombre -->
          <div class="mb-3">
            <label for="name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" 
                   placeholder="Ej. Juan Pérez García" required
                   oninput="validateField(this, 'nombre')">
            <div class="invalid-feedback" id="name-error">
              ❌ Por favor ingresa un nombre válido (mínimo 2 caracteres).
            </div>
            <div class="valid-feedback" id="name-success">
              ✅ Nombre válido
            </div>
          </div>

          <!-- Email -->
          <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" 
                   placeholder="empleado@hotel.com" required
                   oninput="validateEmail(this)">
            <div class="invalid-feedback" id="email-error">
              ❌ Por favor ingresa un correo electrónico válido.
            </div>
            <div class="valid-feedback" id="email-success">
              ✅ Correo electrónico válido
            </div>
          </div>

          <!-- Contraseñas -->
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password" name="password" 
                       placeholder="Mínimo 8 caracteres" required
                       oninput="validatePassword(this)">
                <div class="invalid-feedback" id="password-error">
                  ❌ La contraseña debe tener al menos 8 caracteres.
                </div>
                <div class="valid-feedback" id="password-success">
                  ✅ Contraseña válida
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
                       placeholder="Repetir contraseña" required
                       oninput="validatePasswordConfirmation(this)">
                <div class="invalid-feedback" id="password_confirmation-error">
                  ❌ Las contraseñas no coinciden.
                </div>
                <div class="valid-feedback" id="password_confirmation-success">
                  ✅ Contraseñas coinciden
                </div>
              </div>
            </div>
          </div>

          <!-- Teléfono -->
          <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
            <input type="tel" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}" 
                   placeholder="Ej. 555-123-4567" required
                   oninput="validatePhone(this)">
            <div class="invalid-feedback" id="telefono-error">
              ❌ Por favor ingresa un número de teléfono válido.
            </div>
            <div class="valid-feedback" id="telefono-success">
              ✅ Teléfono válido
            </div>
          </div>

          <!-- Dirección -->
          <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}" 
                   placeholder="Ej. Calle Principal #123, Ciudad"
                   oninput="validateOptionalField(this, 'dirección')">
            <div class="valid-feedback" id="direccion-success">
              ✅ Dirección válida
            </div>
          </div>

          <!-- Fecha Nacimiento -->
          <div class="mb-3">
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" 
                   value="{{ old('fecha_nacimiento') }}" required
                   onchange="validateBirthDate(this)">
            <div class="invalid-feedback" id="fecha_nacimiento-error">
              ❌ El empleado debe ser mayor de 18 años.
            </div>
            <div class="valid-feedback" id="fecha_nacimiento-success">
              ✅ Edad válida (mayor de 18 años)
            </div>
            <small class="form-text text-muted">El empleado debe ser mayor de 18 años</small>
          </div>

          <hr class="my-4">

          <h5 class="text-warning mb-3">💼 Información Laboral</h5>

          <!-- Número de empleado oculto -->
          <input type="hidden" name="numero_empleado" value="">

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="puesto" class="form-label">Puesto <span class="text-danger">*</span></label>
                <select class="form-select" id="puesto" name="puesto" required
                        onchange="validateSelect(this, 'puesto')">
                  <option value="">Seleccionar puesto</option>
                  <option value="recepcionista" {{ old('puesto') == 'recepcionista' ? 'selected' : '' }}>Recepcionista</option>
                  <option value="limpieza" {{ old('puesto') == 'limpieza' ? 'selected' : '' }}>Limpieza</option>
                  <option value="administrador" {{ old('puesto') == 'administrador' ? 'selected' : '' }}>Administrador</option>
                  <option value="gerente" {{ old('puesto') == 'gerente' ? 'selected' : '' }}>Gerente</option>
                </select>
                <div class="invalid-feedback" id="puesto-error">
                  ❌ Por favor selecciona un puesto.
                </div>
                <div class="valid-feedback" id="puesto-success">
                  ✅ Puesto seleccionado
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="fecha_contratacion" class="form-label">Fecha de Contratación <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fecha_contratacion" name="fecha_contratacion" 
                       value="{{ date('Y-m-d') }}" readonly
                       onchange="validateHireDate(this)">
                <div class="valid-feedback" id="fecha_contratacion-success">
                  ✅ Fecha de contratación: Hoy
                </div>
                <small class="form-text text-muted">Fecha actual (no editable)</small>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="salario" class="form-label">Salario ($) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="salario" name="salario" value="{{ old('salario') }}" 
                       step="0.01" min="0" placeholder="0.00" required
                       oninput="validateSalary(this)">
                <div class="invalid-feedback" id="salario-error">
                  ❌ El salario debe ser mayor a 0.
                </div>
                <div class="valid-feedback" id="salario-success">
                  ✅ Salario válido
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="turno" class="form-label">Turno <span class="text-danger">*</span></label>
                <select class="form-select" id="turno" name="turno" required
                        onchange="validateSelect(this, 'turno')">
                  <option value="">Seleccionar turno</option>
                  <option value="matutino" {{ old('turno') == 'matutino' ? 'selected' : '' }}>Matutino</option>
                  <option value="vespertino" {{ old('turno') == 'vespertino' ? 'selected' : '' }}>Vespertino</option>
                  <option value="nocturno" {{ old('turno') == 'nocturno' ? 'selected' : '' }}>Nocturno</option>
                  <option value="mixto" {{ old('turno') == 'mixto' ? 'selected' : '' }}>Mixto</option>
                </select>
                <div class="invalid-feedback" id="turno-error">
                  ❌ Por favor selecciona un turno.
                </div>
                <div class="valid-feedback" id="turno-success">
                  ✅ Turno seleccionado
                </div>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label for="estado" class="form-label">Estado Inicial <span class="text-danger">*</span></label>
            <select class="form-select" id="estado" name="estado" required
                    onchange="validateSelect(this, 'estado')">
              <option value="activo" selected>Activo</option>
              <option value="inactivo">Inactivo</option>
              <option value="vacaciones">Vacaciones</option>
              <option value="licencia">Licencia</option>
            </select>
            <small class="form-text text-muted">Por defecto, los nuevos empleados se crean como "Activos"</small>
            <div class="valid-feedback" id="estado-success">
              ✅ Estado seleccionado
            </div>
          </div>

          <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea class="form-control" id="observaciones" name="observaciones" rows="3" 
                      placeholder="Notas adicionales sobre el empleado">{{ old('observaciones') }}</textarea>
          </div>

          <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary me-2" id="submitBtn">
              <i class="fas fa-user-plus"></i> Registrar Empleado
            </button>
            <a href="{{ route('admin.usuarios') }}" class="btn btn-secondary">
              <i class="fas fa-arrow-left"></i> Volver al Listado
            </a>
          </div>
        </form>
      </div>
    </main>
  </div>

  <script>
    // Mostrar número de empleado que se generará
    document.addEventListener('DOMContentLoaded', function() {
      const randomNum = Math.random().toString(36).substring(2, 8).toUpperCase();
      const numeroEmpleadoGenerado = 'EMP' + randomNum;
      
      const estadoField = document.getElementById('estado');
      const helperText = document.createElement('small');
      helperText.className = 'form-text text-muted';
      helperText.innerHTML = `🔢 Número de empleado que se generará: <strong>${numeroEmpleadoGenerado}</strong>`;
      estadoField.parentNode.appendChild(helperText);

      // Configurar fecha máxima para nacimiento (18 años atrás)
      const fechaNacimiento = document.getElementById('fecha_nacimiento');
      const today = new Date();
      const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
      fechaNacimiento.max = maxDate.toISOString().split('T')[0];

      // Marcar fecha de contratación como válida
      const fechaContratacion = document.getElementById('fecha_contratacion');
      fechaContratacion.classList.add('is-valid');
      document.getElementById('fecha_contratacion-success').style.display = 'block';

      // Validar todos los campos al cargar la página
      validateAllFields();
    });

    // Función para validar campo genérico
    function validateField(field, fieldName) {
      const value = field.value.trim();
      const isValid = value.length >= 2;
      
      updateFieldValidation(field, isValid, fieldName);
      return isValid;
    }

    // Validar email
    function validateEmail(field) {
      const email = field.value.trim();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      const isValid = emailRegex.test(email);
      
      updateFieldValidation(field, isValid, 'email');
      return isValid;
    }

    // Validar contraseña
    function validatePassword(field) {
      const password = field.value;
      const isValid = password.length >= 8;
      
      updateFieldValidation(field, isValid, 'password');
      
      // También validar confirmación si ya tiene valor
      const confirmField = document.getElementById('password_confirmation');
      if (confirmField.value) {
        validatePasswordConfirmation(confirmField);
      }
      
      return isValid;
    }

    // Validar confirmación de contraseña
    function validatePasswordConfirmation(field) {
      const password = document.getElementById('password').value;
      const confirmation = field.value;
      const isValid = password === confirmation && confirmation.length >= 8;
      
      updateFieldValidation(field, isValid, 'password_confirmation');
      return isValid;
    }

    // Validar teléfono
    function validatePhone(field) {
      const phone = field.value.trim();
      const phoneRegex = /^[\d\s\-\+\(\)]{10,}$/;
      const isValid = phoneRegex.test(phone) && phone.replace(/\D/g, '').length >= 10;
      
      updateFieldValidation(field, isValid, 'teléfono');
      return isValid;
    }

    // Validar campo opcional
    function validateOptionalField(field, fieldName) {
      const value = field.value.trim();
      const isValid = value === '' || value.length >= 2;
      
      if (isValid && value !== '') {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        document.getElementById(field.id + '-success').style.display = 'block';
        document.getElementById(field.id + '-error').style.display = 'none';
      } else {
        field.classList.remove('is-valid', 'is-invalid');
        document.getElementById(field.id + '-success').style.display = 'none';
        document.getElementById(field.id + '-error').style.display = 'none';
      }
      return true;
    }

    // Validar fecha de nacimiento (mayor de 18 años)
    function validateBirthDate(field) {
      if (!field.value) {
        updateFieldValidation(field, false, 'fecha_nacimiento');
        return false;
      }

      const selectedDate = new Date(field.value);
      const today = new Date();
      const minDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
      
      const isValid = selectedDate <= minDate;
      
      updateFieldValidation(field, isValid, 'fecha_nacimiento');
      return isValid;
    }

    // Validar fecha de contratación (siempre válida porque es hoy)
    function validateHireDate(field) {
      // Siempre válida porque es fija y es la fecha actual
      updateFieldValidation(field, true, 'fecha_contratacion');
      return true;
    }

    // Validar salario
    function validateSalary(field) {
      const salary = parseFloat(field.value);
      const isValid = !isNaN(salary) && salary > 0;
      
      updateFieldValidation(field, isValid, 'salario');
      return isValid;
    }

    // Validar select
    function validateSelect(field, fieldName) {
      const isValid = field.value !== '';
      
      updateFieldValidation(field, isValid, fieldName);
      return isValid;
    }

    // Actualizar estado visual del campo
    function updateFieldValidation(field, isValid, fieldName) {
      const errorElement = document.getElementById(field.id + '-error');
      const successElement = document.getElementById(field.id + '-success');
      
      if (isValid) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        if (errorElement) errorElement.style.display = 'none';
        if (successElement) successElement.style.display = 'block';
      } else {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
        if (errorElement) errorElement.style.display = 'block';
        if (successElement) successElement.style.display = 'none';
      }
    }

    // Validar todos los campos antes de enviar
    function validateAllFields() {
      const fields = [
        { id: 'name', validator: validateField },
        { id: 'email', validator: validateEmail },
        { id: 'password', validator: validatePassword },
        { id: 'password_confirmation', validator: validatePasswordConfirmation },
        { id: 'telefono', validator: validatePhone },
        { id: 'fecha_nacimiento', validator: validateBirthDate },
        { id: 'puesto', validator: validateSelect },
        { id: 'salario', validator: validateSalary },
        { id: 'turno', validator: validateSelect },
        { id: 'estado', validator: validateSelect }
      ];

      let allValid = true;

      fields.forEach(fieldInfo => {
        const field = document.getElementById(fieldInfo.id);
        if (field) {
          const isValid = fieldInfo.validator(field);
          if (!isValid) allValid = false;
        }
      });

      return allValid;
    }

    // Interceptar envío del formulario
    document.getElementById('formEmpleado').addEventListener('submit', function(e) {
      if (!validateAllFields()) {
        e.preventDefault();
        showAlert('❌ Por favor corrige los errores en el formulario antes de enviar.', 'danger');
        
        // Scroll al primer error
        const firstError = document.querySelector('.is-invalid');
        if (firstError) {
          firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
          firstError.focus();
        }
      }
    });

    // Mostrar alerta temporal
    function showAlert(message, type) {
      const alertDiv = document.createElement('div');
      alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
      alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      `;
      
      const form = document.getElementById('formEmpleado');
      form.parentNode.insertBefore(alertDiv, form);
      
      // Auto-eliminar después de 5 segundos
      setTimeout(() => {
        if (alertDiv.parentNode) {
          alertDiv.remove();
        }
      }, 5000);
    }
  </script>
</body>
</html>