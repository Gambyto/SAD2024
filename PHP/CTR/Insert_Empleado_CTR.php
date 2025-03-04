<?php 
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
$type_invalid_person= $_POST['tipo-discapacidad'];
$afeccion= $_POST['afeccion'];
		
$F_ingreso = date('Y-m-d', strtotime($ingreso));



if (!empty($cedula) && !empty($nombre) && !empty($apellido) &&
!empty($direccion) && !empty($correo) && !empty($sexo) &&
!empty($tlf) && !empty($departamento) && !empty($cargo) && 
!empty($F_ingreso) && !empty($sueldo) && !empty($edad)) {
	
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
		$edad)) {
			$message = 'Datos actualizados';
			ob_start();
			include_once '../../View/Components/True_alerts.php';
			$html = ob_get_clean();
			$response = array('message' => $message, 'html' => $html);
			echo json_encode($response);
			exit;
		}else {
			$message = 'Error: Algo salio mal';
			ob_start();
			include_once '../../View/Components/alerts.php';
			$html = ob_get_clean();
			$response = array('message' => $message, 'html' => $html);
			echo json_encode($response);
			exit;
		}
		
	}else{
		
		if($Empleado->get_DNI($cedula)) {
			$message = 'Error: Este empleado ya existe';
			ob_start();
			include_once '../../View/Components/alerts.php';
			$html = ob_get_clean();
			$response = array('message' => $message, 'html' => $html);
			echo json_encode($response);
			exit;
		}
		else {
			$error = $Nomina->validarCampos($sueldo,$edad,$F_ingreso);
			if ($error){
				$message = $error;
				ob_start();
				include_once '../../View/Components/alerts.php';
				$html = ob_get_clean();
				$response = array('message' => $message, 'html' => $html);
				echo json_encode($response);
				exit;
			}else{
				if ($Empleado -> Create_Empleado($cedula,
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
					$edad, $type_invalid_person, $afeccion)){
						$message = 'Empleado registrado con exito';
						ob_start();
						include_once '../../View/Components/True_alerts.php';
						$html = ob_get_clean();
						$response = array('message' => $message, 'html' => $html);
						echo json_encode($response);
						exit;
					} else{
						$response = array('message' => 'Algo ha salido mal');
						echo json_encode($response);
						exit;
					}
				}
				}
			}
			
		}else{
			$message = 'Error: Todos los campos son obligatorios';
			ob_start();
			include_once '../../View/Components/alerts.php';
			$html = ob_get_clean();
			$response = array('message' => $message, 'html' => $html);
			echo json_encode($response);
			exit;
		}
			?>