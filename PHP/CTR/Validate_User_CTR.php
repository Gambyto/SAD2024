<?php
include_once '../CLASS/conexion_Original.php';
include_once '../CLASS/user_Original.php';
session_start();

$username =  $_POST['username'] ?? 0;
$pass = $_POST['password'] ?? 0;

if (!empty($username) && !empty($pass)){
    if (!$User->Existencia($username)){
        $message = 'Error: el usuario no existe';
        ob_start();
        include_once '../../View/Components/alerts.php';
        $html = ob_get_clean();
        $response = array('message' => $message, 'html' => $html);
        echo json_encode($response);
        exit;
    }else{
        
        if ($User->verificar($username,$pass)) { 
            $datos = $User->ReturnDataUser($username,$pass);
            $_SESSION['user'] = $username;
            $_SESSION['clave'] = $datos[0]['clave'];
            $_SESSION['type'] = $datos[0]['type'];
            $_SESSION['nombre'] = $datos[0]['nombre'].' '.$datos[0]['apellido'];
            $_SESSION['id'] = $datos[0]['cedula'];
            
        switch ($datos[0]['type']) {
            case 'Gerencia':
                $response = array('redirect' => 'http://localhost/SAD_2024/View/Dashboard.php');
                break;
                case 'Administrador':
                    $response = array('redirect' => 'http://localhost/SAD_2024/View/Dashboard.php');
                    break;
                    case 'Trabajador':
                        $response = array('redirect' => 'http://localhost/SAD_2024/View/Recibos.php');
                        break;
                        
                        default:
                        $response = array('redirect' => 'http://localhost/SAD_2024/index.php');
                        break;
                    }
                    echo json_encode($response);
                    exit;
                }
             else {
                $message = 'Error: usuario o contraseña incorrectos';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                $response = array('message' => $message, 'html' => $html);
                echo json_encode($response);
                exit;
            }}

}else{
    $message = 'Error: rellene los campos';
    ob_start();
    include_once '../../View/Components/alerts.php';
    $html = ob_get_clean();
    $response = array('message' => $message, 'html' => $html);
    echo json_encode($response);
    exit;
}
?>