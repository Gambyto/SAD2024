<!-- Modal para actualizar empleado -->
<div class="modal fade" id="aporte" tabindex="-1" aria-labelledby="empleadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="empleadoModalLabel">Aportar adelanto de prestamo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="formaporte" class="needs-validation" style="gap: 1rem; display: flex;
            flex-direction: column;">

            <div class="empleados__content" style="display: flex; gap: 1rem;">
                <div>
                    <label for="cedula" class="form-label">Cédula</label>
                    <input type="text" class="form-control" id="Mcedula" 
                    name="cedula"
                    required 
                    pattern="\d{8}" maxlength="8" 
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                    onkeyup="buscarEmpleado(this.value)">
                </div>
                <div>
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="Mnombre" name="nombre" required oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                </div>

                <div>
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="Mapellido" name="apellido" required oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                </div>

            </div>
           
            <div class="empleados__content" style="display: flex; gap: 1rem;">
            <div class="a1">
                <label for="monto" class="form-label">Monto del prestamo</label>
                <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend">$</span>
                    <input type="text" class="form-control" id="Mmonto" 
                    name="monto" maxlength="7"
                    oninput="formatInput(this)"
                    required readonly>
                    <div class="invalid-feedback">
                        Monto requerido.
                    </div>
                </div>
            </div>

            <div class="a1">
                <label for="monto_desc" class="form-label">Deuda pendiente</label>
                <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend">$</span>
                    <input type="text" class="form-control" id="monto_desc" 
                    name="monto_desc" maxlength="7"
                    oninput="formatInput(this)"
                    required>
                    <div class="invalid-feedback">
                        Monto requerido.
                    </div>
                </div>
            </div>
            </div>

            <div class="empleados__content" style="display: flex; gap: 1rem;">
                <div class="a2">
                    <label for="descuento" class="form-label">Aporte</label>
                    <div class="input-group has-validation"> 
                        <span class="input-group-text" id="inputGroupPrepend">$</span>
                        <input type="text" class="form-control" 
                        name="descuento" id="Mdescuento" 
                        onkeyup="actualizarDeudaParcial()"
                        >
                    </div>
                </div>

                <div class="a2">
                    <label for="parcial" class="form-label">Deuda Parcial</label>
                    <div class="input-group has-validation"> 
                        <span class="input-group-text" id="inputGroupPrepend">$</span>
                        <input type="text" class="form-control" name="parcial" id="parcial" readonly>
                    </div>
                </div>
            </div>

            <div class="empleados__content" style="display: flex; gap: 1rem;">
                <div class="a2">	
                    <label for="tpago" class="form-label">Tipo de pago</label>
                    <select class="form-control" id="tpago" name="tpago" 
                        required>
                        <option value="Efectivo"> Efectivo</option>
                        <option value="Pago movil"> Pago movil</option>
                        <option value="Transferencia"> Transferencia</option>
                    </select>
                </div>

                <div class="a2">
                    <label for="referencia" class="form-label">Referencia</label>
                    <div class="input-group has-validation">
                        <input type="text" class="form-control" id="referencia" name="referencia" 
                        aria-describedby="inputGroupPrepend"
                         required readonly>
                    </div> 

                    <input type="hidden" id="idp" name="idp" >
                </div>
            </div>
        </form>
            
           
        </div>
        <div class="modal-footer">
                <div class="col-12">
                <button class="btn btn-outline-primary" id="aporte-btn" onclick="agg_aporte()">Aportar</button>
                    <button class="btn btn-outline-danger" id="cancel" type="reset">Cancelar</button>
                </div>
            </div>
    </div>
</div>
        <script src="../JS/Close_modal.js"></script>
        <script src="../JS/phonenumbervalidate.js"></script>
        <script src="../JS/Validate-decimalnumber.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        
        <script>

// Función para cerrar modal al presionar botones
function cerrarModal() {
  // Cerrar el modal manualmente
  $(this).closest('.modal').modal('hide');
}

// Agregar evento de clic a los botones
$('#aporte-btn, #cancel').on('click', function(event) {
  cerrarModal.call(this);
});


function agg_aporte(){
    // Recoger todos los datos del formulario usando serialize
    const formData = $('#formaporte').serialize();
    console.log(formData);
    $.ajax({
        url: '../PHP/CTR/SaveResult_CTR.php', 
        type: 'POST',
        data: formData + '&op=6',
        success: function(response) {
            console.log(response);
                if (response) {
                var data = JSON.parse(response);
                    if (data.html) {
                    $('#alerts').html(data.html);
                } else {
                    alert(data.message);
                }
                    $('#formaporte')[0].reset(); // Limpiar el formulario
                } else {
                    alert('Error al guardar los datos. Intente nuevamente.');
                }
            },
            error: function() {
                alert('Error en la conexión al servidor. Intente nuevamente.');
            }
        });
    };

$('#descuento').on('change', function() {
    actualizarDeudaParcial();
});

$('#tpago').on('change', function() {
    const seleccionado = $(this).val();
    if (seleccionado === 'Efectivo') {
        $('#referencia').val('No aplica');
        $('#referencia').prop('readonly', true);
        $('#referencia').attr('placeholder', '');
        $('#referencia').attr('maxlength', '');
    } else {
        $('#referencia').val('');
        $('#referencia').prop('readonly', false);
        $('#referencia').attr('type', 'text');
        $('#referencia').attr('placeholder', 'Los últimos 4 dígitos');
        $('#referencia').attr('maxlength', '4');
        $('#referencia').on('input', function() {
            const valor = $(this).val();
            if (!/^\d+$/.test(valor)) {
                $(this).val('');
            }
        });
    }
});

$('#referencia').on('keypress', function(e) {
    if (e.which !== 8 && e.which !== 0 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});
        function buscarEmpleado(cedula) {
        if (cedula.length >= 7 && cedula.length <= 8) { // Asegúrate de que la cédula tenga 8 dígitos
            $.ajax({
                url: '../PHP/CTR/Aporte_data_CTR.php', // Cambia esto por la ruta a tu script PHP
                type: 'POST',
                data: { cedula: cedula },
                success: function(response) {
                    // Suponiendo que la respuesta es un objeto JSON con nombre y apellido
                    const datos = JSON.parse(response);
                    if (datos) {
                        console.log(response)
                        $('#Mnombre').val(datos.nombre);
                        $('#Mapellido').val(datos.apellido);
                        $('#Mmonto').val(datos.monto);
                        $('#monto_desc').val(datos.monto_desc);
                        $('#Mdescuento').val(datos.descuento);
                        $('#idp').val(datos.id_prestamos);

                        actualizarDeudaParcial();

                        $('#alerts').html(datos.html);
                        $('#formaporte').reset(); // Limpiar mensajes de alerta si los hay
                    } else {
                        // Si no se encuentra el usuario, puedes limpiar los campos o mostrar un mensaje
                        $('#nombre').val('');
                        $('#apellido').val('');
                        $('#monto').val('');
                        $('#monto_desc').val('');
                        $('#descuento').val('');
                        $('#idp').val('');
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

    function actualizarDeudaParcial() {
    const deuda = parseFloat($('#monto_desc').val());
    const aporte = parseFloat($('#Mdescuento').val());
    const deudaParcial = Math.max(0, deuda - aporte);
    $('#parcial').val(deudaParcial.toFixed(2));
}


</script>