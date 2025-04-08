<?php
include_once '../CLASS/user_Original.php';

if (isset($_POST['cedula'])) {
    $cedula = $_POST['cedula'];
    $datosEmpleado = $Nomina->Display_Prestamos($cedula); 

    if ($datosEmpleado) {
        echo json_encode($datosEmpleado);
    } else {
        $message = 'Error: Empleado no encontrado o no tiene préstamos registrados.';
        ob_start();
        include_once '../../View/Components/alerts.php';
        $html = ob_get_clean();
        $response = array('message' => $message, 'html' => $html);
        echo json_encode($response);
        exit;
    } 
} else {
    echo json_encode(['error' => 'Cédula no proporcionada.']);
}
?> 