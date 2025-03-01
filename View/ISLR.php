<?php include_once 'Components/Header.php';?>

</header>

    <main>
        <form action="" id="form">
        <div class="form">

            <div class="block Name">
                <h2>Retención de Inpuestos Sobre la Renta (ISLR)</h2>
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
            <h4>Datos del empleado</h4>
                <div class="empleados__content">
                    <label> Cédula</label>
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                            id="cedula" name="cedula"
                            required maxlength="8" 
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                            onkeyup="buscarEmpleado(this.value)">
                            
                            <input type="hidden" id="cedula1" name="cedula1">

                            <input type="submit" class="btn btn-outline-info" value="Buscar" name="Buscar" id="Buscar">
                        </div>
                    </div>
                    
                <div class="empleados__content" style="display: flex; gap: 1rem;">
                    <div class="a1">
                    <label> Nombres</label>
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                            name="nombre" id="nombre" readonly>
                        </div>
                    </div>

                    <div class="a1">
                        <label> Apellidos</label>
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                            name="apellido" id="apellido" readonly>
                        </div>
                    </div>
                </div>

                <div class="empleados__content">
                    <label> Cargo </label>
					<div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                        name="cargo" id="cargo" readonly>
					</div>
			    </div>
        </div>
        
        <div class="block item-2">
            <h4>  </h4>
			<div class="empleados__content" style="display: flex; gap: 1rem;">
                <div class="a1">
                    <label> Sueldo </label>
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text">$</span>
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                        name="sueldo" id="sueldo" readonly>
                    </div>
                </div>
                <div class="a1">
                    <label for=""></label>
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text">Bs.</span>
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                        name="sueldobs" id="sueldobs" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="block item-3">
        <h4> Retención </h4>

        <div class="empleados__content">
                <label>Porcentaje de retención</label>
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text">%</span>
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" 
                        name="reten" id="reten">
                    </div>
            </div>
        </div>

        <div class="block item-4">
            <div class="empleados__content-info">
				<h6 class="info">Monto</h6> <br>
                
				<p id="aporte">Bs 0.00</p>
                <input type="hidden" id="aporte1" name="aporte1">
                <input type="hidden" id="op" name="op" value="4">
			</div>
        </div>
    </form>
        
    <div class="block item-5">
            <?php include_once 'Components/Tables/Tablas-ISLR.php';?>
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
                    $('#cargo').val(datos.cargo);
                    $('#sueldo').val(datos.sueldo);
                    $('#sueldobs').val(datos.sueldobs);
                } else {
                    // Si no se encuentra el usuario, puedes limpiar los campos o mostrar un mensaje
                    $('#cedula1').val('');
                    $('#nombre').val('');
                    $('#apellido').val('');
                    $('#cargo').val('');
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
        $('#cargo').val('');
        $('#sueldo').val(0);
        $('#sueldobs').val(0);
    }
}

</script>

<script>
function Calcular() {
    var sueldo = $('#sueldo').val(); // Obtener el sueldo
    var cedula = $('#cedula1').val(); // Obtener la cédula
    var reten = $('#reten').val(); // Obtener la cédula
    console.log(sueldo);
    console.log(cedula);
    // Enviar datos al servidor mediante AJAX
    $.ajax({
        url: '../PHP/CTR/Calcular_General_CTR.php',
        type: 'POST',
        data: {
            op: 4,
            sueldo: sueldo,
            cedula: cedula,
            reten: reten
        },
        success: function(response) {
            try {
                const data = JSON.parse(response);
                console.log(data);

                // Asignar valores desde la respuesta del servidor
                $('#aporte').text('$' + data.Monto.toFixed(2));
                $('#aporte1').val(data.Monto.toFixed(2));

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