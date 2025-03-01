<?php
include_once '../CLASS/user_Original.php';

if (isset($_POST['cedula'])) {
    $cedula = $_POST['cedula'];
    $datosEmpleado = $Nomina->Display_Prestamos($cedula); 

    if ($datosEmpleado) {
        echo json_encode($datosEmpleado);
    } else {
        echo json_encode(['error' => 'Empleado no encontrado.']);
    }
} else {
    echo json_encode(['error' => 'Cédula no proporcionada.']);
}
?>