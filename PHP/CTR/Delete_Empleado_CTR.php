<?php
include_once '../CLASS/user_Original.php';

if (!isset($_POST['id'])) {
    echo 'Error al obtener la cedula del empleado';
}else{
    $id = $_POST['id'];
	if ($Empleado->Eliminate($id)) {
        echo 'Emepleado eliminado con exito';
	}else{
        echo 'Error: Empleado no identificado';
	}
}
?>