<?php require 'Components/Header.php';?>
    <a onclick="Modal()" class="btn btn-danger"> Usuarios inabilitados  
    <svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  
    stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-off">
    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
    <path d="M8.18 8.189a4.01 4.01 0 0 0 2.616 2.627m3.507 -.545a4 4 0 1 0 -5.59 -5.552" />
    <path d="M6 21v-2a4 4 0 0 1 4 -4h4c.412 0 .81 .062 1.183 .178m2.633 2.618c.12 .38 .184 .785 .184 1.204v2" />
    <path d="M3 3l18 18" /></svg>
	</a> 

<?php include_once 'Components/Modals/Modal-UserInvalid.php';?>
</header>

    <main>
    <div id="alerts"></div>
        <form id="form" method="post" class="empleados needs-validation" novalidate>
            <div class="block Name">
                <h2> Gestión de Usuarios </h2>
        </div>

        <div class="block form-1">
            <h4> Registrar usuarios</h4>
            <div class="empleados__content" style="display: flex; gap: 1rem;">
                <div>
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required 
                    oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" readonly>
                </div>

                <div>
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" required 
                    oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" readonly>
                </div>

                <div>
                    <label for="cedula" class="form-label">Cédula</label>
                    <input type="text" class="form-control" id="cedula" 
                    name="cedula" 
                    required 
                    pattern="\d{8}" maxlength="8" 
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                    onkeyup="buscarUsuario(this.value)">
                    <input type="hidden" name="cedula1" id="cedula1">
                </div>
            </div>
           
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
                    <select class="form-select" id="validationCustom04" name="tipo" required>
                        <option selected disabled value="">Choose...</option>
                        <option>Gerencia</option>
                        <option>Administrador</option>
                        <option>Trabajador</option>
                    </select>
                    <div class="invalid-feedback">
                        Please select a valid state.
                    </div>
                </div>
            </div>
        
            <div class="col-12">
                <input type="hidden" name="op" id="op" value="9">
                <button class="btn btn-outline-primary" type="button" onclick="Guardar()">Crear usuario</button>
                <button class="btn btn-outline-danger" type="reset">Cancelar</button>
            </div>

        <script src="../JS/validation-empleado.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function Modal() {
        $('#Modal').modal('show');
    }
        function openModal() {
        $('#empleadoModal').modal('show');
    }
    
    function buscarUsuario(cedula) {
        if (cedula.length >= 7 && cedula.length <= 8) { // Asegúrate de que la cédula tenga 8 dígitos
            $.ajax({
                url: '../PHP/CTR/User_CTR.php', // Cambia esto por la ruta a tu script PHP
                type: 'POST',
                data: { cedula: cedula },
                success: function(response) {
                    // Suponiendo que la respuesta es un objeto JSON con nombre y apellido
                    const datos = JSON.parse(response);
                    if (datos) {
                        $('#nombre').val(datos.nombre);
                        $('#apellido').val(datos.apellido);
                        $('#cedula1').val(datos.cedula);
                    } else {
                        // Si no se encuentra el usuario, puedes limpiar los campos o mostrar un mensaje
                        $('#nombre').val('');
                        $('#apellido').val('');
                        $('#cedula1').val('');
                    }
                },
                error: function() {
                    alert('Error en la búsqueda del usuario. Intente nuevamente.');
                }
            });
        } else {
            // Limpiar los campos si la cédula no tiene 8 dígitos
            $('#nombre').val('');
            $('#apellido').val('');
        }
    }

    function Guardar(){
        // Recoger todos los datos del formulario usando serialize
        const formData = $('#form').serialize();
        $.ajax({
            url: '../PHP/CTR/SaveResult_CTR.php', 
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response) {
                var data = JSON.parse(response);
                    if (data.html) {
                    $('#alerts').html(data.html);
                } else {
                    alert(data.message);
                }
                    $('#form')[0].reset(); // Limpiar el formulario
                } else {
                    alert('Error al guardar los datos. Intente nuevamente.');
                }
            },
            error: function() {
                alert('Error en la conexión al servidor. Intente nuevamente.');
            }
        });
    };
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const password = document.getElementById('pass');
        const confirmPassword = document.getElementById('passconfirm');
        const confirmFeedback = document.getElementById('confirmFeedback');

        confirmPassword.addEventListener('input', function() {
            if (confirmPassword.value !== password.value) {
                confirmPassword.classList.add('is-invalid'); // Agrega la clase de Bootstrap para mostrar el mensaje de error
                confirmFeedback.style.display = 'block'; // Muestra el mensaje de error
            } else {
                confirmPassword.classList.remove('is-invalid'); // Quita la clase de error si coinciden
                confirmFeedback.style.display = 'none'; // Oculta el mensaje de error
            }
        });
    });
    </script>
        </div>
        
        <div class="block indicator">

        </div>

        <div class="block info">
        <?php include_once 'Components/Tables/Tablas-usuarios.php';?>
        </div>

        </form>
    </main>
    