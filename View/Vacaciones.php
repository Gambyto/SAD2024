<?php require 'Components/Header.php';?>

</header>

    <main>
    <div id="alerts"></div>

        <form action="" class="form" id="form">
            <div class="block Name">
                <h2>vacaciones</h2>
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
				<input type="button" value="Calcular" class="btn btn-outline-success" name="calcular" onclick="Calcular()">
					
				<input type="button" value="Guardar" class="btn btn-outline-success" name="guardar" onclick="Guardar()">
					
				<input type="reset" value="Nuevo" class="btn btn-outline-danger" name="reset">
			</div>
        </div>

        <div class="block item-1">
            <div class="empleados__content">
				<label> Cédula:</label>
					<div class="input-group input-group-sm mb-3">
		  				<input type="text" class="form-control" aria-label="Sizing example input" 
						aria-describedby="inputGroup-sizing-sm" id="cedula" name="cedula"
						required maxlength="8" 
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                    	onkeyup="buscarEmpleado(this.value)">

						<input type="hidden" name="cedula1" id="cedula1">

		  				<input type="submit" class="btn btn-outline-info" value="Buscar" name="Buscar" id="Buscar">
					</div>
			</div>

			<div class="empleados__content">
				<label> Nombres:</label>
					<div class="input-group input-group-sm mb-3">
		  				<input type="text" class="form-control" aria-label="Sizing example input" 
						aria-describedby="inputGroup-sizing-sm" name="nombre" id="nombre" readonly>
					</div>
			</div>

			<div class="empleados__content">
				<label> Apellidos:</label>
					<div class="input-group input-group-sm mb-3">
		  				<input type="text" class="form-control" aria-label="Sizing example input" 
						aria-describedby="inputGroup-sizing-sm" name="apellido" id="apellido" readonly>
					</div>
			</div>
        </div>
        
        <div class="block item-2">
                <div class="empleados__content">
					<label> Fecha de ingreso:</label>
						<div class="input-group input-group-sm mb-3">
		  					<input type="date" class="form-control" aria-label="Sizing example input" 
							aria-describedby="inputGroup-sizing-sm" name="f_ingreso" id="f_ingreso" readonly>
						</div>
				</div>

				<div class="empleados__content">
					<label> Sueldo Diario:</label>
						<div class="input-group input-group-sm mb-3">
		  					<input type="text" class="form-control" aria-label="Sizing example input" 
							aria-describedby="inputGroup-sizing-sm" name="sueldoD" id="sueldoD" readonly>
							<span class="input-group-text">$</span>
						</div>
				</div>

				<div class="empleados__content">
					<label> Días de vacaciones:</label>
						<div class="input-group input-group-sm mb-3">
		  					<input type="text" class="form-control" aria-label="Sizing example input" 
							aria-describedby="inputGroup-sizing-sm" name="Dvacaciones" id="Dvacaciones" readonly>
						</div>
				</div>
            
        </div>

        <div class="block item-3">
                <div class="empleados__content">
					<label> Días Feriados:</label>
						<div class="input-group input-group-sm mb-3">
		  					<input type="text" class="form-control" aria-label="Sizing example input" 
							.aria-describedby="inputGroup-sizing-sm" name="feriados" id="feriados"
                            maxlength="2" 
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
						</div>
				</div>

				<div class="empleados__content">
                    <label> Utilidades:</label>
                    <div class="input-group input-group-sm mb-3">
                        <select class="form-control" name="Utilidades" id="Utilidades">
                            <option value="0">0</option>
                            <option value="30">30</option>
                            <option value="60">60</option>
                            <option value="90">90</option>
                        </select>
                    </div>
                </div>

				<div class="empleados__content">
					<label> Días pendientes:</label>
						<div class="input-group input-group-sm mb-3">
		  					<input type="text" class="form-control" aria-label="Sizing example input" 
							aria-describedby="inputGroup-sizing-sm" name="pendientes" id="pendientes"
                            maxlength="2" 
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
						</div>
				</div>
        </div>

        <div class="block item-4">
            <div class="empleados__content">
					<label> Inicio de vacaciones:</label>
						<div class="input-group input-group-sm mb-3">
		  					<input type="date" class="form-control" aria-label="Sizing example input" 
							aria-describedby="inputGroup-sizing-sm" name="Vacacionesini" id="Vacacionesini">
						</div>
				</div>

				<div class="empleados__content">
					<label> Fin de vacaciones:</label>
						<div class="input-group input-group-sm mb-3">
		  					<input type="date" class="form-control" aria-label="Sizing example input" 
							aria-describedby="inputGroup-sizing-sm" name="Vacacionesfin" id="Vacacionesfin" readonly>
						</div>
				</div>

				<div class="empleados__content">
					<label> Inicio laboral:</label>
						<div class="input-group input-group-sm mb-3">
		  					<input type="date" class="form-control" aria-label="Sizing example input" 
							aria-describedby="inputGroup-sizing-sm" name="inilaboral" id="inilaboral" readonly>
						</div>
				</div>
                <input type="hidden" id="op" name="op" value="2">
                <input type="hidden" name="finsemana" id="finsemana">
				<input type="hidden" name="servicio" id="servicio">
				<input type="hidden" name="monto" id="monto">
				<input type="hidden" name="ince" id="ince">
        </div>
        
        <div class="block item-5">
            <?php include_once 'Components/Tables/Tablas-vacaciones.php';?>
        </div>
        </form>
    </main>
    
<?php include_once 'Components/Footer.php';?>



<script src="../JS/Validate-decimalnumber.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function buscarEmpleado(cedula) {
    if (cedula.length >= 7 && cedula.length <= 8) { // Asegúrate de que la cédula tenga 8 dígitos
        $.ajax({
            url: '../PHP/CTR/Search_General.php', // Cambia esto por la ruta a tu script PHP
            type: 'POST',
            data: { cedula: cedula ,
                op : 3,
                tasa : <?php echo json_encode($_SESSION['TasaBCV']); ?> },
            success: function(response) {
                // Suponiendo que la respuesta es un objeto JSON con nombre y apellido
                const datos = JSON.parse(response);
                if (datos) {
                    console.log(datos);
                    $('#cedula1').val(datos.cedula);
                    $('#nombre').val(datos.nombre);
                    $('#apellido').val(datos.apellido);
                    $('#f_ingreso').val(datos.f_ingreso);
					$('#sueldoD').val(datos.sueldod);
					$('#feriados').val(0);
	 				$('#Utilidades').val(0);
					$('#pendientes').val(0);

                } else {
                    // Si no se encuentra el usuario, puedes limpiar los campos o mostrar un mensaje
                    $('#cedula1').val('');
                    $('#nombre').val('');
                    $('#apellido').val('');
					$('#f_ingreso').val('');
					$('#sueldoD').val('');
					$('#feriados').val('');
	 				$('#Utilidades').val('');
					$('#pendientes').val('');
					$('#Dvacaciones').val(0);
					$('#Vacacionesfin').val('');
					$('#inilaboral').val('');
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
		$('#f_ingreso').val('');
		$('#sueldoD').val('');
		$('#feriados').val(0);
	 	$('#Utilidades').val(0);
		$('#pendientes').val(0);
		$('#Dvacaciones').val(0);
		$('#Vacacionesfin').val('');
		$('#inilaboral').val('');
}
}

</script>

<script>
function Calcular() {
    var sueldo = $('#sueldoD').val(); // Obtener el sueldo
    var cedula = $('#cedula1').val(); // Obtener la cédula
	var vacacionesini = $('#Vacacionesini').val();

	// variables probables
	var feriado = $('#feriados').val();
	var utilidades = $('#Utilidades').val();
	var pendientes = $('#pendientes').val();

    console.log(sueldo);
    console.log(cedula);
	console.log(vacacionesini);
	console.log(feriado);
	console.log(utilidades);
	console.log(pendientes);
    // Enviar datos al servidor mediante AJAX
    $.ajax({
        url: '../PHP/CTR/Calcular_General_CTR.php',
        type: 'POST',
        data: {
            op: 2,
            sueldo: sueldo,
            cedula: cedula,
			vacacionesini: vacacionesini,
			feriado: feriado,
			utilidades: utilidades,
			pendientes: pendientes
        },
        success: function(response) {
            try {
                const data = JSON.parse(response);
                var totalferiado = data.feriado * ((sueldo * 0.33) / 30);
                var totalutilidades = data.utilidades * ((sueldo * 0.33) / 30);
                var totalpendientes = data.pendientes * ((sueldo * 0.33) / 30);
                var sueldod = ((sueldo * 0.33)/30);
                console.log(data);
				
                if(data.html){
                    $('#alerts').html(data.html);
                }else{ 

                    $('#Dvacaciones').val(data.Dvacaciones);
                    $('#Vacacionesfin').val(data.Vacacionesfin);
				$('#inilaboral').val(data.laboral);
				$('#servicio').val(data.servicio);
				$('#monto').val(data.Monto.toFixed(2));
				$('#ince').val(data.ince.toFixed(2));

				 // Llenar la tabla con los datos recibidos
				$('.T_Dvacaciones').text(data.Dvacaciones || 0);
				$('.T_vacation').text(data.vacation.toFixed(2) + '$' || '0.00 $');
                $('.T_sueldod').text(sueldod.toFixed(2) + '$' || '0.00 $');

                $('#T_findesemana').text(data.FinSemana || 0);
                $('#finsemana').val(data.FinSemana || 0);
                $('#T_pweekend').text(data.pweekend.toFixed(2) + '$' || '0.00 $');
                
				$('#T_feriado').text(data.feriado || 0);
                $('#T_totalferiado').text(totalferiado.toFixed(2) + '$' || '0.00 $');
                 
                $('#T_pendientes').text(data.pendientes || 0);
                $('#T_totalpendiente').text(totalpendientes.toFixed(2) + '$' || '0.00 $');
                
                $('#T_utilidades').text(data.utilidades || 0);
                $('#T_totalutilidades').text(totalutilidades.toFixed(2) + '$' || '0.00 $');
                
                $('#T_inceV').text('-' + data.ince.toFixed(2) + '$' || '- 0.00 $');
                $('#T_monto').text(data.Monto.toFixed(2) + '$' || '0.00 $');
                
            }

            } catch (e) {
                console.error("Error al procesar la respuesta JSON:", e);
                alert('Error al procesar la respuesta del servidor. Intente nuevamente.');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud AJAX:", status, error);
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
                const data = JSON.parse(response);
                if (response) {
                    console.log(response);
                    $('#alerts').html(data.html);
                    $('#')[0].reset(); // Limpiar el formulario
                } else {
                    alert(response);
                }
            },
            error: function() {
                alert('Error en la conexión al servidor. Intente nuevamente.');
            }
        });
    };
    </script>