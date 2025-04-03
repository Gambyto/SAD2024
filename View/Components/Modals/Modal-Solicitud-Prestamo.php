<!-- Modal para actualizar empleado -->
<div class="modal fade" id="solicitudes" tabindex="-1" aria-labelledby="empleadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="empleadoModalLabel">Solicitar prestamo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="FormEmpleadoModal" class="needs-validation" style="gap: 1rem; display: flex;
            flex-direction: column;">

            <div class="empleados__content" style="display: flex; gap: 1rem;">
                <div>
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                </div>

                <div>
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" required oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                </div>

                <div>
                    <label for="cedula" class="form-label">Cédula</label>
                    <input type="text" class="form-control" id="cedula" 
                    name="cedula" value="<?=$_SESSION['id']?>"
                    required 
                    pattern="\d{8}" maxlength="8" 
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                </div>
            </div>
           
            <div class="empleados__content" style="display: flex; gap: 1rem;">
            <div class="a1">
                <label for="monto" class="form-label">Monto</label>
                <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend">$</span>
                    <input type="text" class="form-control" id="monto" 
                    name="monto" maxlength="7"
                    oninput="formatInput(this); updateCuotas(this.value)"
                    required>
                    <div class="invalid-feedback">
                        Monto requerido.
                    </div>
                </div>
            </div>

            <div class="a1">
                <label for="cuotas" class="form-label">Cuotas semanales</label>
                <select class="form-control" id="cuotas" name="cuotas"
                onchange="updateCampos()" 
                required>
                    <option value="">Seleccione una opción</option>
                </select>
            </div>
            </div>

            <div class="empleados__content">
                <div class="a2">
                    <label for="descuento" class="form-label">Monto a descontar por cuota</label>
                    <div class="input-group has-validation"> 
                        <span class="input-group-text" id="inputGroupPrepend">$</span>
                        <input type="text" class="form-control" name="descuento" id="descuento" readonly>
                    </div>
                </div>
            </div>

            <div class="empleados__content" style="display: flex; gap: 1rem;">
                <div class="a2">	
                    <label for="fechasolicitud" class="form-label">Fecha de solicitud</label>
                    <div class="input-group has-validation">
                        <input type="date" class="form-control" id="fechasolicitud" name="fechasolicitud" 
                        aria-describedby="inputGroupPrepend" required readonly>
                    </div> 
                </div>

                <div class="a2">
                    <label for="fechalimite" class="form-label">Fecha limite del pago</label>
                    <div class="input-group has-validation">
                        <input type="date" class="form-control" id="fechalimite" name="fechalimite" 
                        aria-describedby="inputGroupPrepend"
                         required readonly 
                        onchange="dateinterval(this.value)">
                    </div> 
                </div>
            </div>


            <div class="empleados__content">
                <label for="info" class="form-label">Descripción</label>
                <input type="text" class="form-control" name="info" id="info">
                <input type="hidden" id="op" name="op" value="8">
            </div>
        
   

        <script src="../JS/validation-empleado.js"></script>
        <script src="../JS/Validate-decimalnumber.js"></script>
        <script src="../JS/Get-Empleado.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

        </form>
            
           
        </div>
        <div class="modal-footer">
                <div class="col-12">
                    <button class="btn btn-outline-primary" type="button" onclick="Guardar()">Solicitar</button>
                    <button class="btn btn-outline-danger" type="reset">Cancelar</button>
                </div>
            </div>
    </div>
</div>
        <script src="../JS/phonenumbervalidate.js"></script>
        <script src="../JS/Validate-decimalnumber.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <script>
        buscarUsuario();
        function buscarUsuario() {
            const cedula = '<?php echo $_SESSION['id']; ?>';
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
                        $('#cedula1').val(datos.cedula);
                        $('#apellido').val(datos.apellido);
                    } else {
                        // Si no se encuentra el usuario, puedes limpiar los campos o mostrar un mensaje
                        $('#nombre').val('');
                        $('#cedula1').val('');
                        $('#apellido').val('');
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

    function updateCuotas(monto) {
    const cuotasSelect = document.getElementById('cuotas');
    cuotasSelect.innerHTML = '';

    const cuotasOptions = [
        { min: 0, max: 50, options: [4] },
        { min: 50, max: 150, options: [4, 12] },
        { min: 150, max: 250, options: [4, 12, 24] },
        { min: 250, max: 1000, options: [4, 12, 24, 48] }
    ];

    const cuotasRange = cuotasOptions.find(range => monto >= range.min && monto < range.max);

    if (cuotasRange) {
        cuotasRange.options.forEach(option => {
            cuotasSelect.innerHTML += `<option value="${option}">${option}</option>`;
        });
        // Selecciona la primera opción por defecto
        cuotasSelect.value = cuotasRange.options[0];
        // Llama a la función updateCampos() para actualizar los campos
        updateCampos();
    }
}

function updateCampos() {
    const monto = document.getElementById('monto').value;
    const cuotas = document.getElementById('cuotas').value;
    const descuento = document.getElementById('descuento');
    descuento.value = (monto / cuotas).toFixed(2);

    const fechaSolicitud = document.getElementById('fechasolicitud');
    const fechaHoy = new Date();
    fechaSolicitud.value = fechaHoy.toISOString().split('T')[0];

    const fechaLimite = document.getElementById('fechalimite');
    const semanas = parseInt(cuotas);
    const fechaLimiteDate = new Date(fechaHoy.getTime() + semanas * 7 * 24 * 60 * 60 * 1000);
    fechaLimite.value = fechaLimiteDate.toISOString().split('T')[0];
}



function dateinterval(limite) {
    // No hacer nada si se selecciona un número de cuotas
    if (document.getElementById('cuotas').value !== '') {
        return;
    }

    // Código original
    const solicitud = document.getElementById('fechasolicitud').value;
    const monto = document.getElementById('monto').value;
    const cuotas = document.getElementById('cuotas').value;

    // Validar que los campos no estén vacíos
    if (!solicitud || !limite || !cuotas) {
        alert('Coloque la fecha limite de los pagos y seleccione una opción de cuotas');
        return;
    }

    $.ajax({
        url: '../PHP/CTR/Calcular_General_CTR.php',
        type: 'POST',
        data: {
            op: 5,
            limite: limite,
            solicitud: solicitud,
            cuotas: cuotas
        },
        success: function(response) {
            console.log({
                op: 5,
                limite: limite,
                solicitud: solicitud,
                cuotas: cuotas
            });
            const datos = JSON.parse(response);
            console.log(response);
            if (datos) {
                $('#descuento').val((monto / cuotas).toFixed(2)); // Calcular el descuento por cuota
            } else {
                $('#descuento').val('');
            }
        },
        error: function() {
            alert('Error en el cálculo.');
        }
    });
}

function Guardar(){
        // Recoger todos los datos del formulario usando serialize
        const formData = $('#FormEmpleadoModal').serialize();
        $.ajax({
            url: '../PHP/CTR/SaveResult_CTR.php', 
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response) {
                    
                    // Limpiar el formulario o realizar otra acción
                    $('#form')[0].reset(); // Limpiar el formulario
                    window.location.reload(); // Recargar la página
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