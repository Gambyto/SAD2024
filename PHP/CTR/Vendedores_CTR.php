<?php
include_once '../CLASS/user_Original.php';

// Capturamos y limpiamos
$mes = $_POST['mes'] ?? date('n');
$anio = $_POST['anio'] ?? date('Y');

// Filtro de depuración para tu log
$debug_info = "Filtrando Vendedores por: Mes $mes - Año $anio";

$vendedores = $Nomina->Vendedores_Nomina($mes, $anio);


$labels = [];
$comisiones = [];

foreach ($vendedores as $v) {
    $labels[] = $v['vendedor_nombre'];
    $comisiones[] = (float)$v['t_comiciones'];
}

$total = array_sum($comisiones);
$max_data = $Nomina->MAX_Vendedores($comisiones);

// Respuesta JSON con filtros de depuración incorporados
echo json_encode([
    'filtro_aplicado' => $debug_info,
    'debug_recibido' => [ 'mes' => $mes, 'anio' => $anio ], // Confirmación de entrada
    'debug_conteo' => count($vendedores),                 // Cuántos registros encontró
    'labels' => $labels,
    'comisiones' => $comisiones,
    'totalComisiones' => number_format($total, 2),
    'comisionMax' => number_format($max_data['t_comiciones'] ?? 0, 2),
    'vendedorMax' => $max_data['vendedor_nombre'] ?? 'N/A'
]);

?>
