<?php
// El @ se utiliza para no mostrar ningún error en el caso de que la sesión no esté iniciada.
// Si no le ponemos @ podriamos exponer información comprometedora de nuestro servidor
session_start();
 
// session_destroy rompe la cadena de ejecución de la sesión y "nos saca del sistema"
session_destroy();
 
// Esto nos reenvía al index.php como invitados
header("Location:../index.php");
?>