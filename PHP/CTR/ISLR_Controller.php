<?php
include_once '../CLASS/user_Original.php';
// ISLR_Controller.php

// Verificamos que sea una petición AJAX y que tenga una acción
if (isset($_GET['action'])) {
    
    $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');
    header('Content-Type: application/json');

    try {
        // Acción para el gráfico y tarjetas resumen
        if ($_GET['action'] === 'islr_data') {
            $rows = $Nomina->ISLR_GrapByAnio($anio);
            // Aseguramos que siempre devuelva un array de montos (12 meses)
            $values = array_column($rows, 'monto');
            echo json_encode(['values' => array_map('floatval', $values)]);
            exit;
        }

        // Acción para la tabla de detalle por empleado
        if ($_GET['action'] === 'islr_detail') {
            $empleados = $Nomina->ISLR_Detail($anio);
            echo json_encode(['empleados' => $empleados]);
            exit;
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}
?>