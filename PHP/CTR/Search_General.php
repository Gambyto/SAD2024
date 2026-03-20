<?php
session_start();
include_once '../CLASS/user_Original.php';

$op = isset($_POST['op']) ? (int)$_POST['op'] : null;

/* ═══════════════════════════════════════════════════
   op: 2  →  NO necesita cédula (lista sin pago semana)
   op: 4  →  NO necesita cédula (empleados para ISLR masivo)
   op: 5  →  NO necesita cédula (aportes ISLR por mes)
   op: 1  →  Requiere cédula (búsqueda individual nómina)
   op: 3  →  Requiere cédula (sueldo en Bs para ISLR)
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

if ($op === 4) {

    /* ─────────────────────────────────────────────────────────────
       Todos los empleados activos + flag yaPagado (ISLR este mes).
       Usado por Modal-TotalizarISLR.php para el pago masivo.
    ───────────────────────────────────────────────────────────── */
    $tasaBCV = isset($_SESSION['TasaBCV']) ? floatval($_SESSION['TasaBCV']) : 1;
    $db      = $Nomina->connect_db();

    $query = "SELECT
                e.cedula,
                e.nombre,
                e.apellido,
                e.cargo,
                e.sueldo,
                ROUND(e.sueldo * $tasaBCV, 2) AS sueldobs,
                CASE WHEN EXISTS (
                    SELECT 1 FROM islr i
                    WHERE i.cedula_FK = e.cedula
                      AND MONTH(i.fecha) = MONTH(CURDATE())
                      AND YEAR(i.fecha)  = YEAR(CURDATE())
                ) THEN 1 ELSE 0 END AS yaPagado
              FROM empleados e
              WHERE e.estado = 1
              ORDER BY e.apellido ASC, e.nombre ASC";

    $result = $db->query($query);
    $data   = [];
    while ($row = $result->fetch_assoc()) {
        $row['yaPagado'] = (bool) $row['yaPagado'];
        $data[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

if ($op === 5) {

    /* ─────────────────────────────────────────────────────────────
       Aportes ISLR filtrados por mes (formato YYYY-MM).
       Usado por Tablas-ISLR.php para la paginación JS.
    ───────────────────────────────────────────────────────────── */
    $mes = isset($_POST['mes']) ? trim($_POST['mes']) : date('Y-m');
    if (!preg_match('/^\d{4}-\d{2}$/', $mes)) {
        $mes = date('Y-m');
    }

    $db      = $Nomina->connect_db();
    $mesSafe = $db->real_escape_string($mes);

    $query = "SELECT
                e.nombre,
                e.apellido,
                e.cedula,
                i.aporte,
                i.monto,
                i.fecha
              FROM islr i
              INNER JOIN empleados e ON i.cedula_FK = e.cedula
              WHERE DATE_FORMAT(i.fecha, '%Y-%m') = '$mesSafe'
              ORDER BY i.fecha DESC, e.apellido ASC";

    $result = $db->query($query);
    $data   = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($data);
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