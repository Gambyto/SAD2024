<?php
include_once 'CLASS/user_Original.php';

$mes = isset($_GET['mes']) ? $_GET['mes'] : null;
$anio = isset($_GET['anio']) ? $_GET['anio'] : null;

$datosDiarios = $Nomina->TasaDolar('diario', $mes, $anio);
$fechasDiarias = array();
$valoresDiarios = array();

foreach ($datosDiarios as $dato) {
    $fechasDiarias[] = $dato['fecha'];
    $valoresDiarios[] = $dato['tasa_del_dia'];
}

echo json_encode(['fechas' => $fechasDiarias, 'valores' => $valoresDiarios]);
?>