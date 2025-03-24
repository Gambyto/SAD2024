<?php
    $timeservice = $Nomina->Time_Service();
    $fechasanterior = $Nomina->Time_ServiceAnterior();
    
    $change = $timeservice - $fechasanterior;
    $revenue = number_format(($change/$fechasanterior) * 100, 2);

    list($year, $day) = $Nomina->ConvertTimeService($timeservice);
    list($Ayear, $Aday) = $Nomina->ConvertTimeService($fechasanterior);

?>


<div class="indicator__content">
    <div class="indicator__header">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" 
        stroke="currentColor" stroke-linecap="round" 
        stroke-linejoin="round" stroke-width="2"> 
            <path d="M10.5 21h-4.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v3"></path> 
            <path d="M16 3v4"></path> 
            <path d="M8 3v4"></path> 
            <path d="M4 11h10"></path> 
            <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path> 
            <path d="M18 16.5v1.5l.5 .5"></path> 
        </svg>

        <?php
        if ($revenue > 0) {
            echo '<span class="badge bg-success">'.$revenue.'%</span>';
        } elseif ($revenue < 0) {
            echo '<span class="badge bg-danger">'.$revenue.'%</span>';
        } else {
            echo '<span class="badge bg-secondary">'.$revenue.'%</span>';
        }
            
        ?>
    </div>
    
    <div class="indicator__body">
        <small class="text-body-secondary">Tiempo de servicio</small>
        <h5 class="text-body-primary"><?php echo $year .' Años '. $day .' meses'; ?></h5>
        <small class="text-body-secondary"> Comparado con: <?php echo $Ayear .' Años '. $Aday .' meses'; ?></small>
    </div>

</div>