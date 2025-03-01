<?php
include_once '../CLASS/user_Original.php';

if (!isset($_POST['id'])) {
    echo 'Error al obtener el identificador del prestamo';
}else{
    $id = $_POST['id'];
	if ($Nomina->Delete_Prestamo($id)) {
        echo 'Prestamo eliminado con exito';
	}else{
        echo 'Error: Prestamo no identificado';
	}
}
?>