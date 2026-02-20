<?php
// Sin session_start() aquí, la sesión ya viene iniciada desde Sueldos.php
include_once '../../../PHP/CLASS/conexion_Original.php';
include_once '../../../PHP/CLASS/user_Original.php';

$elementosPorPagina = 8;
$paginaActual       = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;

$datos          = $Nomina->View_Nomina_Historial();
$totalRegistros = count($datos);
$totalPaginas   = max(1, ceil($totalRegistros / $elementosPorPagina));
$inicio         = ($paginaActual - 1) * $elementosPorPagina;
$datosPagina    = array_slice($datos, $inicio, $elementosPorPagina);

ob_start();

if (empty($datosPagina)) {
    echo '<tr><td colspan="6" class="text-muted py-4">No hay nóminas registradas.</td></tr>';
} else {
    $numero = $inicio + 1;
    foreach ($datosPagina as $fila) {
        // Rango legible: "10/02/2025 – 14/02/2025"
        $fechaInicio  = $fila['fecha_inicio']; // YYYY-MM-DD para la URL
        $fechaFin     = $fila['fecha_fin'];
        $semana       = (int)$fila['semana'];
        $anio         = (int)$fila['anio'];
        $rangoMostrar = date('d/m/Y', strtotime($fechaInicio))
                      . ' – '
                      . date('d/m/Y', strtotime($fechaFin));

        $empleados = (int)$fila['total_empleados'];
        $netoUSD   = number_format((float)$fila['total_neto_usd'], 2);
        $netoBs    = number_format((float)$fila['total_neto_bs'],  2);

        echo '<tr>';
        echo '<td>' . $numero++ . '</td>';
        echo '<td>
                <strong>Semana ' . $semana . ' / ' . $anio . '</strong><br>
                <small class="text-muted">' . $rangoMostrar . '</small>
              </td>';
        echo '<td><span class="badge bg-secondary">' . $empleados . ' emp.</span></td>';
        echo '<td>$ ' . $netoUSD . '</td>';
        echo '<td>Bs ' . $netoBs . '</td>';
        echo '<td>
                <button href="PlantillaPDF/Nomina-general-fecha-SYS.php?fecha=' . urlencode($fecha) . '"
                   target="_blank"
                   class="btn btn-outline-primary"
                   title="Imprimir PDF"
                   onclick="window.open(\'PlantillaPDF/Nomina-general-fecha-SYS.php?semana=' . $semana . '&anio=' . $anio . '\', \'_blank\')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
                        <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                        <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4"></path>
                        <path d="M5 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6"></path>
                        <path d="M17 18h2"></path>
                        <path d="M20 15h-3v6"></path>
                        <path d="M11 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z"></path>
                    </svg>
                </button>
              </td>';
        echo '</tr>';
    }
}

$html = ob_get_clean();

while (ob_get_level()) ob_end_clean();

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'datos'          => $html,
    'totalPaginas'   => $totalPaginas,
    'totalRegistros' => $totalRegistros,
]);