<?php require 'Components/Header.php';?>

<?php if (isset($_SESSION['TasaBCV'])) { ?>
	<a href="PlantillaPDF/Nomina-general.php" class="btn btn-danger" target="_blank"> Nomina General  
		<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
  			<path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
		</svg>
	</a> 
<?php } ?>

</header>

    <main>
        <div class="empleados">

            <div class="block Name">
                <h2> Empleados </h2>
            </div>
            
            <form class="needs-validation block form-1" id="FormEmpleado" method="POST" novalidate>
                <h4> Registrar empleado</h4>
                <div class="empleados__content" style="display: flex; gap: 1rem;">
                    <div>
                        <label for="validationCustom03" class="form-label">Cédula</label>
                        <input type="text" name="cedula" class="form-control" id="validationCustom03" required pattern="\d{8}" maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>
                    
                    <div>
                        <label for="validationCustom01" class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" 
                        id="validationCustom01" maxlength="30" required 
                        oninput="this.value = this.value.replace(/^[0-9]/, '').replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '').toLowerCase(); this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)">
                    </div>

                    <div>
                        <label for="validationCustom02" class="form-label">Apellido</label>
                        <input type="text" name="apellido" class="form-control" 
                        id="validationCustom02" maxlength="30" required 
                        oninput="this.value = this.value.replace(/^[0-9]/, '').replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '').toLowerCase(); this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)">
                    </div>
                    

                </div>
                
                <div class="empleados__content" style="display: flex; gap: 1rem;">
                    <div class="a1">
                    <label for="validationCustom04" class="form-label">Teléfono principal</label>
                    <input type="text" name="tlf" class="form-control" id="validationCustom04" required 
                    placeholder="0424-1234567" 
                    oninput="formatPhoneNumber(this)">
                    <div class="invalid-feedback">
                        por favor coloque un número de telefono.
                    </div>
                </div>
                
                <div class="a1">
                    <label for="validationCustom05" class="form-label">Teléfono adicional</label>
                    <input type="text" name="second_tlf" class="form-control" id="validationCustom05" 
                    placeholder="0424-1234567" 
                    oninput="formatPhoneNumber(this)">
                </div>
            </div>
            
            <div class="empleados__content">
                <label for="validationCustom6" class="form-label">Dirección residencial</label>
                <div class="input-group has-validation">
                    <input type="text" name="direccion" class="form-control" 
                    id="validationCustom6" aria-describedby="inputGroupPrepend"
                    maxlength="200" 
                    oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '').charAt(0).toUpperCase() + this.value.slice(1).toLowerCase()"
                    required>
                    <div class="invalid-feedback">
                        Por favor coloque una dirección.
                    </div>
                </div>
            </div>
            
            <div class="empleados__content" style="display: flex; gap: 1rem;">
                <div class="a1">
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
                
                <div class="a2">
                    <label for="validationCustom13" class="form-label">Fecha de nacimiento</label>
                    <input type="date" name="edad" class="form-control" id="validationCustom13" required >
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
            

            <h4> Datos laborales</h4>
            
            <div class="empleados__content" style="display: flex; gap: 1rem;">
                <div class="a1">    
                    <label for="validationCustom09" class="form-label">Departamento</label>
                    <select class="form-select" name="departamento" id="validationCustom09" required>
                        <option selected disabled value="">No asignado...</option>
                        <option value="Gerencia" >Gerencia</option>
                        <option value="Administración"> Administración</option>
                        <option value="Contabilidad" >Contabilidad</option>
                        <option value="Almacén" >Almacén</option>
                        <option value="Ventas" >Ventas</option>
                        <option value="Operador" >Operador</option>
                    </select>
                    <div class="invalid-feedback">
                        Asigne un departamento.
                    </div>
                </div>
                
                <div class="a1">
                    <label for="validationCustom10" class="form-label">Cargo</label>
                    <select class="form-select" name="cargo" id="validationCustom10" required>
                        <option selected disabled value="">No asignado...</option>
                        <option value="Gerente" >Gerente</option>
                        <option value="Sub gerente" >Sub gerente</option>
                        <option value="Contador"> Contador</option>
                        <option value="Aux Contable" >Aux Contable</option>
                        <option value="Almacenista" >Almacenista</option>
                        <option value="Facturador" >Facturador</option>
                        <option value="Cobranza" >Cobranza</option>
                        <option value="Vendedor" >Vendedor</option>

                    </select>
                    <div class="invalid-feedback">
                        Asigne un cargo.
                    </div>
                </div>
                
                <div class="a1">
                    <label for="validationCustom11" class="form-label">Fecha de ingreso</label>
                    <input type="date" name="ingreso" class="form-control" id="validationCustom11" required>
                    <div class="invalid-feedback">
                        Coloque la fecha de ingreso.
                    </div>
                </div>
            </div>
            
            <div class="empleados__content">
                <label for="validationCustom12" class="form-label">Sueldo</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">$</span>
                        <input type="text" name="sueldo" class="form-control" id="validationCustom12" aria-describedby="inputGroupPrepend" 
                        oninput="formatInput(this)" 
                        placeholder="00.00" 
                        maxlength="7"
                        required >
                        <div class="invalid-feedback">
                            Coloque el sueldo del trabajador (130$ - 2000$).
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <button class="btn btn-outline-primary" type="submit">Registrar</button>
                    <button class="btn btn-outline-danger" type="reset">Cancelar</button>
                </div>
                
                <script src="../JS/validation-empleado.js"></script>
                <script src="../JS/phonenumbervalidate.js"></script>
                <script src="../JS/Validate-decimalnumber.js"></script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="../JS/Submit-empleado.js"></script>

                <div id="alerts"></div>
            </form>
        
            <div class="block indicator">
                <?php include_once 'Components/Indicator/Fechas_Ingreso.php';?>
                <?php include_once 'Components/Indicator/Edad_Promedio.php';?>
                <?php include_once 'Components/Indicator/Genero.php';?>
            </div>
            
            <div class="block info">
                <?php include_once 'Components/Tables/Tablas-empleados.php';?>
            </div>
            
        </div>
        </main>
        
       

        <script>
// Obtener los selects de departamento y cargo
const departamentoSelect = document.getElementById('validationCustom09');
const cargoSelect = document.getElementById('validationCustom10');

// Crear un objeto que mapea los departamentos con sus respectivos cargos
const departamentosCargos = {
  'Gerencia': ['Gerente'],
  'Administración': ['Sub gerente'],
  'Contabilidad': ['Contador', 'Aux Contable'],
  'Almacén': ['Almacenista'],
  'Ventas': ['Facturador', 'Cobranza'],
  'Operador': ['Vendedor']
};

// Función de filtrado automático
function filtrarCargos() {
  const departamentoSeleccionado = departamentoSelect.value;
  const cargos = departamentosCargos[departamentoSeleccionado];

  // Limpiar las opciones del select de cargo
  cargoSelect.innerHTML = '<option selected disabled value="">No asignado...</option>';

  // Agregar las opciones de cargo correspondientes al departamento seleccionado
  if (cargos) {
    cargos.forEach(cargo => {
      const option = document.createElement('option');
      option.value = cargo;
      option.textContent = cargo;
      cargoSelect.appendChild(option);
    });
  }
}

// Agregar el evento de cambio al select de departamento
departamentoSelect.addEventListener('change', filtrarCargos);




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