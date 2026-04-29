<?php
include_once '../../../PHP/CLASS/user_Original.php';

// Número de elementos por página
$elementosPorPagina = 10;

// Obtener el número total de elementos
$datos = $Nomina->Solicitudes_pendientes();

// Obtener la búsqueda por cédula
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

// Filtrar los datos según la búsqueda
if ($busqueda) {
    $datos = array_filter($datos, function($dato) use ($busqueda) {
        return strpos($dato['cedula'], $busqueda) !== false;
    });
}

// Calcular el número total de elementos después de la búsqueda
$totalElementos = count($datos);

// Calcular el número total de páginas
$totalPaginas = ceil($totalElementos / $elementosPorPagina);

// Obtener la página actual
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

// Calcular el índice de inicio para la consulta
$inicio = ($paginaActual - 1) * $elementosPorPagina;

// Obtener los datos para la página actual
$datosPagina = array_slice($datos, $inicio, $elementosPorPagina);

// Preparar la respuesta en formato JSON
$respuesta = [
    'datos' => '',
    'totalPaginas' => $totalPaginas,
    'totalElementos' => $totalElementos
];


try {
    foreach ($datosPagina as $dato) {
        $respuesta['datos'] .= '<tr id="registro-' . $dato['id_solicitud'] . '">';
        $respuesta['datos'] .= '<td>' . htmlspecialchars($dato['cedula']) . '</td>';
        $respuesta['datos'] .= '<td><span class="nombre-empleado">' . htmlspecialchars($dato['nombre']) . ' ' . htmlspecialchars($dato['apellido']) . '</span></td>';
        $respuesta['datos'] .= '<td><span class="monto-badge">' . number_format($dato['monto'], 2) . ' $</span></td>';
        $respuesta['datos'] .= '<td><span class="descuento-badge">' . number_format($dato['descuento'], 2) . ' $</span></td>';
        $respuesta['datos'] .= '<td><span class="cuotas-badge">' . $dato['cuotas'] . '</span></td>';
        $respuesta['datos'] .= '<td colspan="2"><span class="concepto-text">' . htmlspecialchars($dato['concepto']) . '</span></td>';
        $respuesta['datos'] .= '<td><span class="fecha-text">' . $dato['f_solicitud'] . '</span></td>';
        $respuesta['datos'] .= '<td><span class="estado-pendiente">' . htmlspecialchars($dato['estado']) . '</span></td>';
        $respuesta['datos'] .= '<td>
            <div class="acciones-group">
                <button class="btn-accion btn-aprobar" title="Aprobar solicitud" onclick="return aprobarSolicitud(\'' . $dato['id_solicitud'] . '\')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                    <span>Aprobar</span>
                </button>
                <button class="btn-accion btn-denegar" title="Denegar solicitud" onclick="return denegarSolicitud(\'' . $dato['id_solicitud'] . '\')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                    <span>Denegar</span>
                </button>
            </div>
        </td>';
        $respuesta['datos'] .= '</tr>';
    }
} catch (Exception $e) {
    $respuesta = [
        'error' => 'Ocurrió un error al procesar la solicitud',
        'mensaje' => $e->getMessage()
    ];
}

echo json_encode($respuesta);
?>