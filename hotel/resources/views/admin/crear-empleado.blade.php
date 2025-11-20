<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Nuevo Empleado - Hotel</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/a2d04a4f5d.js" crossorigin="anonymous"></script>

  <!-- Si usas Vite y un archivo CSS externo, mantén la directiva -->
  @vite(['resources/css/estilo2.css'])
</head>
<body>
  <div class="dashboard-container">

    <!-- MAIN -->
    <main class="main-content" id="main">
      <article class="card-form" role="article" aria-labelledby="title-form">
        <header class="card-header-gradient">
          <h2 id="title-form"><i class="fa-solid fa-user-plus me-2"></i> Registro de Nuevo Empleado</h2>
        </header>

        <div class="card-body">
          <!-- Mensajes blade (errores/éxito) -->
          @if ($errors->any())
            <div class="alert alert-danger" role="alert">
              <strong>❌ Errores encontrados:</strong>
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          @if (session('success'))
            <div class="alert alert-success" role="alert">
              <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
          @endif

          <!-- FORM -->
          <form id="formEmpleado" method="POST" action="{{ route('admin.empleados.store') }}" novalidate>
            @csrf

            <section aria-labelledby="personal-title" class="mb-4">
              <h5 id="personal-title" class="section-title">Información Personal</h5>

              <div class="row g-3">
                <div class="col-12">
                  <label for="name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                         placeholder="Ej. Juan Pérez García" required oninput="validateField(this)">
                  <div class="invalid-feedback" id="name-error">❌ Por favor ingresa un nombre válido (mínimo 2 caracteres).</div>
                  <div class="valid-feedback" id="name-success">✅ Nombre válido</div>
                </div>

                <div class="col-12">
                  <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                  <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                         placeholder="empleado@hotel.com" required oninput="validateEmail(this)">
                  <div class="invalid-feedback" id="email-error">❌ Por favor ingresa un correo válido.</div>
                  <div class="valid-feedback" id="email-success">✅ Correo válido</div>
                </div>

                <div class="col-md-6">
                  <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                  <input type="password" class="form-control" id="password" name="password"
                         placeholder="Mínimo 8 caracteres" required oninput="validatePassword(this)">
                  <div class="invalid-feedback" id="password-error">❌ La contraseña debe tener al menos 8 caracteres.</div>
                  <div class="valid-feedback" id="password-success">✅ Contraseña válida</div>
                </div>

                <div class="col-md-6">
                  <label for="password_confirmation" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                  <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                         placeholder="Repetir contraseña" required oninput="validatePasswordConfirmation(this)">
                  <div class="invalid-feedback" id="password_confirmation-error">❌ Las contraseñas no coinciden.</div>
                  <div class="valid-feedback" id="password_confirmation-success">✅ Contraseñas coinciden</div>
                </div>

                <div class="col-md-6">
                  <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                  <input type="tel" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}"
                         placeholder="Ej. 555-123-4567" required oninput="validatePhone(this)">
                  <div class="invalid-feedback" id="telefono-error">❌ Por favor ingresa un número válido.</div>
                  <div class="valid-feedback" id="telefono-success">✅ Teléfono válido</div>
                </div>

                <div class="col-md-6">
                  <label for="direccion" class="form-label">Dirección</label>
                  <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}"
                         placeholder="Ej. Calle Principal #123, Ciudad" oninput="validateOptionalField(this)">
                  <div class="valid-feedback" id="direccion-success">✅ Dirección válida</div>
                </div>

                <div class="col-md-6">
                  <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                         value="{{ old('fecha_nacimiento') }}" required onchange="validateBirthDate(this)">
                  <div class="invalid-feedback" id="fecha_nacimiento-error">❌ El empleado debe ser mayor de 18 años.</div>
                  <div class="valid-feedback" id="fecha_nacimiento-success">✅ Edad válida</div>
                  <small class="form-text text-muted">El empleado debe ser mayor de 18 años</small>
                </div>
              </div>
            </section>

            <hr />

            <section aria-labelledby="laboral-title" class="mb-3">
              <h5 id="laboral-title" class="section-title"> Información Laboral</h5>

              <input type="hidden" name="numero_empleado" id="numero_empleado" value="">

              <div class="row g-3">
                <div class="col-md-6">
                  <label for="puesto" class="form-label">Puesto <span class="text-danger">*</span></label>
                  <select class="form-select" id="puesto" name="puesto" required onchange="validateSelect(this)">
                    <option value="">Seleccionar puesto</option>
                    <option value="recepcionista" {{ old('puesto') == 'recepcionista' ? 'selected' : '' }}>Recepcionista</option>
                    <option value="limpieza" {{ old('puesto') == 'limpieza' ? 'selected' : '' }}>Limpieza</option>
                    <option value="administrador" {{ old('puesto') == 'administrador' ? 'selected' : '' }}>Administrador</option>
                    <option value="gerente" {{ old('puesto') == 'gerente' ? 'selected' : '' }}>Gerente</option>
                  </select>
                  <div class="invalid-feedback" id="puesto-error">❌ Por favor selecciona un puesto.</div>
                  <div class="valid-feedback" id="puesto-success">✅ Puesto seleccionado</div>
                </div>

                <div class="col-md-6">
                  <label for="fecha_contratacion" class="form-label">Fecha de Contratación</label>
                  <input type="date" class="form-control" id="fecha_contratacion" name="fecha_contratacion"
                         value="{{ date('Y-m-d') }}" readonly onchange="validateHireDate(this)">
                  <div class="valid-feedback" id="fecha_contratacion-success">✅ Fecha: Hoy</div>
                  <small class="form-text text-muted">Fecha actual (no editable)</small>
                </div>

                <div class="col-md-6">
                  <label for="salario" class="form-label">Salario ($) <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" id="salario" name="salario" value="{{ old('salario') }}"
                         step="0.01" min="0" placeholder="0.00" required oninput="validateSalary(this)">
                  <div class="invalid-feedback" id="salario-error">❌ El salario debe ser mayor a 0.</div>
                  <div class="valid-feedback" id="salario-success">✅ Salario válido</div>
                </div>

                <div class="col-md-6">
                  <label for="turno" class="form-label">Turno <span class="text-danger">*</span></label>
                  <select class="form-select" id="turno" name="turno" required onchange="validateSelect(this)">
                    <option value="">Seleccionar turno</option>
                    <option value="matutino" {{ old('turno') == 'matutino' ? 'selected' : '' }}>Matutino</option>
                    <option value="vespertino" {{ old('turno') == 'vespertino' ? 'selected' : '' }}>Vespertino</option>
                    <option value="nocturno" {{ old('turno') == 'nocturno' ? 'selected' : '' }}>Nocturno</option>
                    <option value="mixto" {{ old('turno') == 'mixto' ? 'selected' : '' }}>Mixto</option>
                  </select>
                  <div class="invalid-feedback" id="turno-error">❌ Por favor selecciona un turno.</div>
                  <div class="valid-feedback" id="turno-success">✅ Turno seleccionado</div>
                </div>

                <div class="col-12">
                  <label for="estado" class="form-label">Estado Inicial <span class="text-danger">*</span></label>
                  <select class="form-select" id="estado" name="estado" required onchange="validateSelect(this)">
                    <option value="activo" selected>Activo</option>
                    <option value="inactivo">Inactivo</option>
                    <option value="vacaciones">Vacaciones</option>
                    <option value="licencia">Licencia</option>
                  </select>
                  <small class="form-text text-muted">Por defecto, los nuevos empleados se crean como "Activos"</small>
                  <div class="valid-feedback" id="estado-success">✅ Estado seleccionado</div>
                </div>

                <div class="col-12">
                  <label for="observaciones" class="form-label">Observaciones</label>
                  <textarea class="form-control" id="observaciones" name="observaciones" rows="3"
                            placeholder="Notas adicionales sobre el empleado">{{ old('observaciones') }}</textarea>
                </div>
              </div>
            </section>

            <div class="d-flex justify-content-center gap-3 mt-4">
              <button type="submit" class="btn btn-primary" id="submitBtn">
                <i class="fas fa-user-plus me-2"></i> Registrar Empleado
              </button>
              <a href="{{ route('admin.usuarios') }}" class="btn btn-outline-muted">
                <i class="fas fa-arrow-left me-1"></i> Volver al Listado
              </a>
            </div>

          </form>
        </div>
      </article>
    </main>
  </div>

  <!-- ===== JS (validación cliente mejorada) ===== -->
  <script>
    /* ---------- Helper: mostrar/ocultar feedback ---------- */
    function setValid(field, valid) {
      if (!field) return;
      if (valid) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        const err = document.getElementById(field.id + '-error'); if (err) err.style.display = 'none';
        const ok = document.getElementById(field.id + '-success'); if (ok) ok.style.display = 'block';
      } else {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
        const err = document.getElementById(field.id + '-error'); if (err) err.style.display = 'block';
        const ok = document.getElementById(field.id + '-success'); if (ok) ok.style.display = 'none';
      }
    }

    // Validadores
    function validateField(field) {
      const ok = field.value.trim().length >= 2;
      setValid(field, ok);
      return ok;
    }

    function validateEmail(field) {
      const val = field.value.trim();
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      const ok = re.test(val);
      setValid(field, ok);
      return ok;
    }

    function validatePassword(field) {
      const ok = field.value.length >= 8;
      setValid(field, ok);
      // si confirm ya tiene valor, checkear coincidencia
      const conf = document.getElementById('password_confirmation');
      if (conf && conf.value) validatePasswordConfirmation(conf);
      return ok;
    }

    function validatePasswordConfirmation(field) {
      const pass = document.getElementById('password').value;
      const ok = field.value === pass && field.value.length >= 8;
      setValid(field, ok);
      return ok;
    }

    function validatePhone(field) {
      const val = field.value.trim();
      const digits = val.replace(/\D/g,'');
      const ok = digits.length >= 10;
      setValid(field, ok);
      return ok;
    }

    function validateOptionalField(field) {
      const val = field.value.trim();
      if (!val) {
        // no obligatorio -> neutral
        field.classList.remove('is-valid','is-invalid');
        const ok = document.getElementById(field.id + '-success'); if (ok) ok.style.display = 'none';
        const err = document.getElementById(field.id + '-error'); if (err) err.style.display = 'none';
        return true;
      }
      const ok = val.length >= 2;
      setValid(field, ok);
      return ok;
    }

    function validateBirthDate(field) {
      if (!field.value) { setValid(field,false); return false; }
      const sel = new Date(field.value);
      const today = new Date();
      const eighteen = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
      const ok = sel <= eighteen;
      setValid(field, ok);
      return ok;
    }

    function validateSalary(field) {
      const n = parseFloat(field.value);
      const ok = !isNaN(n) && n > 0;
      setValid(field, ok);
      return ok;
    }

    function validateSelect(field) {
      const ok = field.value !== '';
      setValid(field, ok);
      return ok;
    }

    // Validar todos antes de submit
    function validateAllFields() {
      const checks = [
        {id:'name', fn:validateField},
        {id:'email', fn:validateEmail},
        {id:'password', fn:validatePassword},
        {id:'password_confirmation', fn:validatePasswordConfirmation},
        {id:'telefono', fn:validatePhone},
        {id:'fecha_nacimiento', fn:validateBirthDate},
        {id:'puesto', fn:validateSelect},
        {id:'salario', fn:validateSalary},
        {id:'turno', fn:validateSelect},
        {id:'estado', fn:validateSelect}
      ];
      let all = true;
      checks.forEach(c => {
        const el = document.getElementById(c.id);
        if (el) {
          const ok = c.fn(el);
          if (!ok) all = false;
        }
      });
      return all;
    }

    // Mostrar alerta temporal
    function showAlert(message, type='danger') {
      const div = document.createElement('div');
      div.className = `alert alert-${type} alert-dismissible fade show`;
      div.innerHTML = `${message} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>`;
      const card = document.querySelector('.card-form .card-body');
      card.insertBefore(div, card.firstChild);
      setTimeout(()=> div.remove(), 5000);
    }

    // DOM ready
    document.addEventListener('DOMContentLoaded', () => {
      // generar número de empleado y mostrar ayuda
      const rand = Math.random().toString(36).substring(2,8).toUpperCase();
      const empNum = 'EMP' + rand;
      const empField = document.getElementById('numero_empleado');
      if (empField) empField.value = empNum;

      const estadoField = document.getElementById('estado');
      if (estadoField) {
        const helper = document.createElement('small');
        helper.className = 'form-text text-muted mt-1';
        helper.innerHTML = `🔢 Número de empleado que se generará: <strong>${empNum}</strong>`;
        estadoField.parentNode.appendChild(helper);
      }

      // Configurar fecha máxima nacimiento (>=18)
      const fechaNacimiento = document.getElementById('fecha_nacimiento');
      if (fechaNacimiento) {
        const today = new Date();
        const max = new Date(today.getFullYear()-18, today.getMonth(), today.getDate());
        fechaNacimiento.max = max.toISOString().split('T')[0];
      }

      // marcar fecha contratación como válida
      const fechaContratacion = document.getElementById('fecha_contratacion');
      if (fechaContratacion) {
        fechaContratacion.classList.add('is-valid');
        const ok = document.getElementById('fecha_contratacion-success');
        if (ok) ok.style.display = 'block';
      }

      // Validar inputs a medida que escribes
      ['name','email','password','password_confirmation','telefono','direccion','salario','fecha_nacimiento'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('input', ()=> {
          switch(id){
            case 'name': validateField(el); break;
            case 'email': validateEmail(el); break;
            case 'password': validatePassword(el); break;
            case 'password_confirmation': validatePasswordConfirmation(el); break;
            case 'telefono': validatePhone(el); break;
            case 'direccion': validateOptionalField(el); break;
            case 'salario': validateSalary(el); break;
          }
        });
      });

      // selects change
      ['puesto','turno','estado'].forEach(id => {
        const s = document.getElementById(id);
        if (s) s.addEventListener('change', ()=> validateSelect(s));
      });

      // submit handler
      const form = document.getElementById('formEmpleado');
      form.addEventListener('submit', function(e){
        if (!validateAllFields()) {
          e.preventDefault();
          showAlert('❌ Por favor corrige los errores en el formulario antes de enviar.', 'danger');
          const firstErr = document.querySelector('.is-invalid');
          if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
          // opcional: deshabilitar botón para evitar envíos múltiples
          const btn = document.getElementById('submitBtn');
          if (btn) { btn.disabled = true; btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-2"></i> Enviando...`; }
        }
      });
    });
  </script>

  <!-- Bootstrap bundle (opcional para toasts/alerts) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
