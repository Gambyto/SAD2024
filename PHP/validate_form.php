<?php
if (isset($_POST['send'])) { // si se presiona el boton de entrar
    $u = trim($_POST['user']); //Usuario
    $p = trim($_POST['pass']); //Contraseña

    if (empty($u) || empty($p)) { //validación de variables vacías
        echo '<p class="danger"> Introduzca su usuario y contraseña</p>';
    } else {
        $User = NEW User($u, $p);
            if ($User->__validate()) {
                $_SESSION['user'] = $u; //Variable de seseción para usuario
                header('location:View/main.php');
                exit;
            } else {
                echo '<p class="danger"> Usuario o contraseña no validos </p>';
            }
        }
    }
?>
