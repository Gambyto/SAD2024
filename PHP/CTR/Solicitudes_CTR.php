<?php
include_once '../CLASS/user_Original.php';


$id = $_POST['id'];
$estado = $_POST['accion'];

$solicitud = $Nomina->Get_Solicitud($id);
if ($solicitud != null) {
if ($estado == 'Aprovado'){
    $descuento = $solicitud[0]['descuento'];
    $monto = $solicitud[0]['monto'];
    $cuotas = $solicitud[0]['cuotas'];
    $cedula = $solicitud[0]['cedula_FK'];
    $fecha = ($solicitud[0]['f_solicitud'] >= date('Y-m-d')) ? $solicitud[0]['f_solicitud'] : date('Y-m-d');
    $limit = $Nomina->CalcularFechaLimite($fecha, $cuotas);
    $concepto = $solicitud[0]['concepto'];

    if ($Nomina->Create_Prestamos($descuento, $monto, $cuotas, $concepto, $cedula, $fecha, $limit, $id)){
        $Nomina->Update_Solicitud($fecha, $id, $estado);

        $message = 'Prestamo añadido con exito';
        ob_start();
        include_once '../../View/Components/True_alerts.php';
        $html = ob_get_clean();
        $response = array('message' => $message, 'html' => $html);
        echo json_encode($response);
        exit;
    }else{
        $message = 'Error: Algo salio mal';
        ob_start();
        include_once '../../View/Components/alerts.php';
        $html = ob_get_clean();
        $response = array('message' => $message, 'html' => $html);
        echo json_encode($response);
        exit;
    }
}else{
    $fecha = ($solicitud[0]['f_solicitud'] >= date('Y-m-d')) ? $solicitud[0]['f_solicitud'] : date('Y-m-d');

    if($Nomina->Update_Solicitud($fecha, $id, $estado)){
        $message = 'Solicitud actualizada con exito';
        ob_start();
        include_once '../../View/Components/True_alerts.php';
        $html = ob_get_clean();
        $response = array('message' => $message, 'html' => $html);
        echo json_encode($response);
        exit;
    }else{
        $message = 'Error: Algo salio mal';
        ob_start();
        include_once '../../View/Components/alerts.php';
        $html = ob_get_clean();
        $response = array('message' => $message, 'html' => $html);
        echo json_encode($response);
        exit;        
    }
}}else{
    $message = 'Error: No se podido tomar la solicitud';
    ob_start();
    include_once '../../View/Components/alerts.php';
    $html = ob_get_clean();
    $response = array('message' => $message, 'html' => $html);
    echo json_encode($response);
    exit;     
}
?>