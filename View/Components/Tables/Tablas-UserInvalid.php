<?php
include_once '../../../PHP/CLASS/user_Original.php';

// Número de elementos por página
$elementosPorPagina = 10;

// Obtener el número total de elementos
$datos = $User->Invalid_View();

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

// Agrega un try-catch para manejar cualquier error que pueda ocurrir
try {
    foreach ($datosPagina as $dato) {
        $respuesta['datos'] .= '<tr>';
        $respuesta['datos'] .= '<th scope="col">' . $dato['cedula'] . '</th>';
        $respuesta['datos'] .= '<th scope="col">' . $dato['nombre'] . ' ' . $dato['apellido'] . '</th>';
        $respuesta['datos'] .= '<th scope="col">' . $dato['username'] . '</th>';
        $respuesta['datos'] .= '<th scope="col">
					<span class="clave-oculta">••••••••</span>
					<span class="clave-visible" style="display: none;">' . $dato['clave'] . '</span>
					<button type="button" class="btn btn-sm btn-outline-secondary mostrar-clave" onclick="mostrarClave(this)">
						<i class="bi bi-eye"></i>
					</button>
				</th>';
        $respuesta['datos'] .= '<th scope="col">' . $dato['type'] . '</th>';
        $respuesta['datos'] .= '<th scope="col">
        <button name="btn2" class="btn btn-outline-primary" onclick="return ReValidate(\'' . $dato['cedula'] . '\')">
        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  
        fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  
        stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-back">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" /></svg>
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
