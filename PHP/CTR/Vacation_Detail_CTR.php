<?php
// ../PHP/CTR/Vacation_Detail_CTR.php

include_once '../CLASS/user_Original.php';

header('Content-Type: application/json');

$anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');

$data = $Nomina->Vacation_Detail_By_Year($anio);

echo json_encode($data);
?>