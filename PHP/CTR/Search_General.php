<?php
include_once '../CLASS/user_Original.php';

$op = isset($_POST['op']) ? (int)$_POST['op'] : null;

/* ═══════════════════════════════════════════════════
   op: 2  →  NO necesita cédula (lista sin pago)
   op: 1  →  Requiere cédula (búsqueda individual)
   op: 3  →  Requiere cédula (sueldo en Bs)
═══════════════════════════════════════════════════ */

if ($op === 2) {

    /* ─────────────────────────────────────────────────────────────
       Empleados activos que NO tienen registro en nomina
       para la semana ISO actual (WEEK(...,1) + YEAR).
       Se excluyen también los que tienen un pago en la semana
       aunque el registro esté en estado 0 (anulado), por eso
       se filtra estado = 1.
    ───────────────────────────────────────────────────────────── */
    $empleadosSinPago = $Nomina->View_Empleados_Sin_Pago_Semana();
    echo json_encode($empleadosSinPago);
    exit;
}

/* ─── Operaciones que sí requieren cédula ─── */
if (!isset($_POST['cedula'])) {
    echo json_encode(['error' => 'Cédula no proporcionada.']);
    exit;
}

$cedula = $_POST['cedula'];
$datosEmpleado = $Nomina->View_Active_Search_Nomina($cedula);

switch ($op) {

    case 1:
        $prestamos       = ['id_prestamos' => null, 'descuento' => 0, 'aporte_semana' => 0];
        $cuentasPorPagar = ['id_cuentasp'  => null, 'descuento' => 0];

        if ($prestamoData = $Nomina->Display_Prestamos_Aporte($cedula)) {
            $prestamos = $prestamoData;
        }

        if ($cuentaData = $Nomina->Display_cuentas_por_pagar($cedula)) {
            $cuentasPorPagar = $cuentaData;
        }

        if ($datosEmpleado) {
            $datosEmpleado['prestamos'] = $prestamos;
            $datosEmpleado['consumo']   = $cuentasPorPagar;
            echo json_encode($datosEmpleado);
        } else {
            exit;
        }
        break;

    case 3:
        $sueldobs = $datosEmpleado['sueldo'] * $_POST['tasa'];
        $sueldoD  = $datosEmpleado['sueldo'];

        if ($datosEmpleado) {
            $datosEmpleado['sueldobs'] = $sueldobs;
            $datosEmpleado['sueldod']  = $sueldoD;
            echo json_encode($datosEmpleado);
        } else {
            echo json_encode(['error' => 'Empleado no encontrado.']);
        }
        break;

    default:
        echo json_encode(['error' => 'Operación no válida.']);
        break;
}
?>