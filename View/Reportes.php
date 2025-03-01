<?php include_once 'Components/Header.php';?>

</header>

    <main>
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
		  					<input type="text" class="form-control" aria-label="Sizing example input" 
							aria-describedby="inputGroup-sizing-sm" name="Utilidades" id="Utilidades"
                            maxlength="2" 
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
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


