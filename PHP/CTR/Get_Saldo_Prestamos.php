<?php
  session_start();
  include_once '../CLASS/user_Original.php';
 
  header('Content-Type: application/json');
 
  if (!isset($_SESSION['id']) || $_SESSION['type'] !== 'Trabajador') {
      echo json_encode(['error' => 'No autorizado']);
      exit;
  }
 
  $cedula = $_SESSION['id'];
 
  $saldo_info = $Nomina->Get_Saldo_Prestamos($cedula);
  $prestamos  = $Nomina->Get_Prestamos_Activos_Trabajador($cedula);
 
  // Formatear fecha para el front
  foreach ($prestamos as &$p) {
      $p['fecha_limite'] = date('d/m/Y', strtotime($p['fecha_limite']));
  }
 
  echo json_encode([
      'limite'     => $saldo_info['limite'],
      'pendiente'  => $saldo_info['pendiente'],
      'saldo'      => $saldo_info['saldo'],
      'porcentaje' => $saldo_info['porcentaje'],
      'prestamos'  => $prestamos,
  ]);
?>