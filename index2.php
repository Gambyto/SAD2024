<?php 
session_start();
if (isset($_SESSION['user'])) {
    header('Location:main.php');}

        include 'PHP/CLASS/conec_DB.php';
        include 'PHP/CLASS/User.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<title>SAD 2024</title>

	<link rel="stylesheet" type="text/css" href="CSS/login.css">
</head>
<body>
	
	<header class="header"></header>

	<main>

		<form class="my-form" method="post" action="">
        <div class="login-welcome-row">
            <a href="#" title="Logo">
                <img src="assets/img/lgo_Black.png" alt="Logo" class="logo">
            </a>
            <h1>¡Bienvenido! &#x1F44F;</h1>
            <?php include 'PHP/validate_form.php'; ?>
        </div>
        <div class="input__wrapper">
            <input type="text" id="user" name="user" class="input__field" placeholder="Your user" maxlength="10" required>
            <label for="user" class="input__label">Usuario:</label>

			<svg class="input__icon user" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" 
				stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">

				<path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/>
            </svg>

        </div>
        <div class="input__wrapper">
            <input id="password" type="password" name="pass" class="input__field" placeholder="Your Password"
                title="Mínimo 6 caracteres al menos 1 Número"
                pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$" maxlength="10" required>

            <label for="password" class="input__label">Contraseña:</label>

            <svg class="input__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                stroke-width="2.3" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z"></path>
                <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0"></path>
                <path d="M8 11v-4a4 4 0 1 1 8 0v4"></path>
            </svg>
        </div>
        <button type="submit" class="my-form__button" name="send">
            Entrar
        </button>
    </form>
	</main>

    <div class="block"></div>

    
</body>
</html>