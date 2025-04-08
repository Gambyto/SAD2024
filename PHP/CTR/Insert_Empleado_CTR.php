<?php 
session_start();
include_once '../CLASS/user_Original.php';

$cedula = $_POST['cedula'];
$nombre = $_POST['nombre']; 
$apellido = $_POST['apellido'];
$direccion = $_POST['direccion'];
$correo = $_POST['correo'];
$sexo = $_POST['sexo'] ?? null;
$tlf = $_POST['tlf'];
$second_tlf= $_POST['second_tlf'];
$departamento = $_POST['departamento'] ?? null;
$cargo = $_POST['cargo'] ?? null;
$ingreso = $_POST['ingreso'];
$sueldo = $_POST['sueldo'];
$edad= $_POST['edad'];
$type_invalid_person= $_POST['tipo-discapacidad'] ?? null;
$afeccion= $_POST['afeccion'] ?? null;

$F_ingreso = date('Y-m-d', strtotime($ingreso));

if ($_SESSION['type'] != 'Gerencia') {
    $message = 'No tienes permisos para realizar esta acción';
	ob_start();
	include_once '../../View/Components/alerts.php';
	$html = ob_get_clean();
	$response = array('message' => $message, 'html' => $html);
	echo json_encode($response);
	exit;
} 

if (!empty($cedula) && !empty($nombre) && !empty($apellido) &&
!empty($direccion) && !empty($correo) && !empty($sexo) &&
!empty($tlf) && !empty($departamento) && !empty($cargo) && 
!empty($F_ingreso) && !empty($sueldo) && !empty($edad)) {
	if ($cedula < 1000000) {
		$message = 'Error: La cedula no es valida';
		ob_start();
		include_once '../../View/Components/alerts.php';
		$html = ob_get_clean();
		$response = array('message' => $message, 'html' => $html);
		echo json_encode($response);
		exit;

	}else {
	
		
		if (isset($_POST['btnU'])) {
			if ($Empleado->Update_Empleado($cedula,
            $nombre,
            $apellido,
            $direccion,
            $correo,
            $sexo,
            $tlf,
            $second_tlf,
            $departamento,
            $cargo,
            $F_ingreso,
            $sueldo,
            $edad,
            $type_invalid_person,
            $afeccion)) {
				$message = 'Empleado actualizado con éxito';
				ob_start();
            include_once '../../View/Components/True_alerts.php';
            $html = ob_get_clean();
            $response = array('message' => $message, 'html' => $html);
            echo json_encode($response);
            exit;
        } else {
			$message = 'Error: al actualizar empleado';
            ob_start();
            include_once '../../View/Components/alerts.php';
            $html = ob_get_clean();
            $response = array('message' => $message, 'html' => $html);
            echo json_encode($response);
            exit;
        }
    } else {
		$empleado = $Empleado->get_DNI($cedula);
		
        if(!is_null($empleado)){

            if ($empleado['estado'] == 0 || $empleado['estado'] == 1) {
                $error = $Nomina->validarCampos($sueldo,$edad,$F_ingreso);
			if ($error){
				$message = $error;
				ob_start();
				include_once '../../View/Components/alerts.php';
				$html = ob_get_clean();
				$response = array('message' => $message, 'html' => $html);
				echo json_encode($response);
				exit;}
                else{
                    // Actualizar empleado
                    if ($Empleado->Update_Empleado($cedula,
                    $nombre,
                    $apellido,
                    $direccion,
                    $correo,
                    $sexo,
                    $tlf,
                    $second_tlf,
                    $departamento,
                    $cargo,
                    $F_ingreso,
                    $sueldo,
                    $edad,$type_invalid_person,
                    $afeccion)){
                        if ($empleado['estado'] == 0) {
                            $message = 'Empleado reintegrado con éxito';              
                            ob_start();
                            include_once '../../View/Components/True_alerts.php';
                            $html = ob_get_clean();
                            $response = array('message' => $message, 'html' => $html);
                            echo json_encode($response);
                            exit;
                        }else{
                            $message = 'Empleado actualizado con éxito';
                            ob_start();
                            include_once '../../View/Components/True_alerts.php';
                            $html = ob_get_clean();
                            $response = array('message' => $message, 'html' => $html);
                            echo json_encode($response);
                            exit;
                        }
                    } else {
                        $message = 'Error: al actualizar empleado';
                        ob_start();
                        include_once '../../View/Components/alerts.php';
                        $html = ob_get_clean();
                        $response = array('message' => $message, 'html' => $html);
                        echo json_encode($response);
                        exit;
                    }
                }
        }
        } elseif ($empleado && $empleado['estado'] == 1) {
			// Empleado ya existe y está activo
            $message = 'Error: Empleado ya existe';
            ob_start();
            include_once '../../View/Components/alerts.php';
            $html = ob_get_clean();
            $response = array('message' => $message, 'html' => $html);
            echo json_encode($response);
            exit;


        } else {
            $error = $Nomina->validarCampos($sueldo,$edad,$F_ingreso);
			if ($error){
				$message = $error;
				ob_start();
				include_once '../../View/Components/alerts.php';
				$html = ob_get_clean();
				$response = array('message' => $message, 'html' => $html);
				echo json_encode($response);
				exit;}
                else{

                    // Crear empleado
                    if ($Empleado->Create_Empleado($cedula,
                    $nombre,
                $apellido,
                $direccion,
                $correo,
                $sexo,
                $tlf,
                $second_tlf,
                $departamento,
                $cargo,
                $F_ingreso,
                $sueldo,
                $edad,
                $type_invalid_person,
                $afeccion)) {
					$message = 'Empleado creado con éxito';
					ob_start();
					include_once '../../View/Components/True_alerts.php';
					$html = ob_get_clean();
					$response = array('message' => $message, 'html' => $html);
					echo json_encode($response);
					exit;
				} else {
                    $message = 'Error al crear empleado';
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
		
	}} else {
		$message = 'Error: Todos los campos son obligatorios';
		ob_start();
		include_once '../../View/Components/alerts.php';
		$html = ob_get_clean();
		$response = array('message' => $message, 'html' => $html);
		echo json_encode($response);
    exit;
}
			?>