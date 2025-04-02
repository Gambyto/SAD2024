<?php

use PhpParser\Node\Stmt\TryCatch;

session_start();
include_once '../CLASS/user_Original.php';

function getPostValue($key, $default = null) {
    return isset($_POST[$key]) && trim($_POST[$key]) !== '' ? $_POST[$key] : $default;
}


// Variables de llegada
$op = $_POST['op'];

if(empty($_POST['cedula'])){
    $message = 'Error: debe ingresar la cedula del empleado';
    ob_start();
    include_once '../../View/Components/alerts.php';
    $html = ob_get_clean();
    $response = array('message' => $message, 'html' => $html);
    echo json_encode($response);
    exit;
 }else{
     $cedula = $_POST['cedula'];


switch ($op) {
    case '1': // Caso para insertar un empleado a la nomina 
    if (empty($cedula)) {
        $message = 'Error: Complete todos los campos';
        ob_start();
        include_once '../../View/Components/alerts.php';
        $html = ob_get_clean();
        $response = array('message' => $message, 'html' => $html);
        echo json_encode($response);
        exit;
    } else {
        if ($Nomina->validarPagoEmpleado($cedula)) {
            $message = 'Error: Ya se ha emitido un pago a este trabajador esta semana';
            ob_start();
            include_once '../../View/Components/alerts.php';
            $html = ob_get_clean();
            $response = array('message' => $message, 'html' => $html);
            echo json_encode($response);
            exit;
        }else{
            //Nomina
            $id_cpp =getPostValue('id_consumo','null'); 
            $id_ptm =getPostValue('id_prestamo','null');
            $sueldoS =getPostValue('sueldoS',0);
            $netoDiv =getPostValue('Netodiv','null');
            $bono = getPostValue('bono1',0);
            $comisiones =getPostValue('comision1',0);
            
            $descConsumo = getPostValue('consumo',0);
            $descPrestamo = getPostValue('prestamo',0);

            $fecha = date('Y-m-d');

            if ($netoDiv == 'null') {
                $message = 'Error: No se ha calculado el sueldo';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                $response = array('message' => $message, 'html' => $html);
                echo json_encode($response);
                exit;
            }else{
                if ($Nomina->Create_Nomina($cedula, $id_cpp, $id_ptm, $sueldoS, $netoDiv,$bono, $comisiones)) {
                if (empty($id_ptm)) {
                    $Nomina->Discount_Prestamos($cedula,$descPrestamo);
                    $data = $Nomina->Display_Prestamos($cedula);
                    $deuda = $data['monto_desc'];
                    $Nomina->Insert_aporte($id_ptm,$deuda,$descPrestamo,'Sueldo','No aplica',$fecha);
                    
                    $message = 'Nomina registrada';
                    ob_start();
                    include_once '../../View/Components/True_alerts.php';
                    $html = ob_get_clean();
                    $response = array('message' => $message, 'html' => $html);
                    echo json_encode($response);
                }else{
                    $message = 'Nomina registrada';
                    ob_start();
                    include_once '../../View/Components/True_alerts.php';
                    $html = ob_get_clean();
                    $response = array('message' => $message, 'html' => $html);
                    echo json_encode($response);
                }
            } else {
                $message = 'Error: Algo salio mal';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                $response = array('message' => $message, 'html' => $html);
                echo json_encode($response);
                exit;
            }
        }
        }

    }
        break;

    case '2': // Caso para insertar las vacaciones de un empleado 

        if (empty($cedula)) {
            $message = 'Error: Complete todos los campos';
            ob_start();
            include_once '../../View/Components/alerts.php';
            $html = ob_get_clean();
            $response = array('message' => $message, 'html' => $html);
            echo json_encode($response);
            exit;
        }else {
            $Dvacaciones = getPostValue('Dvacaciones','null'); //
            $vacacionesini = getPostValue('Vacacionesini','null'); //
            $vacacionesfin = getPostValue('Vacacionesfin','null'); //
            $laboral = getPostValue('inilaboral','null'); //
            $findesemana = getPostValue('finsemana',0); //
            $sueldod = getPostValue('sueldoD',0); //
            $monto = getPostValue('monto',0); //
            $ince = getPostValue('ince',0); //

            $feriado = getPostValue('feriados',0); //
            $pendiente = getPostValue('pendientes',0); //
            $utilidad = getPostValue('utilidades',0); //
            $Tservicio = getPostValue('servicio',0); //
            
            if(empty($monto)){
                echo 'Error: No se ha calculado el monto de las vacaciones';
            }else{
                if($Nomina->Vacation_Insert($Dvacaciones,$utilidad,$Tservicio,$vacacionesini,$vacacionesfin,
                $laboral,$findesemana,$pendiente,$feriado,$sueldod,$cedula,$monto,$ince)){
                    $message = 'Vacaciones cargadas con exito';
                    ob_start();
                    include_once '../../View/Components/True_alerts.php';
                    $html = ob_get_clean();
                    $response = array('message' => $message, 'html' => $html);
                    echo json_encode($response);
                } else {
                    $message = 'Error: Algo salio mal';
                    ob_start();
                    include_once '../../View/Components/alerts.php';
                    $html = ob_get_clean();
                    $response = array('message' => $message, 'html' => $html);
                    echo json_encode($response);
                    exit;
                }
            }
        }
        break;

    case '3': // caso para insertar un fideicomiso
        if (empty($cedula)) {
            $message = 'Error: Complete todos los campos';
            ob_start();
            include_once '../../View/Components/alerts.php';
            $html = ob_get_clean();
            $response = array('message' => $message, 'html' => $html);
            echo json_encode($response);
        exit;
        }else{
            $Tservicio= getPostValue('Tservicio',0);
            $Tutilidad = getPostValue('Tuitlidad',0);
            $alicuotaU = getPostValue('alicuotaU',0);
            $bonoVacacional = getPostValue('bonoVaca',0);
            $alicuotaBV = getPostValue('alicuotaBV',0);
            $Sintegral = getPostValue('Sintegral',0);
            $Sintegral_diario = getPostValue('Dintegral',0);
            $antiguedad = getPostValue('antiguedad',0);
            $Dvacaciones = getPostValue('Dvacaciones',0);
            $Tdias = getPostValue('Tdias',0);
            $fideicomiso = getPostValue('fideicomiso1',0);
            $anticipo = getPostValue('anticipo1',0);

            if ($Nomina->Insert_Fide($cedula,$Tservicio,$Tutilidad,
            $bonoVacacional,$alicuotaU, $alicuotaBV,$Sintegral,$Sintegral_diario,
            $antiguedad,$Dvacaciones,$Tdias,$anticipo,$fideicomiso)) {
                $message = 'Fideicomiso registrado';
                ob_start();
                include_once '../../View/Components/True_alerts.php';
                $html = ob_get_clean();
                $response = array('message' => $message, 'html' => $html);
                echo json_encode($response);
            }else{
                $message = 'Error: Algo salio mal';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                $response = array('message' => $message, 'html' => $html);
                echo json_encode($response);
                exit;
            }
        }
        break;

    
    case '4': // Caso para insertar un aporte al ISLR
    if (empty($cedula)) {
        $message = 'Error: Complete todos los campos';
        ob_start();
        include_once '../../View/Components/alerts.php';
        $html = ob_get_clean();
        $response = array('message' => $message, 'html' => $html);
        echo json_encode($response);
        exit;
    }else{
        $aporte =getPostValue('reten',0);
        $monto = getPostValue('aporte1',0);

        if ($Nomina->Create_ISLR($aporte,$monto, $cedula)) {
            $message = 'Aporte ingresado';
            ob_start();
            include_once '../../View/Components/True_alerts.php';
            $html = ob_get_clean();
            $response = array('message' => $message, 'html' => $html);
            echo json_encode($response);
        }else{
            $message = 'Error: Algo salio mal';
			ob_start();
			include_once '../../View/Components/alerts.php';
			$html = ob_get_clean();
			$response = array('message' => $message, 'html' => $html);
			echo json_encode($response);
			exit;
        }
    }
    break;

    case '6': // Caso para insertar un aporte al prestamo
        if (empty($cedula)){
            $message = 'Error: Complete todos los campos';
            ob_start();
            include_once '../../View/Components/alerts.php';
            $html = ob_get_clean();
            $response = array('message' => $message, 'html' => $html);
            echo json_encode($response);
            exit;
        }else{
            $aporte = getPostValue('descuento',0);
            $monto_desc = getPostValue('monto_desc',0);
            if ($aporte <= $monto_desc) {

                $id = getPostValue('idp',0);
                $parcial = getPostValue('parcial',0);
                $tpago = getPostValue('tpago',0);
                $references = getPostValue('referencia','No aplica');
                $fecha = date('Y-m-d');

                if ($Nomina->Insert_aporte($id,$parcial,$aporte,$tpago,$references,$fecha)) {
                    $Nomina->Discount_Prestamos($cedula,$aporte);

                    $message = 'Aporte ingresado';
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
                $message = 'Error: El aporte no puede ser mayor a la deuda actual';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                $response = array('message' => $message, 'html' => $html);
                return json_encode($response);
                exit;
            }

        }
        break;

    case '7': // Caso para insertar un prestamo
            if (empty($cedula)){
                $message = 'Error: Complete todos los campos';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                $response = array('message' => $message, 'html' => $html);
                echo json_encode($response);
                exit;
            }else{

                $fechaIngreso = getPostValue('f_ingreso','null');
                $fechaActual = new DateTime();
                $fechaIngresoDate = new DateTime($fechaIngreso);
                $intervalo = $fechaActual->diff($fechaIngresoDate);
                $diferenciaEnMeses = $intervalo->m + ($intervalo->y * 12);

                $descuento = getPostValue('descuento',0);
                $monto = getPostValue('monto',0);
                $cuota = getPostValue('cuotas',0);
                $solicitud = getPostValue('fechasolicitud','null');
                $limit = getPostValue('fechalimite','null');
                $concepto = getPostValue('info','null');

                if ($diferenciaEnMeses  <= 5) {
                    $message = 'Error: El empleado posee menos de 6 meses en la empresa';
                    ob_start();
                    include_once '../../View/Components/alerts.php';
                    $html = ob_get_clean();
                    $response = array('message' => $message, 'html' => $html);
                    echo json_encode($response);
                    exit;
                }else{

                    if ($Nomina->ValidatePrestamos($cedula)) {
                        
                        $message = 'Error: El empleado ya poseé un prestamo activo';
                        ob_start();
                        include_once '../../View/Components/alerts.php';
                        $html = ob_get_clean();
                        $response = array('message' => $message, 'html' => $html);
                        echo json_encode($response);
                        exit;
                        
                    }else{
                        
                        if ($Nomina->Create_Prestamos_Ori($descuento, 
                        $monto, $cuota, $concepto, $cedula, $solicitud,$limit)){
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
                    }
                }
            }
        
    break;

    case '8': // Caso para solicitar un prestamo
            if (empty($cedula)){
                $message = 'Error: Complete todos los campos';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                $response = array('message' => $message, 'html' => $html);
                echo json_encode($response);
                exit;
            }else{
                $descuento = getPostValue('descuento',0);
                $monto = getPostValue('monto',0);
                $cuota = getPostValue('cuotas',0);
                $solicitud = getPostValue('fechasolicitud','null');
                $concepto = getPostValue('info','null');
                $estado = 'Espera';
    
                if ($Nomina->Insert_Solicitud($cedula, $monto, $descuento, $cuota, $concepto, $solicitud, $estado)){
                    $message = 'Solicitud enviada';
                    ob_start();
                    include_once '../../View/Components/True_alerts.php';
                    $html = ob_get_clean();
                    $response = array('message' => $message, 'html' => $html);
                    echo json_encode($response);
                }else{
                    $message = 'Error: Algo salio mal';
                    ob_start();
                    include_once '../../View/Components/alerts.php';
                    $html = ob_get_clean();
                    $response = array('message' => $message, 'html' => $html);
                    echo json_encode($response);
                    exit;
                }
            }
        
    break;

    case '9': // Caso para insertar nuevos usuarios
        if (empty($cedula)) {
            $message = 'Error: Complete todos los campos';
            ob_start();
            include_once '../../View/Components/alerts.php';
            $html = ob_get_clean();
            $response = array('message' => $message, 'html' => $html);
            echo json_encode($response);
            exit;
        }else{
            if ($Empleado->validate_DNI($cedula)) {
                if (!$User->validate_DNI($cedula)){

                    $usuario = getPostValue('username','null');
                    $contrasena = getPostValue('pass','null');
                    $tipo = getPostValue('tipo','null');
                    
                    if ($User->Insert_User($usuario,$contrasena,$cedula,$tipo)) {
                        $message = 'Usuario registrado';
                        ob_start();
                        include_once '../../View/Components/True_alerts.php';
                        $html = ob_get_clean();
                        $response = array('message' => $message, 'html' => $html);
                        echo json_encode($response);
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
                    $message = 'Error: Ya Existe un usuario con esta cédula';
                    ob_start();
                    include_once '../../View/Components/alerts.php';
                    $html = ob_get_clean();
                    $response = array('message' => $message, 'html' => $html);
                    echo json_encode($response);
                    exit;
                }
            }else{
                $message = 'Error: No es una cédula válida';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                $response = array('message' => $message, 'html' => $html);
                echo json_encode($response);
                exit;
            }
        }
        break;

    case '10': // Caso para insertar nuevos usuarios
        if (empty($cedula)) {
            $message = 'Error: Complete todos los campos';
            ob_start();
            include_once '../../View/Components/alerts.php';
            $html = ob_get_clean();
            $response = array('message' => $message, 'html' => $html);
            echo json_encode($response);
            exit;
        }else{
            if ($Empleado->validate_DNI($cedula)) {
                    $usuario = getPostValue('username','null');
                    $contrasena = getPostValue('pass','null');
                    $tipo = getPostValue('tipo','null');
                    
                    if ($User->Update_User($usuario,$contrasena,$cedula,$tipo)) {
                        $message = 'Usuario actualizado';
                        ob_start();
                        include_once '../../View/Components/True_alerts.php';
                        $html = ob_get_clean();
                        $response = array('message' => $message, 'html' => $html);
                        echo json_encode($response);
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
                $message = 'Error: No es una cédula válida';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                $response = array('message' => $message, 'html' => $html);
                echo json_encode($response);
                exit;
            }
        }
        break;
   

    break;

    default:
        break;
} }
 ?>