<?php
$data = $Nomina->Balance_Prestamos();
$balance = number_format(($data[0]['total_reembolsado']/$data[0]['total_prestado']) * 100 , 2);

// Convierte el mes de número a mes
$meses = array(
    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
  );
  
  $mes = $meses[$dato[0]['mes'] - 1];

// Calcula la fecha del primer día de la semana
$fecha_inicio = new DateTime();
$fecha_inicio->setISODate($año, $semana, 1);

// Calcula la fecha del último día de la semana
$fecha_fin = new DateTime();
$fecha_fin->setISODate($año, $semana, 7);

// Formatea las fechas en el formato dd/mm/yyyy
$fecha_inicio_formateada = $fecha_inicio->format('d/m/Y');
$fecha_fin_formateada = $fecha_fin->format('d/m/Y');

if ($balance > 50 ){
    $indicator = 'success';}
 elseif ($balance <= 50 && $balance >= 31) 
 { $indicator = 'warning'; } 
 else  
 { $indicator = 'danger'; } 
?>

<div class="indicator__content <?php echo $indicator?>" style="min-width: 19rem;">
    <div class="indicator__header">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2"></path>
    <path d="M14 8h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5m2 0v1.5m0 -9v1.5"></path>
    </svg>
    
    
    </div>
    
    <div class="indicator__body">
        <small class="text-body-secondary">Tasa de reembolso de los préstamos</small>
        <h5 class="text-body-primary"><?php
        echo $balance. '% han sido pagados'; // Muestra el promedio mensual
        ?></h5>
    </div>

</div>