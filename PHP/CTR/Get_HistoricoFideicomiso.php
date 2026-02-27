<?php

session_start();
include_once '../CLASS/user_Original.php';

header('Content-Type: application/json');

$fecha = isset($_POST['fecha']) && trim($_POST['fecha']) !== '' ? trim($_POST['fecha']) : null;

$datos = $fecha
    ? $Nomina->Search_Fide($fecha)
    : $Nomina->View_Fideicomiso();

echo json_encode($datos);
?>