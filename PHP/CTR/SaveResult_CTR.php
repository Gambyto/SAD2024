<?php

session_start();
include_once '../CLASS/user_Original.php';

function getPostValue($key, $default = null) {
    return isset($_POST[$key]) && trim($_POST[$key]) !== '' ? $_POST[$key] : $default;
}

// Variables de llegada
$op = $_POST['op'];

if (empty($_POST['cedula'])) {
    $message = 'Error: debe ingresar la cedula del empleado';
    ob_start();
    include_once '../../View/Components/alerts.php';
    $html = ob_get_clean();
    echo json_encode(['message' => $message, 'html' => $html]);
    exit;
} else {
    $cedula = $_POST['cedula'];

    switch ($op) {

        // ============================================================
        // CASE 1: Registrar nómina semanal
        // ============================================================
        case '1':
            if (empty($cedula)) {
                $message = 'Error: Complete todos los campos';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            if ($Nomina->validarPagoEmpleado($cedula)) {
                $message = 'Error: Ya se ha emitido un pago a este trabajador esta semana';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            $id_cpp      = getPostValue('id_consumo', 'null');
            $id_ptm      = getPostValue('id_prestamo', 'null');
            $sueldoS     = getPostValue('sueldoS', 0);
            $netoDiv     = getPostValue('Netodiv', 'null');
            $bono        = getPostValue('bono1', 0);
            $comisiones  = getPostValue('comision1', 0);
            $descConsumo = getPostValue('consumo', 0);
            $descPrestamo= getPostValue('prestamo', 0); // aporte real de esta semana
            $fecha       = date('Y-m-d');

            if ($netoDiv == 'null') {
                $message = 'Error: No se ha calculado el sueldo';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            if ($Nomina->Create_Nomina($cedula, $id_cpp, $id_ptm, $sueldoS, $netoDiv, $bono, $comisiones)) {

                // --- Descontar préstamo financiero si existe ---
                if (!empty($id_ptm) && $id_ptm !== 'null') {
                    // Discount_Prestamos ahora recibe id_prestamo y el aporte real
                    $Nomina->Discount_Prestamos($id_ptm, $descPrestamo);

                    // Obtener la deuda actualizada después del descuento
                    $dataPtm = $Nomina->Display_Prestamos($cedula);
                    $deuda   = $dataPtm ? $dataPtm['monto_desc'] : 0;

                    // Registrar el aporte en cuentas_por_pagar2
                    $Nomina->Insert_aporte($id_ptm, $deuda, $descPrestamo, 'Sueldo', 'No aplica', $fecha);
                }

                // --- Descontar consumo (cuentas por pagar) si existe ---
                if (!empty($id_cpp) && $id_cpp !== 'null') {
                    // Discount_cuentas_por_pagar ahora recibe id_cuentasp y el aporte real
                    $Nomina->Discount_cuentas_por_pagar($id_cpp, $descConsumo);
                }

                $message = 'Nomina registrada';
                ob_start();
                include_once '../../View/Components/True_alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);

            } else {
                $message = 'Error: Algo salio mal';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }
            break;

        // ============================================================
        // CASE 2: Insertar vacaciones
        // ============================================================
        case '2':
            if (empty($cedula)) {
                $message = 'Error: Complete todos los campos';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            $Dvacaciones   = getPostValue('Dvacaciones', 'null');
            $vacacionesini = getPostValue('Vacacionesini', 'null');
            $vacacionesfin = getPostValue('Vacacionesfin', 'null');
            $laboral       = getPostValue('inilaboral', 'null');
            $findesemana   = getPostValue('finsemana', 0);
            $sueldod       = getPostValue('sueldoD', 0);
            $monto         = getPostValue('monto', 0);
            $ince          = getPostValue('ince', 0);
            $feriado       = getPostValue('feriados', 0);
            $pendiente     = getPostValue('pendientes', 0);
            $utilidad      = getPostValue('utilidades', 0);
            $Tservicio     = getPostValue('servicio', 0);

            if (empty($monto)) {
                $message = 'Error: No se ha calculado el monto de las vacaciones';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            if ($Nomina->Vacation_Insert($Dvacaciones, $Tservicio, $utilidad, $vacacionesini,
                $vacacionesfin, $laboral, $findesemana, $feriado, $pendiente, $sueldod, $cedula, $monto, $ince)) {
                $message = 'Vacaciones cargadas con exito';
                ob_start();
                include_once '../../View/Components/True_alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
            } else {
                $message = 'Error: Algo salio mal';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }
            break;

        // ============================================================
        // CASE 3: Insertar fideicomiso
        // ============================================================
        case '3':
            if (empty($cedula)) {
                $message = 'Error: Complete todos los campos';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            if ($Nomina->validaraportetrimestral($cedula)) {
                $message = 'Error: Ya se ha emitido un aporte a este trabajador este trimestre';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            $Tservicio        = getPostValue('Tservicio', 0);
            $Tutilidad        = getPostValue('Tuitlidad', 0);
            $alicuotaU        = getPostValue('alicuotaU', 0);
            $bonoVacacional   = getPostValue('bonoVaca', 0);
            $alicuotaBV       = getPostValue('alicuotaBV', 0);
            $Sintegral        = getPostValue('Sintegral', 0);
            $Sintegral_diario = getPostValue('Dintegral', 0);
            $antiguedad       = getPostValue('antiguedad', 0);
            $Dvacaciones      = getPostValue('Dvacaciones', 0);
            $Tdias            = getPostValue('Tdias', 0);
            $fideicomiso      = getPostValue('fideicomiso1', 0);
            $anticipo         = getPostValue('anticipo1', 0);

            if ($Nomina->Insert_Fide($cedula, $Tservicio, $Tutilidad, $bonoVacacional,
                $alicuotaU, $alicuotaBV, $Sintegral, $Sintegral_diario,
                $antiguedad, $Dvacaciones, $Tdias, $anticipo, $fideicomiso)) {
                $message = 'Fideicomiso registrado';
                ob_start();
                include_once '../../View/Components/True_alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
            } else {
                $message = 'Error: Algo salio mal';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }
            break;

        // ============================================================
        // CASE 4: Insertar aporte ISLR
        // ============================================================
        case '4':
            if (empty($cedula)) {
                $message = 'Error: Complete todos los campos';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            $aporte = getPostValue('reten', 0);
            $monto  = getPostValue('aporte1', 0);

            $islrExistente = $Nomina->Display_ISLR($cedula);
            if ($islrExistente) {
                $message = 'Error: Ya se ha registrado un aporte ISLR para este trabajador en el mes en curso';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            if ($Nomina->Create_ISLR($aporte, $monto, $cedula)) {
                $message = 'Aporte ingresado';
                ob_start();
                include_once '../../View/Components/True_alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
            } else {
                $message = 'Error: Algo salio mal';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }
            break;

        // ============================================================
        // CASE 6: Pago manual de cuota de préstamo
        // Corregido: Discount_Prestamos ahora usa id_prestamo, no cédula
        // ============================================================
        case '6':
            if (empty($cedula)) {
                $message = 'Error: Complete todos los campos';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            $aporte    = getPostValue('descuento', 0);
            $monto_desc= getPostValue('monto_desc', 0);

            if ($aporte <= $monto_desc) {
                $id         = getPostValue('idp', 0);       // id_prestamos
                $parcial    = getPostValue('parcial', 0);   // deuda actualizada
                $tpago      = getPostValue('tpago', 0);
                $references = getPostValue('referencia', 'No aplica');
                $fecha      = date('Y-m-d');

                if ($Nomina->Insert_aporte($id, $parcial, $aporte, $tpago, $references, $fecha)) {
                    // CORREGIDO: pasa id_prestamo y aporte (no cédula y descuento fijo)
                    $Nomina->Discount_Prestamos($id, $aporte);

                    $message = 'Aporte ingresado';
                    ob_start();
                    include_once '../../View/Components/True_alerts.php';
                    $html = ob_get_clean();
                    echo json_encode(['message' => $message, 'html' => $html]);
                    exit;
                } else {
                    $message = 'Error: Algo salio mal';
                    ob_start();
                    include_once '../../View/Components/alerts.php';
                    $html = ob_get_clean();
                    echo json_encode(['message' => $message, 'html' => $html]);
                    exit;
                }
            } else {
                $message = 'Error: El aporte no puede ser mayor a la deuda actual';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }
            break;

        // ============================================================
        // CASE 7: Crear préstamo directo (sin solicitud)
        // ============================================================
        case '7':
            if (empty($cedula)) {
                $message = 'Error: Complete todos los campos';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            if ($Nomina->ValidatePrestamos($cedula)) {
                $message = 'Error: El empleado ya poseé un prestamo activo';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            $monto = (float)getPostValue('monto', 0);
            if ($monto <= 0 || $monto > 2000) {
                $message = ($monto <= 0)
                    ? 'Error: El monto del préstamo debe ser mayor a 0'
                    : 'Error: El monto del préstamo no puede ser mayor a 2000';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            $fechaIngreso     = getPostValue('f_ingreso', 'null');
            $fechaActual      = new DateTime();
            $fechaIngresoDate = new DateTime($fechaIngreso);
            $intervalo        = $fechaActual->diff($fechaIngresoDate);
            $diferenciaEnMeses= $intervalo->m + ($intervalo->y * 12);
            $descuento        = getPostValue('descuento', 0);
            $cuota            = getPostValue('cuotas', 0);
            $solicitud        = getPostValue('fechasolicitud', 'null');
            $limit            = getPostValue('fechalimite', 'null');
            $concepto         = getPostValue('info', 'null');

            if ($diferenciaEnMeses <= 5) {
                $message = 'Error: El empleado posee menos de 6 meses en la empresa';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            if ($Nomina->Create_Prestamos_Ori($descuento, $monto, $cuota, $concepto, $cedula, $solicitud, $limit)) {
                $message = 'Prestamo añadido con exito';
                ob_start();
                include_once '../../View/Components/True_alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            } else {
                $message = 'Error: Algo salio mal';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }
            break;

        // ============================================================
        // CASE 8: Solicitar préstamo
        // ============================================================
        case '8':
            if (empty($cedula)) {
                $message = 'Error: Complete todos los campos';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            if ($Nomina->ValidatePrestamos($cedula)) {
                $message = 'Error: El empleado ya poseé un prestamo activo';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            if ($Nomina->Exists_solicitud_prestamos($cedula)) {
                $message = 'Error: El empleado ya poseé una solicitud activa';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            $monto = (float)getPostValue('monto', 0);
            if ($monto <= 0 || $monto > 2000) {
                $message = ($monto <= 0)
                    ? 'Error: El monto del préstamo debe ser mayor a 0'
                    : 'Error: El monto del préstamo no puede ser mayor a 2000';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            $fechaIngreso = getPostValue('f_ingreso', null);
            if (!$fechaIngreso || $fechaIngreso === 'null') {
                $message = 'Error: No se pudo verificar la fecha de ingreso del empleado';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            $fechaActual       = new DateTime();
            $fechaIngresoDate  = new DateTime($fechaIngreso);
            $intervalo         = $fechaActual->diff($fechaIngresoDate);
            $diferenciaEnMeses = $intervalo->m + ($intervalo->y * 12);

            if ($diferenciaEnMeses <= 5) {
                $message = 'Error: El empleado posee menos de 6 meses en la empresa';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            $descuento = getPostValue('descuento', 0);
            $cuota     = getPostValue('cuotas', 0);
            $solicitud = getPostValue('fechasolicitud', 'null');
            $concepto  = getPostValue('info', 'null');
            $estado    = 'Espera';

           if ($Nomina->Insert_Solicitud($cedula, $monto, $descuento, $cuota, $concepto, $solicitud, $estado)) {
                $message = 'Solicitud enviada';
                ob_start();
                include_once '../../View/Components/True_alerts.php';
                $html = ob_get_clean();

                $message = 'Su solicitud está siendo revisada por el departamento de nómina'; // ← reutiliza $message
                ob_start();
                include_once '../../View/Components/alertsW.php';
                $html2 = ob_get_clean();

                echo json_encode(['message' => $message, 'html' => $html, 'html2' => $html2]);
                exit;
            }else {
                $message = 'Error: Algo salio mal al procesar la solicitud';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }
            break;

        // ============================================================
        // CASE 9: Insertar nuevo usuario
        // ============================================================
        case '9':
            if (empty($cedula)) {
                $message = 'Error: Complete todos los campos';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            if ($Empleado->validate_DNI($cedula)) {
                if (!$User->validate_DNI($cedula)) {
                    $usuario   = getPostValue('username', 'null');
                    $contrasena= getPostValue('pass', 'null');
                    $tipo      = getPostValue('tipo', 'null');

                    if ($User->Insert_User($usuario, $contrasena, $cedula, $tipo)) {
                        $message = 'Usuario registrado';
                        ob_start();
                        include_once '../../View/Components/True_alerts.php';
                        $html = ob_get_clean();
                        echo json_encode(['message' => $message, 'html' => $html]);
                    } else {
                        $message = 'Error: Algo salio mal';
                        ob_start();
                        include_once '../../View/Components/alerts.php';
                        $html = ob_get_clean();
                        echo json_encode(['message' => $message, 'html' => $html]);
                        exit;
                    }
                } else {
                    $message = 'Error: Ya Existe un usuario con esta cédula';
                    ob_start();
                    include_once '../../View/Components/alerts.php';
                    $html = ob_get_clean();
                    echo json_encode(['message' => $message, 'html' => $html]);
                    exit;
                }
            } else {
                $message = 'Error: No es una cédula válida';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }
            break;

        // ============================================================
        // CASE 10: Actualizar usuario
        // ============================================================
        case '10':
            if (empty($cedula)) {
                $message = 'Error: Complete todos los campos';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }

            if ($Empleado->validate_DNI($cedula)) {
                $usuario    = getPostValue('username', 'null');
                $contrasena = getPostValue('pass', 'null');
                $tipo       = getPostValue('tipo', 'null');

                if ($cedula == $_SESSION['id']) {
                    if ($tipo != $_SESSION['type']) {
                        $message = 'Error: No se puede cambiar el tipo de usuario';
                        ob_start();
                        include_once '../../View/Components/alerts.php';
                        $html = ob_get_clean();
                        echo json_encode(['message' => $message, 'html' => $html]);
                        exit;
                    }

                    $datosUsuario = $User->ReturnDataUser($_SESSION['user'], $_SESSION['clave']);
                    if ($contrasena == $datosUsuario[0]['clave']) {
                        session_destroy();
                        $message = 'Usuario actualizado, por favor inicie sesión nuevamente';
                        ob_start();
                        include_once '../../View/Components/True_alerts.php';
                        $html = ob_get_clean();
                        echo json_encode(['message' => $message, 'html' => $html, 'redirect' => true]);
                        exit;
                    }
                }

                if ($User->Update_User($usuario, $contrasena, $cedula, $tipo)) {
                    if ($cedula == $_SESSION['id']) {
                        session_destroy();
                        $message = 'Usuario actualizado, por favor inicie sesión nuevamente';
                        ob_start();
                        include_once '../../View/Components/True_alerts.php';
                        $html = ob_get_clean();
                        echo json_encode(['message' => $message, 'html' => $html, 'redirect' => true, 'url' => '../index.php']);
                        exit;
                    } else {
                        $message = 'Usuario actualizado';
                        ob_start();
                        include_once '../../View/Components/True_alerts.php';
                        $html = ob_get_clean();
                        echo json_encode(['message' => $message, 'html' => $html]);
                        exit;
                    }
                } else {
                    $message = 'Error: Algo salio mal';
                    ob_start();
                    include_once '../../View/Components/alerts.php';
                    $html = ob_get_clean();
                    echo json_encode(['message' => $message, 'html' => $html]);
                    exit;
                }
            } else {
                $message = 'Error: No es una cédula válida';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                echo json_encode(['message' => $message, 'html' => $html]);
                exit;
            }
            break;

        default:
            break;
    }
}
?>