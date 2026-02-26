<?php require 'Components/Header.php';?>

<div style="display: flex; gap:1rem;">

    <?php if (isset($_SESSION['TasaBCV'])) { ?>
	<a  class="btn btn-danger"
        data-bs-toggle="modal"
        data-bs-target="#historicoNominaModal">
        Nómina General
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
        </svg>
    </a>   
    <?php } ?>

    <?php include_once 'Components/Modals/Modal-AutoPago.php';?>
    <?php include_once 'Components/Modals/Modal-Nomina-General.php';?>
</div>

</header>

    <main>       

    <div id="alerts"></div>

        <form id="form">
        <div class="form">
            <div class="block Name">
                <h2>Sueldos y Salarios</h2>
<?php /*
                <div class="search-box">
                    <div class="row">
                        <input type="text" class="search-bar" placeholder="Buscar trabajador... (cedula)" autocomplete="off">
                        <button>
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24" height="24"  
                            viewBox="0 0 24 24"  
                            fill="none"  
                            stroke="currentColor"  
                            stroke-width="2"  
                            stroke-linecap="round"  
                            stroke-linejoin="round"  
                            class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                            
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                            <path d="M21 21l-6 -6" />
                            
                        </svg>
                    </button>
                </div>
                <div class="results"></div>
            </div>

            <script src="../JS/Search-box.js"></script>
            */
?>
            <div class="buttons">
				<input type="button" value="Calcular" class="btn btn-outline-success" id="calcularbtn" onclick="Calcular()" name="calcular">
					
				<input type="button" value="Guardar" class="btn btn-outline-success" onclick="Guardar()" name="guardar">
					
				<input type="reset" value="Nuevo" class="btn btn-outline-danger" name="reset">
			</div>
        </div>

        <div class="block item-1">
            <h4>Datos del empleado</h4>
                <div class="empleados__content">
                    <label for="cedula"> Cédula</label>
                        <div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control"
                            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
                            id="cedula" name="cedula"
                            required maxlength="8"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                            onkeyup="buscarEmpleado(this.value)">

                        <!-- Botón que abre el modal de empleados pendientes -->
                        <button class="btn btn-outline-secondary" type="button"
                                data-bs-toggle="modal"
                                data-bs-target="#modalBuscarEmpleado"
                                title="Ver empleados pendientes de pago">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                                class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1z"/>
                                <path d="M13.5 5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                                <path fill-rule="evenodd"
                                    d="M13.5 5H10a.5.5 0 0 0 0 1h3.5a.5.5 0 0 0 0-1zm0 2H10a.5.5 0 0 0 0 1h3.5a.5.5 0 0 0 0-1z"/>
                            </svg>
                        </button>

                        <input type="hidden" id="cedula1" name="cedula1">
                    </div>

                    <?php include_once 'Components/Modals/Modal-BuscarEmpleado.php'; ?>
                </div>

                <div class="empleados__content" style="display: flex; gap: 1rem;">
                    <div class="a1">
                        <label for="nombre"> Nombres</label>
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control" 
                            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                            id="nombre" name="nombre" readonly>
                        </div>
                    </div>
                    <div class="a1">
                        <label for="apellido"> Apellidos</label>
                            <div class="input-group input-group-sm mb-3">
                                <input type="text" class="form-control" 
                                aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                                id="apellido" name="apellido" readonly>
                            </div>
                    </div>
                </div>

                <div class="empleados__content">
                    <label for="cargo"> Cargo</label>
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control" 
                            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                            id="cargo" name="cargo" readonly>
                        </div>
                </div>

                <div class="empleados__content" style="display: flex; gap: 1rem;">
                    <div class="a1"> 
                        <label for="sueldoM"> Sueldo mensual </label>
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control" 
                            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                            id="sueldoM" name="sueldoM" readonly>
                        </div>
                    </div>

                    <div class="a1">
                        <label for="sueldoS"> Sueldo semanal </label>
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control" 
                            aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                            id="sueldoS" name="sueldoS" readonly>
                        </div>
                    </div>
                </div>

        </div>
        
        <div class="block item-2">
            <h4> Prestamos </h4>
			<div class="empleados__content">
				<label for="prestamo"> Financiero </label>
					<div class="input-group input-group-sm mb-3">
						<span class="input-group-text">$</span>
		  				<input type="text" class="form-control" 
                        aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                        id="prestamo" name="prestamo" readonly
                        oninput="formatInput(this)">

                        <input type="hidden" id="id_prestamo" name="id_prestamo">
					</div>
			</div>

			<div class="empleados__content">
				<label for="consumo"> Consumo </label>
					<div class="input-group input-group-sm mb-3">
						<span class="input-group-text">$</span>
		  				<input type="text" class="form-control" 
                        aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                        id="consumo" name="consumo" readonly
                        oninput="formatInput(this)">

                        <input type="hidden" id="id_consumo" name="id_consumo">
					</div>
			</div>

            <div class="empleados__content">
				<label for="deduc"> Total deducciones </label>
					<div class="input-group input-group-sm mb-3">
						<span class="input-group-text">$</span>
		  				<input type="text" class="form-control" 
                        aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                        id="deduc" name="deduc" readonly
                        oninput="formatInput(this)">
					</div>
			</div>
            
        </div>

        <div class="block item-3">
        <h4> Adicionales </h4>

            <div class="empleados__content">
                <label for="bono"> Bonificaciones </label>
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text">$</span>
                    <input type="text" class="form-control" 
                    aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                    id="bono" name="bono" oninput="formatInput(this)">

                    <input type="hidden" id="bono1" name="bono1">
                </div>
            </div>


            <div class="empleados__content">
                <label for="comision"> Comisiones </label>
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text">$</span>
                        <input type="text" class="form-control" 
                        aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                        id="comision" name="comision" 
                        oninput="formatInput(this)">

                        <input type="hidden" id="comision1" name="comision1">
                    </div>
            </div>

            <div class="empleados__content">
				<label for="asig"> Total asignaciones </label>
					<div class="input-group input-group-sm mb-3">
						<span class="input-group-text">$</span>
		  				<input type="text" class="form-control"
                        aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                        id="asig" name="asig" readonly>
					</div>
			</div>
        </div>

        <div class="block item-4">
            <div class="empleados__content-info">
				<h6 class="info">Neto pagar</h6> <br>
				<p id="netoPagar">$ 0.00 </p> <br> 
                <p id="netoPagarBs">Bs 0.00</p>

                <input type="hidden" id="op" name="op" value="1">
                <input type="hidden" id="Netodiv" name="Netodiv">
			</div>

            <div class="section__content-end">
			    <label> Fecha: <?php echo date("d/m/Y"); ?> </label>
		    </div>
        </div>
        </form>
        
        <script src="../JS/Validate-decimalnumber.js"></script>
<script>

        function openModal() {
        $('#modalPagarTodo').modal('show');
    }

function calcularTotalDeducciones() {
    const prestamo = parseFloat($('#prestamo').val()) || 0;
    const consumo = parseFloat($('#consumo').val()) || 0;
    const totalDeducciones = prestamo + consumo;
    $('#deduc').val(totalDeducciones.toFixed(2));
}

// Llamar la función cada vez que cambie el valor de uno de los inputs
$('#prestamo, #consumo').on('input', calcularTotalDeducciones);

function buscarEmpleado(cedula) {
    if (cedula.length >= 7 && cedula.length <= 8) { // Asegúrate de que la cédula tenga 8 dígitos
        $.ajax({
            url: '../PHP/CTR/Search_General.php', // Cambia esto por la ruta a tu script PHP
            type: 'POST',
            data: { cedula: cedula,
                op: 1 },
            success: function(response) {
                // Suponiendo que la respuesta es un objeto JSON con nombre y apellido
                const datos = JSON.parse(response);
                if (datos) {
                    console.log(datos);
                    $('#cedula1').val(datos.cedula);
                    $('#nombre').val(datos.nombre);
                    $('#apellido').val(datos.apellido);
                    $('#cargo').val(datos.cargo);
                    
                    const sueldo = parseFloat(datos.sueldo);
                    if (!isNaN(sueldo)) {
                        $('#sueldoM').val(sueldo);
                        $('#sueldoS').val(sueldo / 4); // Calcular sueldo semanal
                    } else {
                        // Si sueldo no es válido, asignar valores por defecto
                        $('#sueldoM').val('0');
                        $('#sueldoS').val('0');
                    }

                    //rellenar datos de prestamos 
                    if (datos.prestamos && datos.prestamos.id_prestamos != null) {
                        let descuento = parseFloat(datos.prestamos.descuento);
                        let monto_desc = parseFloat(datos.prestamos.monto_desc);
                        if(descuento < monto_desc){
                            $('#prestamo').val(descuento);
                            console.log('entro');
                        }else{
                            console.log('entro2');
                            $('#prestamo').val(monto_desc);
                        }
                        $('#id_prestamo').val(datos.prestamos.id_prestamos);
                    }else{
                        $('#prestamo').val('0');
                        $('#id_prestamo').val(null);
                    }
                    //rellenar datos de consumo 
                    if (datos.consumo && datos.consumo.id_cuentasp != null) {
                        $('#consumo').val(datos.consumo.descuento);
                        $('#id_consumo').val(datos.consumo.id_cuentasp);
                    }else{
                        $('#consumo').val('0');
                        $('#id_consumo').val(null);
                    }

                    // Calcular total de deducciones
                    const prestamo = parseFloat($('#prestamo').val());
                    const consumo = parseFloat($('#consumo').val());
                    const totalDeducciones = (isNaN(prestamo) ? 0 : prestamo) + (isNaN(consumo) ? 0 : consumo);
                    $('#deduc').val(totalDeducciones); // Asignar el total de deducciones al campo correspondiente
                
                } else {
                    // Si no se encuentra el usuario, puedes limpiar los campos o mostrar un mensaje
                    $('#cedula1').val('');
                    $('#nombre').val('');
                    $('#apellido').val('');
                    $('#cargo').val('');
                    $('#sueldoM').val('0');
                    $('#sueldoS').val('0');
                    $('#prestamo').val('0');
                    $('#id_prestamo').val(null);
                    $('#consumo').val('0');
                    $('#id_consumo').val(null);
                    $('#deduc').val('0');
                }
            },
            error: function() {
                alert('Error en la búsqueda del empleado. Intente nuevamente.');
            }
        });
    } else {
        // Limpiar los campos si la cédula no tiene 8 dígitos
        $('#cedula1').val('');
        $('#nombre').val('');
        $('#apellido').val('');
        $('#cargo').val('');
        $('#sueldoM').val('0');
        $('#sueldoS').val('0');
        $('#prestamo').val('0');
        $('#id_prestamo').val(null);
        $('#consumo').val('0');
        $('#id_consumo').val(null);
        $('#deduc').val('0');
    }
}

$(document).ready(function() {
    // Función para calcular el total de asignaciones
    function calcularTotalAsignaciones() {
        // Obtener los valores de bonificaciones y comisiones
        const bono = parseFloat($('#bono').val()) || 0; // Si no es un número, usar 0
        const comision = parseFloat($('#comision').val()) || 0; // Si no es un número, usar 0

        // Calcular el total
        const totalAsignaciones = bono + comision;

        $('#bono1').val(bono); 
        $('#comision1').val(comision); 
        // Asignar el resultado al campo de total asignaciones
        $('#asig').val(totalAsignaciones.toFixed(2)); // Formatear a 2 decimales
    }

    // Eventos para actualizar el total cuando se cambian los valores
    $('#bono').on('input', calcularTotalAsignaciones);
    $('#comision').on('input', calcularTotalAsignaciones);
});

</script>

<script>
/**
 * Función para calcular el neto a pagar de un empleado.
 */
function Calcular() {
    // Obtener valores de los campos
    const sueldoSemanal = parseFloat($('#sueldoS').val());
    const totalDeducciones = parseFloat($('#deduc').val());
    const totalAsignaciones = parseFloat($('#asig').val());

    // Enviar datos al servidor mediante AJAX
    $.ajax({
        url: '../PHP/CTR/Calcular_General_CTR.php',
        type: 'POST',
        data: {
            op: 1,
            sueldoSemanal: sueldoSemanal,
            totalDeducciones: totalDeducciones,
            totalAsignaciones: totalAsignaciones
        },
        success: function(response) {
            if (response) {
                try {
                    const data = JSON.parse(response);
                    if (data.html) {
                        console.log(response);
                        $('#alerts').html(data.html);
                        $('#prestamo').prop('readonly', false); // Retirar readonly de prestamo
                    } else {
                        console.log(response);
                        $('#netoPagar').text('$ ' + data.netoPagar.toFixed(2)); // Actualizar neto a pagar
                        $('#Netodiv').val(data.netoPagar.toFixed(2)); // Actualizar neto a pagar
                        $('#netoPagarBs').text('Bs ' + (data.netoPagar * data.tasaBCV).toFixed(2)); // Actualizar en Bs
                    }
                } catch (error) {
                    console.error('Error al parsear la respuesta:', error);
                    alert('Error al calcular el Neto a Pagar. Intente nuevamente.');
                }
            } else {
                alert('Error al calcular el Neto a Pagar. Intente nuevamente.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al enviar la solicitud:', error);
            alert('Error al calcular el Neto a Pagar. Intente nuevamente.');
        }
    });
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



        <div class="block item-5">
            <?php include_once 'Components/Tables/Tablas-nomina.php';?>
        </div>
        </div>
    </main>
    
<?php include_once 'Components/Footer.php';?>
