<?php
/**
 * Get_Empleados_Nomina.php  v2
 * - Excluye vendedores
 * - Marca empleados cuyo descuento supera el sueldo semanal
 */
include_once '../CLASS/user_Original.php';

/* Cargos excluidos del pago automático */
$cargosExcluidos = ['vendedor', 'vendedora', 'vendor'];

$empleados = $Empleado->View();
$resultado = [];

foreach ($empleados as $emp) {
    $cedula = $emp['cedula'];

    /* Excluir vendedores */
    $cargoLower = strtolower(trim($emp['cargo'] ?? ''));
    $esVendedor = false;
    foreach ($cargosExcluidos as $ex) {
        if (strpos($cargoLower, $ex) !== false) {
            $esVendedor = true;
            break;
        }
    }
    if ($esVendedor) continue;

    /* Descuentos vigentes */
    $prestamo  = $Nomina->Display_Prestamos($cedula);
    $consumo   = $Nomina->Display_cuentas_por_pagar($cedula);

    $descPrestamo = $prestamo ? floatval($prestamo['descuento']) : 0;
    $descConsumo  = $consumo  ? floatval($consumo['descuento'])  : 0;
    $idPtm        = $prestamo ? $prestamo['id_prestamos']        : null;
    $idCpp        = $consumo  ? $consumo['id_cuentasp']          : null;

    $sueldo    = floatval($emp['sueldo']);
    $sueldoSem = round($sueldo / 4, 2);
    $descTotal = $descPrestamo + $descConsumo;

    /* Validación: descuento no puede superar el sueldo semanal */
    $descExcede = $descTotal > $sueldoSem;
    $neto       = $descExcede ? 0 : round($sueldoSem - $descTotal, 2);

    $resultado[] = [
        'cedula'       => $cedula,
        'nombre'       => $emp['nombre'],
        'apellido'     => $emp['apellido'],
        'cargo'        => $emp['cargo'],
        'departamento' => $emp['departamento'],
        'sueldo'       => $sueldo,
        'sueldoSem'    => $sueldoSem,
        'descPrestamo' => $descPrestamo,
        'descConsumo'  => $descConsumo,
        'idPtm'        => $idPtm,
        'idCpp'        => $idCpp,
        'neto'         => $neto,
        'yaPagado'     => $Nomina->validarPagoEmpleado($cedula),
        'descExcede'   => $descExcede,   // el front usa esto para bloquear el checkbox
    ];
}

header('Content-Type: application/json');
echo json_encode($resultado);