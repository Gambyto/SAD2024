<?php require 'Components/Header.php';?>


</header>

    <main>
        <div class="Perfil">

            <div class="block Name">
                <h2> Perfil de Usuario </h2>  

                <div class="buttons">
                  <button class="btn btn-outline-primary" type="submit">Actualizar</button>
                  <button class="btn btn-outline-danger" type="reset">Cancelar</button>
                </div>
        
            </div>
            
            <form class="needs-validation block form-1 perfil" id="FormEmpleado" method="POST" novalidate>
                <h4> Actualizar datos personales</h4>
                <div class="empleados__content" style="display: flex; gap: 1rem;">
                    <div>
                        <label for="validationCustom03" class="form-label">Cédula</label>
                        <input type="text" name="cedula" class="form-control" id="validationCustom03" value="<?=$_SESSION['id'];?>" 
                        pattern="\d{8}" maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '');" disabled required>
                    </div>
                    
                    <div>
                        <label for="validationCustom01" class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" 
                        id="validationCustom01" maxlength="30" required 
                        oninput="this.value = this.value.replace(/^[0-9]/, '').replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '').toLowerCase(); this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1); this.value = this.value.replace(/ (?=[a-z])/g, ' ').replace(/\b\w/g, l => l.toUpperCase())"
                    ></div>

                    <div>
                        <label for="validationCustom02" class="form-label">Apellido</label>
                        <input type="text" name="apellido" class="form-control" 
                        id="validationCustom02" maxlength="30" required 
                        oninput="this.value = this.value.replace(/^[0-9]/, '').replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '').toLowerCase(); this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1); this.value = this.value.replace(/ (?=[a-z])/g, ' ').replace(/\b\w/g, l => l.toUpperCase())">
                    </div>
                    

                </div>
                
                <div class="empleados__content" style="display: flex; gap: 1rem;">
                    <div class="a1">
                    <label for="validationCustom04" class="form-label">Teléfono principal</label>
                    <input type="text" name="tlf" class="form-control" id="validationCustom04" required 
                    placeholder="xxxx-xxxxxxx" 
                    oninput="formatPhoneNumber(this)">
                    <div class="invalid-feedback">
                        por favor coloque un número de telefono.
                    </div>
                </div>
                
                <div class="a1">
                    <label for="validationCustom05" class="form-label">Teléfono adicional</label>
                    <input type="text" name="second_tlf" class="form-control" id="validationCustom05" 
                    placeholder="xxxx-xxxxxxx" 
                    oninput="formatPhoneNumber(this)">
                </div>
            </div>
            
            <div class="empleados__content">
                <label for="validationCustom6" class="form-label">Dirección residencial</label>
                <div class="input-group has-validation">
                    <input type="text" name="direccion" class="form-control" 
                    id="validationCustom6" aria-describedby="inputGroupPrepend"
                    maxlength="200" 
                    oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '').charAt(0).toUpperCase() + this.value.slice(1).toLowerCase(); this.value = this.value.replace(/ (?=[a-z])/g, ' ').replace(/\b\w/g, l => l.toUpperCase())"
                    required>
                    <div class="invalid-feedback">
                        Por favor coloque una dirección.
                    </div>
                </div>
            </div>

            <div class="empleados__content"> 
                 <label for="validationCustom7" class="form-label">Correo</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="email" name="correo" class="form-control" id="validationCustom7" aria-describedby="inputGroupPrepend" required 
                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                        oninput="validateEmail(this)"
                        placeholder="ejem@ejem.com">
                        <div class="invalid-feedback">
                            Coloque una dirección de correo.
                        </div>
                    </div>
            </div>

            
            <div class="empleados__content" style="display: flex; gap: .5rem;">
                <div class="a1">
                    <label for="validationCustom13" class="form-label">Fecha de nacimiento</label>
                    <input type="date" name="edad" class="form-control" id="validationCustom13" required >
                </div>

                <div class="sex_selector">
                    <label for="validationCustom8" class="form-label g1">Sexo</label>
                    <div class="form-check">
                        <input type="radio" name="sexo" value="H" class="form-check-input g2" id="validationFormCheck2" name="radio-stacked" required>
                        <label class="form-check-label" for="validationFormCheck2"> Hombre </label>
                    </div>
                    <div class="form-check mb-3 g3">
                        <input type="radio" name="sexo" value="M" class="form-check-input" id="validationFormCheck3" name="radio-stacked" required>
                        <label class="form-check-label" for="validationFormCheck3"> Mujer </label>
                    </div>
                </div>
            </div>
            
            <h4> Datos especiales</h4>
            
            <div class="empleados__content" style="display: flex; gap: 1rem;">
            <div class="sex_selector">
                <label for="validationCustom15" class="form-label g1">Discapacidad</label>
                <div class="form-check">
                <input type="radio" name="Select-discapacidad" value="Y" class="form-check-input g2" id="validationFormCheck4" name="radio-stacked" required>
                <label class="form-check-label" for="validationFormCheck4"> Sí </label>
                </div>
                <div class="form-check mb-3 g3">
                <input type="radio" name="Select-discapacidad" value="N" class="form-check-input" id="validationFormCheck5" name="radio-stacked" required checked>
                <label class="form-check-label" for="validationFormCheck5"> No </label>
                </div>
            </div>

            <div class="a1" id="discapacidad-selectores">
                <label for="validationCustom16" class="form-label">Tipo de discapacidad</label>
                <select class="form-select" name="tipo-discapacidad" id="validationCustom16" required>
                <option value="Ninguna" selected>Ninguna</option>
                </select>
                <div class="invalid-feedback">
                Seleccione un tipo de discapacidad.
                </div>
            </div>

            <div class="a1" id="afeccion-selectores">
                <label for="validationCustom17" class="form-label">Afección</label>
                <div id="afeccion-select">
                    <select class="form-select" name="afeccion" id="validationCustom17" required>
                    <option value="No aplica" selected>No aplica</option>
                    </select>
                </div>
                <div class="invalid-feedback">
                    Seleccione una afección.
                </div>
                </div>
            </div>
                 
               

                <div id="alerts"></div>
            </form>
            
            <div class="block form-1 form-3">
                <h4> Datos de Usuario</h4>
                <div class="empleados__content">
                <label for="validationCustomUsername" class="form-label">Nombre de Usuario</label>
                <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                    <input type="text" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" 
                    name="username" id="username" required>
                    <div class="invalid-feedback">
                        Por favor coloque un usuario valido.
                    </div>
                </div> 
            </div>

            <div class="empleados__content" style="display: flex; gap: 1rem;">
                <div class="a1">
                    <label for="pass" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="pass" id="pass" required>
                    <div class="invalid-feedback">
                        por favor coloque una contraseña.
                    </div>
                </div>

                <div class="a1">
                    <label for="passconfirm" class="form-label">Confirmar contraseña</label>
                    <input type="password" class="form-control" id="passconfirm" name="passconfirm" required>
                    <div class="invalid-feedback" id="confirmFeedback">
                        Las contraseñas no coinciden.
                    </div>
                </div>
            </div>


            <div class="empleados__content">
                <div class="a2">    
                    <label for="validationCustom04" class="form-label">Tipo de Ususario</label>
                    <select class="form-select" id="validationCustom04" name="tipo" required disabled>
                        <option selected disabled value="">Choose...</option>
                        <option value="Gerencia">Gerencia</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Trabajador" selected>Trabajador</option>
                    </select>
                    <div class="invalid-feedback">
                        Please select a valid state.
                    </div>
                </div>
            </div>
            </div>

            <div class="block indicator">
               
            </div>
            
            
        </div>
        </main>
        
        <script src="../JS/Rellenar_campos_de_Empleados.js"></script>
        <script src="../JS/Validate_Empleado.js"></script>
        <script src="../JS/validation-empleado.js"></script>
        <script src="../JS/phonenumbervalidate.js"></script>
        <script src="../JS/Validate-decimalnumber.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="../JS/Submit-empleado.js"></script>

        <script>            
// Función para buscar empleado por cédula
const cedula = '<?php echo $_SESSION['id']; ?>';
buscarEmpleado(cedula);


const radioSi = document.getElementById('validationFormCheck4');
const radioNo = document.getElementById('validationFormCheck5');
const discapacidadSelectores = document.getElementById('discapacidad-selectores');
const afeccionSelectores = document.getElementById('afeccion-selectores');
const tipoDiscapacidadSelect = document.getElementById('validationCustom16');
const afeccionSelect = document.getElementById('validationCustom17');

radioSi.addEventListener('change', () => {
  if (radioSi.checked) {
    tipoDiscapacidadSelect.innerHTML = '';
    tipoDiscapacidadSelect.innerHTML += '<option value="Ninguna">Ninguna</option>';
    tipoDiscapacidadSelect.innerHTML += '<option value="Física">Física</option>';
    tipoDiscapacidadSelect.innerHTML += '<option value="Mental">Mental</option>';
    tipoDiscapacidadSelect.innerHTML += '<option value="Sensorial">Sensorial</option>';
    tipoDiscapacidadSelect.innerHTML += '<option value="Salud mental">Salud mental</option>';
    tipoDiscapacidadSelect.innerHTML += '<option value="Otro">Otro</option>';

    afeccionSelect.innerHTML = '';
    afeccionSelect.innerHTML += '<option value="Ninguna">Ninguna</option>';
  }
});

radioNo.addEventListener('change', () => {
  if (radioNo.checked) {
    tipoDiscapacidadSelect.innerHTML = '';
    tipoDiscapacidadSelect.innerHTML += '<option value="Ninguna" selected>Ninguna</option>';

    afeccionSelect.innerHTML = '';
    afeccionSelect.innerHTML += '<option value="Ninguna" selected>Ninguna</option>';
  }
});

tipoDiscapacidadSelect.addEventListener('change', () => {
  const tipoDiscapacidad = tipoDiscapacidadSelect.value;
  const opciones = {
    'Física': [
      'Parálisis Cerebral',
      'Esclerosis Múltiple',
      'Distrofia Muscular',
      'Lesión de la Médula Espinal',
      'Amputación',
      'Síndrome de Fatiga Crónica',
      'Enfermedad de Células Falciformes',
      'Fibrosis Quística',
      'Accidente Cerebrovascular',
      'Síndrome del Túnel Carpiano',
      'Trastornos de Dolor Crónico',
      'Parálisis de Bell',
      'Paraplejía Espástica Hereditaria',
      'Espina Bífida'
    ],
    'Mental': [
      'Autismo',
      'Síndrome de Down',
      'Discapacidad Intelectual',
      'Retraso Mental',
      'Síndrome de X Frágil',
      'Síndrome de Klinefelter',
      'Síndrome de Turner',
      'Síndrome de Williams',
      'Síndrome de Prader-Willi',
      'Trastorno de Aprendizaje No Verbal',
      'Dislexia',
      'Discalculia'
    ],
    'Sensorial': [
      'Ceguera',
      'Discapacidad Visual',
      'Sordera',
      'Discapacidad Auditiva',
      'Trastornos del Procesamiento Auditivo'
    ],
    'Salud mental': [
      'Depresión',
      'Ansiedad',
      'Trastorno Bipolar',
      'Esquizofrenia',
      'Trastorno Obsesivo-Compulsivo'
    ],
    'Otro': [
      'Especificar'
    ]
  };

  if (tipoDiscapacidad === 'Otro') {
    document.getElementById('afeccion-select').innerHTML = '';
    document.getElementById('afeccion-select').innerHTML += '<input type="text" name="afeccion" class="form-control" id="validationCustom17" required>';
  } else {
    document.getElementById('afeccion-select').innerHTML = '<select class="form-select" name="afeccion" id="validationCustom17" required><option value="No aplica" selected>No aplica</option></select>';
    if (opciones[tipoDiscapacidad]) {
      opciones[tipoDiscapacidad].forEach(afeccion => {
        const option = document.createElement('option');
        option.value = afeccion;
        option.textContent = afeccion;
        document.getElementById('validationCustom17').appendChild(option);
      });
    }
  }
});
        </script>