<?php
include_once 'CLASS/user_Original.php';

// URL de la página del BCV que contiene la tasa del día
$url = "https://www.bcv.org.ve/";

// Medir el tiempo de inicio
$start_time = microtime(true);

$response = $Nomina->obtenerTasaDelDia($url);

// Medir el tiempo de finalización
// $end_time = microtime(true);
// $execution_time = $end_time - $start_time; // Tiempo de ejecución en segundos

// Crear un nuevo DOMDocument
$dom = new DOMDocument;
libxml_use_internal_errors(true);
$dom->loadHTML($response);
libxml_clear_errors();

$xpath = new DOMXPath($dom);
$tasaElements = $xpath->query('//div[@id="dolar"]//div[contains(@class, "centrado")]/strong');

if ($tasaElements->length > 0) {
    $tasa_del_dia = trim($tasaElements->item(0)->nodeValue);
    $tasa_del_dia = str_replace('"', '', $tasa_del_dia);
    $tasa_del_dia = str_replace(',', '.', $tasa_del_dia);
    //echo "Tasa del día encontrada: " . $tasa_del_dia . "<br>"; // Mensaje de depuración
} else {
    die();
}

$tasa_del_dia = (float)$tasa_del_dia;

if ($Nomina->Create_Tasa_Dola($tasa_del_dia)) {
   // echo "Tasa del día guardada exitosamente: " . $tasa_del_dia . "<br>";
} else {
   // echo "Error al guardar la tasa del día.<br>";
}
// echo "Tiempo de ejecución: " . number_format($execution_time, 2) . " segundos.<br>";
?>