<?php
include_once '../CLASS/user_Original.php';

if (!isset($_POST['id'])) {
    echo 'Error al obtener la cedula del empleado';
}else{
    $id = $_POST['id'];
	if ($User->Revalidate_User($id)) {
        echo 'Usuario habilitado con exito';
	}else{
        echo 'Error: Usuario no identificado';
	}
}
?>