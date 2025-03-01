<?php
session_start();
$user = $_SESSION['user'] ?? null;
if (isset($user)) {
    if ($_SESSION['type'] == 'Trabajador') {
        header('Location:view/Reportes.php');
    }else{
        header('Location:view/Dashboard.php');
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/all.css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css'>
    
    <link rel="stylesheet" href="CSS/default.css">
    <link rel="stylesheet" href="CSS/login.css">
    <link rel="stylesheet" href="CSS/alerts.css">
    

</head>
<body>
    <div class="contenedor">
        <div class="bloque-izquierdo">
            <?php include 'View/login_index.php'; ?>
        </div>
        <div class="bloque-derecho">
            <div class=title>
            <img class="social-login__icon" src="assets/img/Logo 2.png" alt="SAD logo">
                <h1>Sistema Administrativo Disorient</h1>
                <h3>SAD 2024</h3>
            </div>
        </div>

        <div id="alerts"></div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
    // Envío del formulario de registro de empleados
    $('#login').on('submit', function(e) {
        e.preventDefault(); // Evita el envío normal del formulario
 
        // Recoge los datos del formulario
        var formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: 'PHP/CTR/Validate_User_CTR.php',
            data: formData,
            success: function(response) {
                console.log(response);
                var data = JSON.parse(response);
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else if (data.html) {
                    $('#alerts').html(data.html);
                } else {
                    alert(data.message);
                }
            },
            error: function() {
                alert('Error');
            }
        });
    });
});
</script>
</html>