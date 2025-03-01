<?php
// Número de elementos por página
$elementosPorPagina = 7;

$FechaFiltro = isset($_GET['fecha']) ? $_GET['fecha'] : null;

// Obtener el número total de elementos
if ($FechaFiltro) {
	$datos = $Nomina->Search_Nomina($FechaFiltro);
}else{
	$datos = $Nomina->View_Nomina(); // Asegúrate de que esta función devuelva todos los registros
}
$totalElementos = count($datos);

// Calcular el número total de páginas
$totalPaginas = ceil($totalElementos / $elementosPorPagina);

// Obtener la página actual
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

// Calcular el índice de inicio para la consulta
$inicio = ($paginaActual - 1) * $elementosPorPagina;

// Obtener los datos para la página actual
$datosPagina = array_slice($datos, $inicio, $elementosPorPagina);
?>

<div class="table__information">
		<h4 class="title"> Nómina </h4>
		<form action="" >
			<div class="empleados__content">
				<div class="input-group input-group-sm mb-3">
					<input type="month" name="fecha" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" name="FechaBuscar">
					<input type="submit" class="btn btn-outline-info" value="Buscar por fecha">
				</div>
			</div>
			
			<!-- Botón para restablecer el filtro -->
			<a href="?pagina=1" class="btn btn-outline-secondary">Ver nómina actual</a>
			<!-- Navegación de Páginas -->
			<nav aria-label="Page navigation" class="navi">
				<ul class="pagination">
					<li class="page-item <?php if ($paginaActual == 1) echo 'disabled'; ?>">
						<a class="page-link" href="?pagina=<?php echo max(1, $paginaActual - 1); ?>&fecha=<?php echo $FechaFiltro; ?>" tabindex="-1">Anterior</a>
					</li>
					
					<li class="page-item <?php if ($paginaActual == $totalPaginas) echo 'disabled'; ?>">
						<a class="page-link" href="?pagina=<?php echo min($totalPaginas, $paginaActual + 1); ?>&fecha=<?php echo $FechaFiltro; ?>">Siguiente</a>
					</li>
				</ul>
			</nav>
		</form>

</div>

    <table class="table">
        <thead class="table-dark">
            <tr>
                <th scope="col"> Nombre </th>
                <th scope="col"> Apellido </th>
                <th scope="col"> Cédula </th>
                <th scope="col"> Sueldo mensual </th>
                <th scope="col"> Sueldo semanal </th>
                <th scope="col"> Deducciones </th>
                <th scope="col"> Asignaciones </th>
                <th scope="col" colspan="2"> Neto a pagar </th>
                <th scope="col"> Tasa $ BCV </th>
                <th scope="col"> Fecha </th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($datosPagina as $dato): ?>
                <tr>
                    <th scope="col"><?php echo $dato['nombre']; ?></th>
                    <th scope="col"><?php echo $dato['apellido']; ?></th>
                    <th scope="col"><?php echo $dato['cedula']; ?></th>
                    <th scope="col"><?php echo $dato['sueldo']; ?> $</th>
                    <th scope="col"><?php echo $dato['sueldosem']; ?> $</th>
                    <th scope="col"><?php echo $dato['desc1'] + $dato['desc2']; ?> $</th>
                    <th scope="col"><?php echo $dato['asignaciones']; ?> $</th>
                    <th scope="col"><?php echo $dato['neto']; ?> $</th>
                    <th scope="col"><?php echo $dato['netobs']; ?> Bs</th>
                    <th scope="col"><?php echo $dato['TasaBCV']; ?> Bs</th>
                    <th scope="col"><?php echo $dato['fecha']; ?></th>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

