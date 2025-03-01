<?php
include_once '../../../PHP/CLASS/user_Original.php';

// Número de elementos por página
$elementosPorPagina = 10;

// Obtener el número total de elementos
$datos = $Nomina->View_Variacion();

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

$meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',
    'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

// Agrega un try-catch para manejar cualquier error que pueda ocurrir
try {
    foreach ($datosPagina as $dato) {
        $mes = isset($dato['mes']) ? $meses[$dato['mes'] - 1] : 'Mes no disponible';
        $respuesta['datos'] .= '<tr>';
        $respuesta['datos'] .= '<th scope="col">' . $dato['anio'] . '</th>';
        $respuesta['datos'] .= '<th scope="col">' . $mes . '</th>';
        $respuesta['datos'] .= '<th scope="col" style="text-align: right;">' . number_format($dato['costo_nominia_actual'],2) . ' $</th>';
        $respuesta['datos'] .= '<th scope="col" style="text-align: right;">' . number_format($dato['costo_nominia_anterior'],2) . ' $</th>';
        if ($dato['variacion_porcentual'] > 0) {
            $respuesta['datos'] .= '<th scope="col" style="text-align: right;">
            <span class="badge bg-danger" id="totalPagado">+
            ' . number_format($dato['variacion_porcentual'],2) . ' %</span></th>';
        } else {
            $respuesta['datos'] .= '<th scope="col" style="text-align: right;">
            <span class="badge bg-success" id="totalPagado">
            ' . number_format($dato['variacion_porcentual'],2) . ' %</span></th>';
        }
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
