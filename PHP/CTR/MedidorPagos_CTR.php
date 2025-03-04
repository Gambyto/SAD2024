<?php
include_once '../CLASS/user_Original.php'; // Asegúrate de incluir la clase que contiene la función obtenerPagosNomina

$mes = $_POST['mes'] ?? date('n'); // Obtener el mes del POST, o el mes actual si no se proporciona
$anio = $_POST['año'] ?? date('Y'); // Puedes modificar esto para permitir seleccionar el año también

$pagos = $Nomina->obtenerPagosNomina($mes, $anio);
$totalPagado = array_sum($pagos);
$totalEmpleados = $Nomina->EmpleadosPagos($mes, $anio);
$totalEmpleados = $totalEmpleados['cantidad_empleados'] ?? 1; // Asegurarse de que no sea cero

$promedioPagos = $totalEmpleados > 0 ? number_format($totalPagado / $totalEmpleados, 2) : 0;

$response = [
    'pagos' => $pagos,
    'totalPagado' => number_format($totalPagado, 2),
    'promedioPagos' => $promedioPagos
];

echo json_encode($response);
?>