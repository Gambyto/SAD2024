<div class="table__information">
    <div style="display: flex; justify-content: space-between;">
        <div class="empleados__content">
            <label> Buscar:</label>
            <div class="input-group input-group-sm mb-3">
                <input type="month" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" name="FechaBuscar">
                <input type="submit" value="Buscar" class="btn btn-outline-info" name="buscarF">
            </div>
        </div>
    </div>
					<table class="table">
						<thead class="table-dark">
							<tr>
								<th scope="col"> Nombre </th>
								<th scope="col"> Apellido </th>
								<th scope="col"> Cédula </th>
								<th scope="col"> % Retención </th>
								<th scope="col"> Monto retenido </th>
								<th scope="col"> Fecha </th>
							</tr>
						</thead>


						<?php if (isset($_POST['buscarF'])) {?>
							
						<tbody>
							<?php 
							$datos = $Nomina->Search_ISLR($_POST['FechaBuscar']);
							foreach ($datos as $dato) {
								echo '<tr>';
								echo '<th scope="col">'	.$dato['nombre']. '</th>';
								echo '<th scope="col">'	.$dato['apellido']. '</th>';
								echo '<th scope="col">'	.$dato['cedula']. '</th>';
								echo '<th scope="col">'	.$dato['aporte']. '</th>';
								echo '<th scope="col">'	.$dato['monto']. ' Bs </th>';
								echo '<th scope="col">'	.$dato['fecha']. '</th>';
								echo '</tr>';
								}
								
							 ?>
						</tbody>

						<?php } else { ?>

						<tbody>
							<?php 
							$datos = $Nomina->ISLR_View();
							foreach ($datos as $dato) {
								echo '<tr>';
								echo '<th scope="col">'	.$dato['nombre']. '</th>';
								echo '<th scope="col">'	.$dato['apellido']. '</th>';
								echo '<th scope="col">'	.$dato['cedula']. '</th>';
								echo '<th scope="col">'	.$dato['aporte']. '</th>';
								echo '<th scope="col">'	.$dato['monto']. ' Bs </th>';
								echo '<th scope="col">'	.$dato['fecha']. '</th>';
								echo '</tr>';
								}
								
							 ?>
						</tbody>
						<?php  } ?>
					</table>
		</div>