<?php
include_once '../../../PHP/CLASS/user_Original.php';

// Número de elementos por página
$elementosPorPagina = 10;

// Obtener el número total de elementos
$datos = $Nomina->Prestamos_View_Modal();

$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : null;

if ($busqueda !== null) {
    $datos = array_filter($datos, function($dato) use ($busqueda) {
        return preg_match('/' . $busqueda . '/i', $dato['cedula']);
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
        $respuesta['datos'] .= '<th scope="col">' . $dato['nombre'] . ' ' . $dato['apellido'] . '</th>';
        $respuesta['datos'] .= '<th scope="col" style="text-align: right;">' . $dato['monto'] . ' $</th>';
        $respuesta['datos'] .= '<th scope="col" style="text-align: right;">' . $dato['descuento'] . ' $</th>';
        $respuesta['datos'] .= '<th scope="col">' . $dato['cuotas'] . '</th>';
        $respuesta['datos'] .= '<th scope="col" colspan="2">' . $dato['concepto'] . '</th>';
        $respuesta['datos'] .= '<th scope="col">' . $dato['fecha'] . '</th>';
        $respuesta['datos'] .= '<th scope="col">' . $dato['date_limit'] . '</th>';
        //$respuesta['datos'] .= '<th scope="col">
        //<button name="btn2" class="btn btn-outline-primary" onclick="">
        //<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
        //    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
        //    <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4"></path>
        //    <path d="M5 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6"></path>
        //    <path d="M17 18h2"></path>
        //    <path d="M20 15h-3v6"></path>
        //    <path d="M11 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z"></path>
        //</svg>
        //</button>
    //</th>';
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
