<?php
$promedio = $Empleado->PromedioPrestamos();
$promedio = number_format($promedio, 2);

if ($promedio < 41 ){
    $indicator = 'success';}
 elseif ($promedio > 40 && $promedio <= 60) 
 { $indicator = 'warning'; } 
 else  
 { $indicator = 'danger'; } 
?>

<div class="indicator__content <?php echo $indicator; ?>">
    <div class="indicator__header">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" width="24" height="24" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" stroke="currentColor">
    <path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"></path>
    <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"></path>
    </svg>
    </div>
    
    <div class="indicator__body">
        <small class="text-body-secondary">Tasa de uso</small>
        <h5 class="text-body-primary"><?php
        echo $promedio. '% han usado prestamos'; // Muestra el promedio mensual
        ?></h5>
    </div>

</div>