<?php
/**
 * PagarTodo_CTR.php
 * Procesa el pago masivo de nómina para los empleados seleccionados.
 * Recibe: JSON body con array "empleados"
 * Responde: JSON con resultado por empleado
 */
session_start();
include_once '../CLASS/user_Original.php';

header('Content-Type: application/json');

// Solo Gerencia o Administrador pueden ejecutar pagos masivos
if (!in_array($_SESSION['type'] ?? '', ['Gerencia', 'Administrador'])) {
    echo json_encode(['success' => false, 'message' => 'No tienes permisos para realizar esta acción.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (empty($input['empleados']) || !is_array($input['empleados'])) {
    echo json_encode(['success' => false, 'message' => 'No se recibieron empleados para procesar.']);
    exit;
}

$resultados   = [];
$exitosos     = 0;
$fallidos     = 0;
$fecha        = date('Y-m-d');

foreach ($input['empleados'] as $emp) {
    $cedula    = $emp['cedula']    ?? null;
    $sueldoSem = $emp['sueldoSem'] ?? 0;
    $neto      = $emp['neto']      ?? 0;
    $idCpp     = !empty($emp['idCpp'])  ? $emp['idCpp']  : null;
    $idPtm     = !empty($emp['idPtm'])  ? $emp['idPtm']  : null;
    $descConsumo  = floatval($emp['descConsumo']  ?? 0);
    $descPrestamo = floatval($emp['descPrestamo'] ?? 0);

    if (!$cedula) {
        $resultados[] = ['cedula' => '?', 'status' => 'error', 'msg' => 'Cédula vacía'];
        $fallidos++;
        continue;
    }

    // Verificar que no haya sido pagado ya esta semana
    if ($Nomina->validarPagoEmpleado($cedula)) {
        $resultados[] = [
            'cedula' => $cedula,
            'status' => 'omitido',
            'msg'    => 'Ya tiene pago registrado esta semana'
        ];
        $fallidos++;
        continue;
    }

    // Insertar registro en nomina (bono y comisiones en 0 para pago automatico)
    $ok = $Nomina->Create_Nomina($cedula, $idCpp, $idPtm, $sueldoSem, $neto, 0, 0);

    if ($ok) {
        // Aplicar descuentos si corresponden
        if ($idPtm && $descPrestamo > 0) {
            $Nomina->Discount_Prestamos($cedula, $descPrestamo);
            $data  = $Nomina->Display_Prestamos($cedula);
            $deuda = $data ? $data['monto_desc'] : 0;
            $Nomina->Insert_aporte($idPtm, $deuda, $descPrestamo, 'Sueldo', 'Pago automatico', $fecha);
        }
        if ($idCpp && $descConsumo > 0) {
            $Nomina->Discount_cuentas_por_pagar($cedula, $descConsumo);
        }

        $resultados[] = ['cedula' => $cedula, 'status' => 'ok',    'msg' => 'Pagado correctamente'];
        $exitosos++;
    } else {
        $resultados[] = ['cedula' => $cedula, 'status' => 'error', 'msg' => 'Error al insertar en nomina'];
        $fallidos++;
    }
}

echo json_encode([
    'success'    => true,
    'exitosos'   => $exitosos,
    'fallidos'   => $fallidos,
    'resultados' => $resultados,
]);