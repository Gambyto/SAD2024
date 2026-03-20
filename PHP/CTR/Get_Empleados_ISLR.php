<?php
/**
 * Get_Empleados_ISLR.php
 * Devuelve todos los empleados activos con el flag yaPagado
 * (indica si ya tienen aporte ISLR registrado en el mes en curso).
 * Usado por Modal-TotalizarISLR.php
 */
ob_start();
session_start();
include_once '../CLASS/user_Original.php';
ob_end_clean(); // Descartar cualquier output accidental (notices, warnings, espacios)

$tasaBCV = isset($_SESSION['TasaBCV']) ? floatval($_SESSION['TasaBCV']) : 1;

$empleados = $Empleado->View();
$resultado = [];

foreach ($empleados as $emp) {
    $cedula = $emp['cedula'];
    $sueldo = floatval($emp['sueldo']);

    /* ¿Ya tiene aporte ISLR este mes? */
    $yaPagado = (bool) $Nomina->Display_ISLR($cedula);

    $resultado[] = [
        'cedula'    => $cedula,
        'nombre'    => $emp['nombre'],
        'apellido'  => $emp['apellido'],
        'cargo'     => $emp['cargo'],
        'sueldo'    => $sueldo,
        'sueldobs'  => round($sueldo * $tasaBCV, 2),
        'yaPagado'  => $yaPagado,
    ];
}

header('Content-Type: application/json');
echo json_encode($resultado);