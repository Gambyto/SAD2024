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
    'totalPaginas' => $totalPaginas
];


try {
    foreach ($datosPagina as $dato) {
        $respuesta['datos'] .= '<tr>';
        $respuesta['datos'] .= '<th scope="col">' . $dato['cedula'] . '</th>';
        $respuesta['datos'] .= '<th scope="col">' . $dato['nombre'].' '.$dato['apellido']. '</th>';
        $respuesta['datos'] .= '<th scope="col" style="text-align: right;">' . $dato['monto'] . ' $</th>';
        $respuesta['datos'] .= '<th scope="col" style="text-align: right;">' . $dato['descuento'] . ' $</th>';
        $respuesta['datos'] .= '<th scope="col">' . $dato['cuotas'] . '</th>';
        $respuesta['datos'] .= '<th scope="col" colspan="2">' . $dato['concepto'] . '</th>';
        $respuesta['datos'] .= '<th scope="col">' . $dato['f_solicitud'] . '</th>';
        $respuesta['datos'] .= '<th scope="col" class="text-warning">' . $dato['estado'] . '</th>';
        $respuesta['datos'] .= '<th scope="col">
        <button name="btn2" title="Aceptar Prestamo" class="btn btn-outline-success" onclick="return confirmarA(\'' . $dato['id_solicitud'] . '\')">
        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
        </button>
        <button name="btn2" title="Denegar Prestamo" class="btn btn-outline-danger" onclick="return confirmarD(\'' . $dato['id_solicitud'] . '\')">
        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
        </button>
    </th>';
        $respuesta['datos'] .= '</tr>';
    }
} catch (Exception $e) {
    // Si ocurre un error, devuelve un JSON con un mensaje de error
    $respuesta = [
        'error' => 'Ocurrió un error al procesar la solicitud',
        'mensaje' => $e->getMessage()
    ];
}

// Devuelve la respuesta en formato JSON
echo json_encode($respuesta);
?>
