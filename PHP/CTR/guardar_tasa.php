<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que se ha enviado una tasa
    if (isset($_POST['tasa'])) {
        // Guardar la tasa en la sesión
        $_SESSION['TasaBCV'] = $_POST['tasa'];
        echo json_encode(['status' => 'success']); // Respuesta exitosa
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Tasa no válida.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
?>