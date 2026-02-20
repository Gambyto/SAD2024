<?php
include_once '../CLASS/user_Original.php';

if (isset($_POST['cedula'])) {
    $cedula = $_POST['cedula'];
    $op = isset($_POST['op']) ? $_POST['op'] : null;

    $datosEmpleado = $Nomina->View_Active_Search_Nomina($cedula);

    switch ($op) {

        case 1:
            $prestamos       = ['id_prestamos' => null, 'descuento' => 0, 'aporte_semana' => 0];
            $cuentasPorPagar = ['id_cuentasp'  => null, 'descuento' => 0];

            // Usar Display_Prestamos_Aporte para obtener el aporte real de esta semana
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
} else {
    echo json_encode(['error' => 'Cédula no proporcionada.']);
}
?>