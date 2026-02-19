<?php 
	session_start(); 

	if (!isset($_SESSION['user'])) {
		header('Location:../index.php');}

		include_once '../PHP/CLASS/conexion_Original.php';
		include_once '../PHP/CLASS/user_Original.php';

        if (!isset($_SESSION['TasaBCV'])) {
            $datos = $Nomina->Verificar();
            if (empty($datos) || empty($datos[0]['tasa_del_dia'])) {
                include_once '../PHP/API_TasaDolar.php';
                $datos = $Nomina->Verificar();
            }
            $tasaDolar = $datos[0]['tasa_del_dia'];
            $_SESSION['TasaBCV'] = number_format($tasaDolar, 2);
        }

        $tasaDelDia = isset($_SESSION['TasaBCV']);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/img/Logo 2.png" type="image/x-icon">
                    <!--Componentes del menu -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display&display=swap" rel="stylesheet">
    
                        <!-- link CSS -->
    <link rel="stylesheet" href="../CSS/alerts.css"> 
    <link rel="stylesheet" href="../CSS/bootstrap.css">
    <link rel="stylesheet" href="../CSS/menu1.css"> <!-- Estilos del menu -->
    <link rel="stylesheet" href="../CSS/Search-box.css"> <!-- Estilos del caja de busqueda -->
    <link rel="stylesheet" href="../CSS/struct.css"> <!-- Cuerpo de la pagina -->
                       
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <title>SAD 2024</title>
</head>

<body class="collapsed">
    
<?php include_once 'menu1.php'; ?>

    <header>
        <?php 
		if ($tasaDelDia !== null) { ?>	
            <h5 class="BCV_Tasa"> Tasa del día: <?php echo "{$_SESSION['TasaBCV']}"; ?> Bs.</h5>	
		<?php  } else{ ?>
            <h5 class="BCV_Tasa"> Tasa del día: No disponible </h5>
        <?php  include_once 'Modals/Modal-tasabcv.php'; } ?>



    