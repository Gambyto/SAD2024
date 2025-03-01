<?php
// Número de elementos por página
$elementosPorPagina = 5;

$FechaFiltro = isset($_GET['fecha']) ? $_GET['fecha'] : null;

// Obtener el número total de elementos
if ($FechaFiltro) {
    $datos = $Nomina->Search_Fide($FechaFiltro); // Cambia esta función según tu lógica
} else {
    $datos = $Nomina->View_Fideicomiso(); // Asegúrate de que esta función devuelva todos los registros
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
    <h4 class="title"> Fideicomisos </h4>
    <form action="">
        <div class="empleados__content">
            <div class="input-group input-group-sm mb-3">
                <input type="month" name="fecha" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" value="<?php echo htmlspecialchars($FechaFiltro); ?>">
                <input type="submit" class="btn btn-outline-info" value="Buscar por fecha">
            </div>
        </div>
        
        <!-- Botón para restablecer el filtro -->
        <a href="?pagina=1" class="btn btn-outline-secondary">Ver fideicomisos actuales</a>
        <!-- Navegación de Páginas -->
        <nav aria-label="Page navigation" class="navi">
            <ul class="pagination">
                <li class="page-item <?php if ($paginaActual == 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?pagina=<?php echo max(1, $paginaActual - 1); ?>&fecha=<?php echo htmlspecialchars($FechaFiltro); ?>" tabindex="-1">Anterior</a>
                </li>
                
                <li class="page-item <?php if ($paginaActual == $totalPaginas) echo 'disabled'; ?>">
                    <a class="page-link" href="?pagina=<?php echo min($totalPaginas, $paginaActual + 1); ?>&fecha=<?php echo htmlspecialchars($FechaFiltro); ?>">Siguiente</a>
                </li>
            </ul>
        </nav>
    </form>

    <table class="table">
        <thead class="table-dark">
            <tr>
                <th scope="col"> Nombre </th>
                <th scope="col"> Apellido </th>
                <th scope="col"> Cédula </th>
                <th scope="col"> Fecha de ingreso </th>
                <th scope="col"> Sueldo </th>
                <th scope="col"> Tasa de utilidad </th>
                <th scope="col"> Tasas de bono vacacional </th>
                <th scope="col"> Alícuota utilidad </th>
                <th scope="col"> Alícuota bono vacacional </th>
                <th scope="col"> Sueldo integral </th>
                <th scope="col"> Salario diario integral </th>
                <th scope="col"> Días antigüedad </th>
                <th scope="col"> Días acumulados adicional </th>
                <th scope="col"> Total días </th>
                <th scope="col"> Fideicomiso </th>
                <th scope="col"> Monto anticipo </th>
                <th scope="col"> Fecha </th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($datosPagina as $dato): ?>
                <tr>
                    <th scope="col"><?php echo htmlspecialchars($dato['nombre']); ?></th>
                    <th scope="col"><?php echo htmlspecialchars($dato['apellido']); ?></th>
                    <th scope="col"><?php echo htmlspecialchars($dato['cedula']); ?></th>
                    <th scope="col"><?php echo htmlspecialchars($dato['f_ingreso']); ?></th>
                    <th scope="col"><?php echo htmlspecialchars($dato['sueldo']); ?></th>
					<th scope="col"><?php echo htmlspecialchars($dato['tasa_utilidad']); ?></th>
                    <th scope="col"><?php echo htmlspecialchars($dato['t_bonovacacional']); ?></th>
                    <th scope="col"><?php echo htmlspecialchars($dato['a_utilidad']); ?></th>
                    <th scope="col"><?php echo htmlspecialchars($dato['a_bonovacional']); ?></th>
                    <th scope="col"><?php echo htmlspecialchars($dato['sueldo_integral']); ?></th>
                    <th scope="col"><?php echo htmlspecialchars($dato['sueldod_integral']); ?></th>
                    <th scope="col"><?php echo htmlspecialchars($dato['dias_antiguedad']); ?></th>
                    <th scope="col"><?php echo htmlspecialchars($dato['dias_acumulados']); ?></th>
                    <th scope="col"><?php echo htmlspecialchars($dato['total_dias']); ?></th>
                    <th scope="col"><?php echo htmlspecialchars($dato['monto']); ?></th>
                    <th scope="col"><?php echo htmlspecialchars($dato['anticipo']); ?></th>
                    <th scope="col"><?php echo htmlspecialchars($dato['fecha']); ?></th>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>