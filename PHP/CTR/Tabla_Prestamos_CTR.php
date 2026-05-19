<?php
session_start();
include_once '../CLASS/user_Original.php';

// Número de elementos por página
$elementosPorPagina = 5;

// Obtener todos los datos
$datos = $Nomina->Prestamos_View();
$totalElementos = count($datos);
$totalPaginas   = ceil($totalElementos / $elementosPorPagina);

// Página solicitada (default 1)
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$paginaActual = max(1, min($paginaActual, max($totalPaginas, 1)));

$inicio      = ($paginaActual - 1) * $elementosPorPagina;
$datosPagina = array_slice($datos, $inicio, $elementosPorPagina);

ob_start();
?>
<h4> Prestamos activos
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item <?php if ($paginaActual == 1) echo 'disabled'; ?>">
                <a class="page-link"
                   href="#"
                   onclick="recargarTablaPrestamos(<?php echo max(1, $paginaActual - 1); ?>); return false;"
                   tabindex="-1">Anterior</a>
            </li>
            <li class="page-item <?php if ($paginaActual >= $totalPaginas) echo 'disabled'; ?>">
                <a class="page-link"
                   href="#"
                   onclick="recargarTablaPrestamos(<?php echo min($totalPaginas, $paginaActual + 1); ?>); return false;">Siguiente</a>
            </li>
        </ul>
    </nav>
</h4>

<table class="table" id="tablaPrestamos">
    <thead class="table-primary" style="text-align: center;">
        <tr>
            <th scope="col"> Cédula </th>
            <th scope="col"> Nombre </th>
            <th scope="col"> Monto </th>
            <th scope="col"> N° cuotas </th>
            <th scope="col"> Deuda </th>
            <th scope="col"> Fecha de solicitud </th>
            <th scope="col"> Fecha de límite </th>
            <?php if ($_SESSION['type'] == 'Gerencia') { ?>
            <th scope="col"> Opciones </th>
            <?php } ?>
        </tr>
    </thead>
    <tbody style="text-align: center;" id="cuerpoTabla">
        <?php foreach ($datosPagina as $dato):
            $clase = (strtotime($dato['date_limit']) < strtotime(date('Y-m-d'))) ? 'mora' : '';
        ?>
        <tr class="<?php echo $clase; ?>">
            <th scope="col"><?php echo $dato['cedula']; ?></th>
            <th scope="col"><?php echo $dato['nombre'] . ' ' . $dato['apellido']; ?></th>
            <th scope="col" style="text-align: right;"><?php echo $dato['monto']; ?> $</th>
            <th scope="col"><?php echo $dato['cuotas']; ?></th>
            <th scope="col"><?php echo $dato['monto_desc']; ?> $</th>
            <th scope="col"><?php echo $dato['fecha']; ?></th>
            <th scope="col"><?php echo $dato['date_limit']; ?></th>
            <?php if ($_SESSION['type'] == 'Gerencia'): ?>
            <th>
                <a name="btn2" class="btn btn-outline-danger"
                   onclick="return confirmar('<?php echo $dato['id_prestamos']; ?>')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                         fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                    </svg>
                </a>
            </th>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
$html = ob_get_clean();
echo json_encode(['html' => $html]);
