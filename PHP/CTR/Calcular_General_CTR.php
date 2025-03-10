<?php

use Illuminate\Support\Arr;

session_start();
include_once '../CLASS/user_Original.php';
$tasaBCV = isset($_SESSION['TasaBCV']) ? floatval($_SESSION['TasaBCV']) : 1; // Valor por defecto 1
$op = isset($_POST['op']) ? intval($_POST['op']) : 0;
$Vacacionesini = date("Y-m-d");
$response = []; // Inicializa la respuesta

switch ($op) {
    case 1: //Nómina
        // Obtener los datos enviados por AJAX
        $sueldoSemanal = isset($_POST['sueldoSemanal']) ? floatval($_POST['sueldoSemanal']) : 0;
        $totalDeducciones = isset($_POST['totalDeducciones']) ? floatval($_POST['totalDeducciones']) : 0;
        $totalAsignaciones = isset($_POST['totalAsignaciones']) ? floatval($_POST['totalAsignaciones']) : 0;
        
        if ($totalDeducciones >= ($sueldoSemanal + $totalAsignaciones)) {
            ob_start();
            if ($totalDeducciones > $sueldoSemanal) {
                $message = 'Advertencia: el total de ducciones es mayor al sueldo, se habilitara la edición de prestamo';
                include_once '../../View/Components/alertsW.php';
            } else {
                $message = 'Advertencia: el neto a pagar son 0$ puede guardar el pago o modificar las deducciones';
                include_once '../../View/Components/alertsW.php';
            }
            $html = ob_get_clean();
            $response = array('message' => $message, 'html' => $html);
            echo json_encode($response);
            exit;
        }else{
            // Calcular Neto a Pagar
            $netoPagar = $sueldoSemanal + $totalAsignaciones - $totalDeducciones;
            // Preparar la respuesta en formato JSON
            $response = array(
                'netoPagar' => $netoPagar,
                'tasaBCV' => $tasaBCV
            );
        }
        break;
    
    case 2: //Vacaciones
        if (!empty($_POST['sueldo'])) {
            
            if (!empty($_POST['vacacionesini'])) {
                $Vacacionesini = isset($_POST['vacacionesini']) ? $_POST['vacacionesini'] : $Vacacionesini; 
                
                $Dadd = $Nomina->DaysOff($_POST['cedula'], $Vacacionesini);
                $Dadd = $Dadd > 16 ? 16 : $Dadd; //Días de Servicio
                $Dvacaciones = 15 + $Dadd -1; //Días de Vacaciones 
        
                $resultado = $Nomina ->Last_DaysOff($Dvacaciones, $Vacacionesini);
                $Laboral= $Nomina->MidDays($resultado['fecha']);
                $Vacacionesfin = $resultado['fecha'];
            // Calculos generales de vacaciones (muestreo de la tabla)
                $vacation = $Dvacaciones * $_POST['sueldo'];
        
                $Pweekend = $resultado['diasFinSemana'] * $_POST['sueldo'];
        
                $Totaladd = ($_POST['feriado'] + $_POST['utilidades'] + $_POST['pendientes']) * $_POST['sueldo'];
        
                $inceV = ($Totaladd + $Pweekend + ($vacation * 2)) * 0.005;
        
                $Monto = ($Totaladd + $Pweekend + ($vacation * 2)) - $inceV;
    
                $response = array(
                    'vacacionesini' => $Vacacionesini,
                    'Vacacionesfin' => $Vacacionesfin,
                    'Dvacaciones' => $Dvacaciones,
                    'laboral' => $Laboral,
                    'vacation' => $vacation,
                    'FinSemana' => $resultado['diasFinSemana'],
                    'pweekend' => $Pweekend,
                    'servicio' => $Dadd,
                    'ince' => $inceV,
                    'Monto' => $Monto,

                    //post
                    'feriado' => $_POST['feriado'],
                    'utilidades' => $_POST['utilidades'],
                    'pendientes' => $_POST['pendientes']
                );
            }else{
                $message = 'Error: No se inserto la fecha de inicio';
                ob_start();
                include_once '../../View/Components/alerts.php';
                $html = ob_get_clean();
                $response = array('message' => $message, 'html' => $html);
                echo json_encode($response);
                exit;
            }

        }else{
            $message = 'Error: No se encontraron datos para calcular las vacaciones';
            ob_start();
            include_once '../../View/Components/alerts.php';
            $html = ob_get_clean();
            $response = array('message' => $message, 'html' => $html);
            echo json_encode($response);
            exit;
        }
        break;

    case 3: //Fideicomisos
        // Obtener los datos enviados por AJAX
        if (isset($_POST) && isset($_POST['sueldo'])) {
        $Tutilidad = 90/360;
        $alicuotaU = (($_POST['sueldo'] * 0.33) * 90) / 360;

        $fecha = date("Y-m-d");
        $Dadd = $Nomina->DaysOff($_POST['cedula'], $fecha);
        $Dadd = $Dadd > 16 ? 16 : $Dadd; // Dias de servicio
        $Dvacaciones = 15 + $Dadd - 1; // Dias acumulados

        $antiguedad = 15;
        $Tdias= $antiguedad + $Dvacaciones;

        $bonoVacacional = $Dvacaciones / 360;
        $bonoVacacional = number_format($bonoVacacional, 2);
        $alicuotaBV = (($_POST['sueldo'] * 0.33) * $Dvacaciones) / 360;
        $alicuotaBV = number_format($alicuotaBV, 2);

        $Sintegral = ($_POST['sueldo'] * 0.33) + $alicuotaBV + $alicuotaU;
        $Sintegral = number_format($Sintegral, 2);
        $Sintegral_diario = $Sintegral / 30;
        $Sintegral_diario = number_format($Sintegral_diario, 2);

        $fideicomiso = $Sintegral_diario * $Tdias;
        $anticipo = $fideicomiso * 0.75;

        // Preparar la respuesta en formato JSON
        $response = array(
            'Tutilidad' => $Tutilidad,
            'alicuotaU' => $alicuotaU,
            'bonoVaca' => $bonoVacacional,
            'alicuotaBV' => $alicuotaBV,
            'Sintegral' => $Sintegral,
            'Dintegral' => $Sintegral_diario,
            'antiguedad' => $antiguedad,
            'Dvacaciones' => $Dvacaciones,
            'Tdias' => $Tdias,
            'fideicomiso' => $fideicomiso,
            'Tservicio' => $Dadd,
            'anticipo' => $anticipo,
            'tasaBCV' => $tasaBCV
        );
    } else {
        // Manejar el caso en que $dato no esté definido
        $response = array('error' => 'Datos del empleado no encontrados.');
    }
    break;

    case 4: //ISLR
        // Obtener los datos enviados por AJAX
        if (isset($_POST) && isset($_POST['sueldo'])) {
            if (isset($_POST['reten'])) {
                $aporte = $_POST['reten'];

                $Monto = (($_POST['sueldo'] * $_SESSION['TasaBCV']) * $aporte) / 100;

                $response =  array(
                    'Monto' => $Monto
                );
            } else{
                $response = array('Error' => 'Error: No se ha ingresado el porcentaje a retener');
            }
        } else {
            // Manejar el caso en que $dato no esté definido
            $response = array('error' => 'Datos del empleado no encontrados.');
        }
    break;

    case 5: // Prestamos Financieros
        // Obtener los datos enviados por AJAX
        if (isset($_POST) && isset($_POST['solicitud'])) {
            if (isset($_POST['limite'])) {
                $ini = $_POST['solicitud'];
                $end = $_POST['limite'];

                $weeks = $Nomina->MidWeeks($ini,$end);
            if ($weeks == 0){
                $weeks = 1;
            }
                $response = array(
                    'cuotas' => $weeks
                );

            }else{
                $response = array('Error' => 'Error: No se ha ingresado la fecha de límite');
            }
        }else{
            $response = array('Error' => 'Error: No se ha ingresado la fecha de solicitud');
        }
    break;

default:
    $response = array('error' => 'Operación no válida.');
    break;
}

// Asegúrate de que la respuesta no esté vacía
if (empty($response)) {
$response = array('error' => 'No se generó respuesta.');
}

// Devuelve la respuesta como JSON
echo json_encode($response);
?>