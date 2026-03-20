<?php
/**
 * TotalizarISLR_CTR.php
 * Recibe un JSON con la lista de empleados seleccionados y registra
 * el aporte ISLR de cada uno en la base de datos.
 *
 * Espera (POST, application/json):
 * {
 *   "empleados": [
 *     {
 *       "cedula"  : "12345678",
 *       "sueldo"  : 500.00,
 *       "aporte"  : 2,          // porcentaje (2 o 3)
 *       "monto"   : 10.00       // monto calculado en Bs
 *     },
 *     ...
 *   ]
 * }
 *
 * Responde:
 * {
 *   "exitosos" : 3,
 *   "fallidos"  : 1,
 *   "resultados": [
 *     { "cedula": "...", "status": "ok" | "error" | "ya_pagado", "msg": "..." }
 *   ]
 * }
 */

session_start();
include_once '../CLASS/user_Original.php';

header('Content-Type: application/json');

/* ── Leer body JSON ── */
$raw  = file_get_contents('php://input');
$body = json_decode($raw, true);

if (empty($body['empleados']) || !is_array($body['empleados'])) {
    echo json_encode(['error' => 'No se recibieron empleados.']);
    exit;
}

$exitosos  = 0;
$fallidos  = 0;
$resultados = [];

foreach ($body['empleados'] as $emp) {
    $cedula = isset($emp['cedula']) ? trim($emp['cedula']) : '';
    $aporte = isset($emp['aporte']) ? floatval($emp['aporte']) : 0;
    $monto  = isset($emp['monto'])  ? floatval($emp['monto'])  : 0;

    /* Validaciones básicas */
    if (empty($cedula) || $aporte <= 0 || $monto <= 0) {
        $fallidos++;
        $resultados[] = ['cedula' => $cedula, 'status' => 'error', 'msg' => 'Datos incompletos o inválidos.'];
        continue;
    }

    /* Verificar si ya tiene aporte ISLR este mes */
    $yaExiste = $Nomina->Display_ISLR($cedula);
    if ($yaExiste) {
        $fallidos++;
        $resultados[] = ['cedula' => $cedula, 'status' => 'ya_pagado', 'msg' => 'Ya posee un aporte registrado este mes.'];
        continue;
    }

    /* Registrar */
    if ($Nomina->Create_ISLR($aporte, $monto, $cedula)) {
        $exitosos++;
        $resultados[] = ['cedula' => $cedula, 'status' => 'ok', 'msg' => 'Registrado correctamente.'];
    } else {
        $fallidos++;
        $resultados[] = ['cedula' => $cedula, 'status' => 'error', 'msg' => 'Error al guardar en la base de datos.'];
    }
}

echo json_encode([
    'exitosos'   => $exitosos,
    'fallidos'   => $fallidos,
    'resultados' => $resultados,
]);
exit;