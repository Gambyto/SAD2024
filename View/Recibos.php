<?php require 'Components/Header.php';?>

<?php if ($_SESSION['type'] == "Trabajador") { ?>

    <!-- Cápsula Saldo Disponible (NUEVO) -->
    <?php include_once 'Components/Modals/Modal-SaldoPrestamo.php'; ?>

    <a onclick="openModal()"
        class="btn btn-primary"> Solicitar Préstamo
        <svg  xmlns="http://www.w3.org/2000/svg"  
        width="24"  
        height="24"  
        viewBox="0 0 24 24"  
        fill="none"  
        stroke="currentColor"  
        stroke-width="2"  stroke-linecap="round"  
        stroke-linejoin="round"  
        class="icon icon-tabler icons-tabler-outline icon-tabler-circle-plus">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
            <path d="M9 12h6" />
            <path d="M12 9v6" />
        </svg>
    </a>
    <?php include_once 'Components/Modals/Modal-Solicitud-Prestamo.php'; ?>
<?php } ?>
    
</header>

    <main>
        <form id="form" method="post" class="empleados needs-validation" novalidate>
            <div class="block Name">
                <h2> Recibos </h2>
        </div>

        <div class="block form-1">
            <h4> Variación de la tasa del dolar </h4>

        <?php include_once 'Components/Indicator/Indicador_TD.php';?>

            <h4> </h4>

        <?php include_once 'Components/Indicator/TasaPromedio.php';?>
        <div style="display: flex; gap: 1rem;">
        <?php  
            include_once 'Components/Indicator/Constancia.php';
            include_once 'Components/Indicator/ARC.php';
            ?>
        </div>
        </div>
        
        <div class="block indicator">
            <div class="block form-2" style="min-width: 100%;">
                <h4> Buscar recibos </h4>

                <div class="empleados__content" style="display: flex; gap: 1rem;">
                <?php if ($_SESSION['type'] != 'Trabajador'){ ?>
				<label> Cédula:</label>
					<div class="input-group input-group-sm mb-3">
		  				<input type="text" class="form-control" aria-label="Sizing example input" 
						aria-describedby="inputGroup-sizing-sm" id="cedula1" name="cedula"
						required maxlength="8" 
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            
                            <?php
                            $modalBuscarConfig = [
                                'op'       => 6,
                                'modalId'  => 'modalBuscarEmpVac',
                                'filtroId' => 'filtroBuscarEmpVac',
                                'listaId'  => 'listaBuscarEmpVac',
                                'loaderId' => 'loaderBuscarEmpVac',
                                'vacioId'  => 'vacioBuscarEmpVac',
                                'titulo'   => 'Buscar empleado',
                                'onSelect' => 'seleccionarEmpleadoVac',
                            ];
                            include_once 'Components/Modals/Modal-BuscarEmpleadoGeneral.php';
                            ?>
					</div>

                <?php } ?>
                    <label> Concepto:</label>
                    <div class="input-group input-group-sm mb-3">
                        <select class="form-control" id="concepto" name="concepto">
                            <option value="">Todos</option>
                            <option value="Sueldo">Sueldo y Salario</option>
                            <option value="Vacaciones">Vacaciones</option>
                            <option value="Préstamo">Préstamo</option>
                        </select>
                    </div>

                    <label> Fecha:</label>
                    <div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control" aria-label="Sizing example input" 
                            aria-describedby="inputGroup-sizing-sm" id="fecha" name="fecha"
                            required maxlength="7" placeholder="2025-01"
                            oninput="this.value = this.value.replace(/[^0-9-]/g, '').replace(/^(\d{4})/g, '$1-').replace(/-+/g, '-').replace(/-$/g, '').replace(/^-/g, ''); if (this.value.length > 7) { this.value = this.value.substring(0, 7); }">
                    </div>
                </div>
</div>
            
        </div>

        <div class="block info">
            
            <?php include_once 'Components/Tables/Tablas-recibos_S.php';?>
            
        </div>

        <script src="../JS/Close_modal.js"></script>
        </form>

        <div id="alerts"></div>
    </main>

    <script>
        function openModal() {
        $('#solicitudes').modal('show');
        }

        function seleccionarEmpleadoVac(cedula) {
    $('#cedula').val(cedula);
    buscarEmpleado(cedula);   // función existente en la vista
}

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
                    cambiarPagina(1, datos.cedula, concepto, fecha); // Actualizar la tabla con la cédula del empleado seleccionado
                } else {
                    // Si no se encuentra el usuario, puedes limpiar los campos o mostrar un mensaje
                    $('#cedula1').val('');
                }
            },
            error: function() {
                alert('Error en la búsqueda del empleado. Intente nuevamente.');
            }
        });
    } else {
        // Limpiar los campos si la cédula no tiene 8 dígitos
		$('#cedula1').val('');
}
}
    </script>