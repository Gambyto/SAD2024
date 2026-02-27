<?php
/**
 * Tablas-Historico-Fideicomiso.php
 * Devuelve JSON { datos: HTML, totalPaginas, totalRegistros }
 * Filas agrupadas por mes — mismo patrón que Tablas-Historico-Nomina.php
 * Ubicación: View/Components/Tables/Tablas-Historico-Fideicomiso.php
 */
include_once '../../../PHP/CLASS/conexion_Original.php';
include_once '../../../PHP/CLASS/user_Original.php';

$elementosPorPagina = 8;
$paginaActual       = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;

$datos          = $Nomina->View_Fideicomiso_Historial();   // agrupado por mes
$totalRegistros = count($datos);
$totalPaginas   = max(1, ceil($totalRegistros / $elementosPorPagina));
$inicio         = ($paginaActual - 1) * $elementosPorPagina;
$datosPagina    = array_slice($datos, $inicio, $elementosPorPagina);

ob_start();

if (empty($datosPagina)) {
    echo '<tr><td colspan="6" class="text-muted py-4 text-center">No hay registros de fideicomiso.</td></tr>';
} else {
    $numero = $inicio + 1;
    foreach ($datosPagina as $fila) {
        $mes        = $fila['mes'];                                         // YYYY-MM  → para la URL
        $rango      = $fila['fecha_inicio'] . ' – ' . $fila['fecha_fin']; // legible
        $empleados  = (int)$fila['total_empleados'];
        $totalMonto = number_format((float)$fila['total_monto'],   2);
        $totalAntic = number_format((float)$fila['total_anticipo'], 2);

        // Nombre legible del mes (ej: "Marzo 2025")
        [$anio, $numMes] = explode('-', $mes);
        $meses = ['', 'Enero','Febrero','Marzo','Abril','Mayo','Junio',
                       'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        $nombreMes = $meses[(int)$numMes] . ' ' . $anio;

        echo '<tr>';
        echo '<td>' . $numero++ . '</td>';
        echo '<td>
                <strong>' . $nombreMes . '</strong><br>
                <small class="text-muted">' . $rango . '</small>
              </td>';
        echo '<td><span class="badge bg-secondary">' . $empleados . ' emp.</span></td>';
        echo '<td class="text-end">$ ' . $totalMonto . '</td>';
        echo '<td class="text-end">$ ' . $totalAntic . '</td>';
        echo '<td class="text-center">
                <button class="btn btn-sm btn-outline-danger" title="Imprimir PDF"
                    onclick="window.open(\'PlantillaPDF/Fideicomiso-mes.php?mes=' . urlencode($mes) . '\', \'_blank\')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                         width="16" height="16" stroke-width="2">
                        <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                        <path d="M5 12v-7a2 2 0 0 1 2-2h7l5 5v4"/>
                        <path d="M5 18h1.5a1.5 1.5 0 0 0 0-3h-1.5v6"/>
                        <path d="M17 18h2"/><path d="M20 15h-3v6"/>
                        <path d="M11 15v6h1a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2h-1z"/>
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