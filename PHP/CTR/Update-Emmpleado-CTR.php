<?php 
include_once '../CLASS/user_Original.php';
var_dump($_POST);

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
		
		$F_ingreso = date('Y-m-d', strtotime($ingreso));

		if (!empty($cedula) && !empty($nombre) && !empty($apellido) &&
    		!empty($direccion) && !empty($correo) && !empty($sexo) &&
    		!empty($tlf) && !empty($departamento) && !empty($cargo) && 
			!empty($F_ingreso) && !empty($sueldo) && !empty($edad)) {
    
                if (!isset($_POST['btnU'])) {
                    if ($Empleado->get_DNI($cedula)) {
                        echo 'Error: Ya existe un empleado con esta cédula';
                    }
                     else {
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
                        $edad)){
                            echo 'Empleado Registrado';
                        } else{
                            echo 'Algo ha salido mal';
                        }
                    }
                }else{
                    if ($Empleado->Update_Empleado($cedula,$nombre,
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
                    $edad)){
                        echo 'Datos actualizados';
                    }else{
                        echo 'Ha ocurrido un error';
                    }
                }
}else{
	echo 'Error: Todos los campos son obligatorios';
}
	
 ?>