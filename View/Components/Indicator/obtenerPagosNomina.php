<?php
include_once '../../../PHP/CLASS/conexion_Original.php';
include_once '../../../PHP/CLASS/user_Original.php';

$mes = $_POST['mes'];
$anio = $_POST['anio'];

// Obtener los pagos de la nómina para el mes seleccionado
$pagos = $Nomina->obtenerPagosNomina($mes, $anio);

// Devolver los datos de los pagos en formato JSON
echo json_encode($pagos);
?>