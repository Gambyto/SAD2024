<?php
include_once '../CLASS/user_Original.php';

if (isset($_POST['cedula'])) {
    $cedula = $_POST['cedula'];
    $op = isset($_POST['op']) ? $_POST['op'] : null; // Asegúrate de que 'op' esté definido
    // Obtener datos del empleado
    $datosEmpleado = $Nomina->View_Active_Search_Nomina($cedula);
    
    switch ($op) { 
        case 1:
            
            // Inicializar arrays para los datos financieros
            $prestamos = ['id' => null, 'descuento' => 0];
            $cuentasPorPagar = ['id' => null, 'descuento' => 0];
        
            // Obtener datos de préstamos y cuentas por pagar
            if ($prestamoData = $Nomina->Display_Prestamos($cedula)) {
                $prestamos = $prestamoData; // Asignar datos si existen
            }
        
            if ($cuentaData = $Nomina->Display_cuentas_por_pagar($cedula)) {
                $cuentasPorPagar = $cuentaData; // Asignar datos si existen
            }

            // Combinar todos los datos en un solo array
            if ($datosEmpleado) {
                $datosEmpleado['prestamos'] = $prestamos;
                $datosEmpleado['consumo'] = $cuentasPorPagar;
                echo json_encode($datosEmpleado);
            } else {
                exit;
            }
            break;

        // Puedes agregar más casos aquí para otras operaciones
        case 3:
            $sueldobs = $datosEmpleado['sueldo'] * $_POST['tasa']; 
            $sueldoD = $datosEmpleado['sueldo'] ;
            // Combinar todos los datos en un solo array
            if ($datosEmpleado) {
                $datosEmpleado['sueldobs'] = $sueldobs;
                $datosEmpleado['sueldod'] = $sueldoD;
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