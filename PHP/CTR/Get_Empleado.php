<?php
include_once '../CLASS/user_Original.php';

if (isset($_GET['cedula'])) {
    $cedula = $_GET['cedula'];
    $datosEmpleado = $Empleado->get_DNI($cedula); 

    if ($datosEmpleado) {
        echo json_encode($datosEmpleado);
    } else {
        // Cambia esto para que devuelva un JSON válido
        echo json_encode(['error' => 'Empleado no encontrado.']);
    }
} else {
    echo json_encode(['error' => 'Cédula no proporcionada.']);
}
?>