<?php include_once 'Components/Header.php';?>

<?php if (isset($_SESSION['TasaBCV'])) { ?>
    <a href="PlantillaPDF/Fideicomiso.php" class="btn btn-danger" target="_blank"> Fideicomiso General  
		<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
  			<path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
		</svg>
	</a>
<?php } ?>

</header>

    <main>
        <form id="form">
            <div class="form">

                <div class="block Name">
                    <h2>Fideicomisos</h2>
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
                    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
                    name="cedula" id="cedula" required 
                    maxlength="8" 
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                    onkeyup="buscarEmpleado(this.value)">

                    <input type="hidden" id="cedula1" name="cedula1">

                    <input type="submit" class="btn btn-outline-info" name="Buscar" value="Buscar" id="Buscar">
                </div>
    		</div>
            
			<div class="empleados__content">
                <label> Nombres:</label>
                <div class="input-group input-group-sm mb-3">
                    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                    name="nombre" id="nombre" readonly>
                </div>
			</div>
            
	        <div class="empleados__content">
                <label> Apellidos:</label>
                <div class="input-group input-group-sm mb-3">
                    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                    name="apellido" id="apellido" readonly>
                </div>
	        </div>
            
			<div class="empleados__content">
                <label> Sueldo:</label>
					<div class="input-group input-group-sm mb-3">
                        <span class="input-group-text">$</span>
						<input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                        name="sueldo" id="sueldo" readonly>
                        
						<span style="margin-left: 1rem;" class="input-group-text">Bs</span>
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                        name="sueldobs" id="sueldobs" readonly>
					</div>
                </div>
                
                <div class="empleados__content">
                    <label> Fecha de ingreso:</label>
					<div class="input-group input-group-sm mb-3">
                        <input type="date" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                        name="fechaingreso" id="fechaingreso" readonly>
					</div>
                </div>
            </div>
            
            <div class="block item-2">
                <div class="empleados__content">
		    	<label> Tasa de utlidad:</label>
                <div class="input-group input-group-sm mb-3">
                    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                    name="Tutilidad" id="Tutilidad" readonly>
                </div>
		    </div>
            
		    <div class="empleados__content">
                <label>Tasa Bono vacacional:</label>
                <div class="input-group input-group-sm mb-3">
                    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                    name="bonoVaca" id="bonoVaca" readonly>
                </div>
		    </div>
            
		    <div class="empleados__content">
                <label> Alicuota Utilidad:</label>
                <div class="input-group input-group-sm mb-3">
                    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                    name="alicuotaU" id="alicuotaU" readonly>
                </div>
		    </div>
            
		    <div class="empleados__content">
                <label> Alicuota Bono Vacacional:</label>
                <div class="input-group input-group-sm mb-3">
		    			<input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                        name="alicuotaBV" id="alicuotaBV" readonly>
		    		</div>
                </div>
                
                <div class="empleados__content" style="display: flex; gap: 1rem;">
                    <div>
                        <label> Sueldo Integral:</label>
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                            name="Sintegral" id="Sintegral" readonly>
                        </div>
                    </div>
                    <div>
                        <label> Diario Integral:</label>
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                            name="Dintegral" id="Dintegral" readonly>
                        </div>
                    </div>    
                </div>
            </div>
            
            <div class="block item-3">
                <div class="empleados__content">
                    <label> Antigüedad:</label>
                    <div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                        name="antiguedad" id="antiguedad" readonly>

                        <input type="hidden" name="tservicio" id="tservicio">
                    </div>
                </div>
                
                
            <div class="empleados__content">
                <label> Días acumulados adicionales:</label>
                <div class="input-group input-group-sm mb-3">
                    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                    name="Dvacaciones" id="Dvacaciones" readonly>
                </div>
            </div>
            
            <div class="empleados__content">
                <label> Total días deparar:</label>
                <div class="input-group input-group-sm mb-3">
                    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
                     name="Tdias" id="Tdias"  readonly>
                </div>
            </div>
        </div>
        
        <div class="block item-4">
            <div class="empleados__content-info">
                <h6 class="info">Fideicomiso</h6> <br>
                
				<p id="fideicomiso">$ 0.00</p> <br> 
                <p id="fideicomisobs">Bs 0.00</p>

			</div>
            
			<div class="empleados__content-info">
                <h6 class="info">Monto anticipo</h6> <br>
                
				<p id="anticipo">$ 0.00</p> <br>
                <p id="anticipobs">Bs 0.00</p>
                
			</div>
            <input type="text" id="fideicomiso1" name="fideicomiso1">
            <input type="text" id="anticipo1" name="anticipo1">
            <input type="hidden" id="op" name="op" value="3">
        </div>
    </form>
        
    <div class="block item-5">
        <?php include_once 'Components/Tables/Tablas-fideicomiso.php';?>
    </div>
</div>
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
                    $('#fechaingreso').val(datos.f_ingreso);
                    $('#sueldo').val(datos.sueldo);
                    $('#sueldobs').val(datos.sueldobs);
                } else {
                    // Si no se encuentra el usuario, puedes limpiar los campos o mostrar un mensaje
                    $('#cedula1').val('');
                    $('#nombre').val('');
                    $('#apellido').val('');
                    $('#fechaingreso').val('');
                    $('#sueldo').val(0);
                    $('#sueldobs').val(0);
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
        $('#fechaingreso').val('');
        $('#sueldo').val(0);
        $('#sueldobs').val(0);
    }
}

</script>

<script>
function Calcular() {
    var sueldo = $('#sueldo').val(); // Obtener el sueldo
    var cedula = $('#cedula1').val(); // Obtener la cédula
    console.log(sueldo);
    console.log(cedula);
    // Enviar datos al servidor mediante AJAX
    $.ajax({
        url: '../PHP/CTR/Calcular_General_CTR.php',
        type: 'POST',
        data: {
            op: 3,
            sueldo: sueldo,
            cedula: cedula
        },
        success: function(response) {
            try {
                const data = JSON.parse(response);
                console.log(data);

                // Asignar valores desde la respuesta del servidor
                $('#Tutilidad').val(data.Tutilidad);
                $('#alicuotaU').val(data.alicuotaU);
                $('#bonoVaca').val(data.bonoVaca);
                $('#alicuotaBV').val(data.alicuotaBV);
                $('#Sintegral').val(data.Sintegral);
                $('#Dintegral').val(data.Dintegral);
                $('#antiguedad').val(data.antiguedad);
                $('#Dvacaciones').val(data.Dvacaciones);
                $('#Tdias').val(data.Tdias);
                $('#Tservicio').val(data.Tservicio);

                // Actualizar los valores de fideicomiso y anticipo
                $('#fideicomiso1').val(data.fideicomiso.toFixed(2));
                $('#anticipo1').val(data.anticipo.toFixed(2));

                $('#fideicomiso').text('$ ' + data.fideicomiso.toFixed(2));
                $('#anticipo').text('$ ' + data.anticipo.toFixed(2));
                $('#anticipobs').text('Bs ' + (data.anticipo * data.tasaBCV).toFixed(2));
                $('#fideicomisobs').text('Bs ' + (data.fideicomiso * data.tasaBCV).toFixed(2));
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
                if (response) {
                    alert(response);
                    // Limpiar el formulario o realizar otra acción
                    $('#nominaForm')[0].reset(); // Limpiar el formulario
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