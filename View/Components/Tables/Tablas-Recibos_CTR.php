<?php
session_start();
include_once '../../../PHP/CLASS/user_Original.php';
// Número de elementos por página
$elementosPorPagina = 7;

// Obtener datos de nómina, vacaciones y préstamos
$datosNomina = $Nomina->Search_Nomina();
$datosVacaciones = $Nomina->View_Vacation();
$datosPrestamos = $Nomina->Prestamos_View_report();

// Combinar todos los datos en un solo arreglo
$datosCombinados = array_merge($datosNomina, $datosVacaciones, $datosPrestamos);

// Verificar el rol del usuario
$rol = $_SESSION['type'];

// Si el usuario es trabajador, filtrar los datos
if ($rol == 'Trabajador') {
    $cedulaTrabajador = $_SESSION['id'];
    $datosCombinados = array_filter($datosCombinados, function($dato) use ($cedulaTrabajador) {
        return $dato['cedula'] == $cedulaTrabajador;
    });
}

// Obtener las búsquedas por cédula, concepto y fecha
$busquedaCedula = isset($_GET['cedula']) ? $_GET['cedula'] : '';
$busquedaConcepto = isset($_GET['concepto']) ? $_GET['concepto'] : '';
$busquedaFecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';

// Filtrar los datos según las búsquedas
if ($busquedaCedula || $busquedaConcepto || $busquedaFecha) {
    $datosCombinados = array_filter($datosCombinados, function($dato) use ($busquedaCedula, $busquedaConcepto, $busquedaFecha) {
        $condicion = true;

        if ($busquedaCedula && strpos($dato['cedula'], $busquedaCedula) === false) {
            $condicion = false;
        }

        if ($busquedaConcepto) {
            // Determinar el tipo de registro
            if (isset($dato['id_nomina'])) {
                $concepto = 'Sueldo y Salario';
            } elseif (isset($dato['vacaciones_id'])) {
                $concepto = 'Vacaciones';
            } elseif (isset($dato['id_prestamos'])) {
                $concepto = 'Préstamo';
            }

            if (strpos($concepto, $busquedaConcepto) === false) {
                $condicion = false;
            }
        }

        if ($busquedaFecha && ((isset($dato['fecha']) && strpos($dato['fecha'], $busquedaFecha) === false) || (isset($dato['ini_vacaciones']) && strpos($dato['ini_vacaciones'], $busquedaFecha) === false))) {
            $condicion = false;
        }

        return $condicion;
    });
}

// Calcular el número total de elementos después de la búsqueda
$totalElementos = count($datosCombinados);

// Calcular el número total de páginas
$totalPaginas = ceil($totalElementos / $elementosPorPagina);

// Obtener la página actual
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

// Calcular el índice de inicio para la consulta
$inicio = ($paginaActual - 1) * $elementosPorPagina;

// Obtener los datos para la página actual
$datosPagina = array_slice($datosCombinados, $inicio, $elementosPorPagina);

// Preparar la respuesta en formato JSON
$respuesta = [
    'datos' => '',
    'totalPaginas' => $totalPaginas
];

try {
    foreach ($datosPagina as $dato) {
        $respuesta['datos'] .= '<tr>';

        if (isset($dato['vacaciones_id'])) {
            $respuesta['datos'] .= '<th scope="col">' . $dato['ini_vacaciones'] . '</th>';
        } else {
            $respuesta['datos'] .= '<th scope="col">' . $dato['fecha'] . '</th>';
        }

        // Determinar el tipo de registro
        if (isset($dato['id_nomina'])) {
            $respuesta['datos'] .= '<th scope="col"> Sueldo y Salario </th>';
            $tipo = 'nomina';
            $id = $dato['id_nomina'];
            $pdfUrl = 'PlantillaPDF/Reporte-de-sueldos-y-salarios.php?id=' . $id;
        } elseif (isset($dato['vacaciones_id'])) {
            $respuesta['datos'] .= '<th scope="col"> Vacaciones </th>';
            $tipo = 'vacaciones';
            $id = $dato['vacaciones_id'];
            $pdfUrl = 'PlantillaPDF/Vacaciones-y-utilidades.php?id=' . $id;
        } elseif (isset($dato['id_prestamos'])) {
            $respuesta['datos'] .= '<th scope="col"> Préstamo </th>';
            $tipo = 'prestamo';
            $id = $dato['id_prestamos'];
            $pdfUrl = 'PlantillaPDF/Prestamos-y-cuentas-por-pagar1.php?id=' . $id;
        }

        $respuesta['datos'] .= '<th scope="col">' . $dato['cedula'] . '</th>';
        $respuesta['datos'] .= '<th scope="col">' . $dato['nombre'] . '  ' . $dato['apellido'] . '</th>';
        
        // Verificar el rol del usuario para mostrar los botones
        if ($rol == 'Trabajador' || $rol == 'Administrador') {
            $respuesta['datos'] .= '<th>
                <a name="btn1" class="btn btn-outline-primary" href="' . $pdfUrl . '" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                    <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4"></path>
                    <path d="M5 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6"></path>
                    <path d="M17 18h2"></path>
                    <path d="M20 15h-3v6"></path>
                    <path d="M11 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z"></path>
                </svg>
                </a>
            </th>';
        } else {
            $respuesta['datos'] .= '<th>
                <a name="btn1" class="btn btn-outline-primary" href="' . $pdfUrl . '" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                    <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4"></path>
                    <path d="M5 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6"></path>
                    <path d="M17 18h2"></path>
                    <path d="M20 15h-3v6"></path>
                    <path d="M11 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z"></path>
                </svg>
            </a>
            <a name="btn2" class="btn btn-outline-danger" onclick="return confirmar('.$id.', ' .$tipo.')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1 1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                </svg>
            </a>
        </th>';
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