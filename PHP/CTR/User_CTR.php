<?php
include_once '../CLASS/user_Original.php';

if (isset($_POST['cedula'])) {
    $cedula = $_POST['cedula'];
    $datosEmpleado = $User ->Get_User($cedula);

    if ($datosEmpleado) {
        $datosEmpleado['op'] = 10; // Agrega el valor de op
        echo json_encode($datosEmpleado);
        exit;
    } else {
        $datosEmpleado = $Nomina->View_Active_Search_Nomina($cedula);

        if ($datosEmpleado) {
            $datosEmpleado['op'] = 9; // Agrega el valor de op
            echo json_encode($datosEmpleado);
        } else {
            echo json_encode(['error' => 'Empleado no encontrado.', 'op' => 9]); // Agrega el valor de op
        }
    }
} else {
    echo json_encode(['error' => 'Cédula no proporcionada.', 'op' => 9]); // Agrega el valor de op
}
?>