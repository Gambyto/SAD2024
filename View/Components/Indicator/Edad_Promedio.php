<?php
    $edad = $Empleado->calcularPromedioEdad();
    $edad = number_format($edad, 0);

?>


<div class="indicator__content" style="max-width: 20rem;">
    <div class="indicator__header">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" 
    stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"  stroke-width="2"> 
        <path d="M11 21l-1 -4l-2 -3v-6"></path> 
        <path d="M5 14l-1 -3l4 -3l3 2l3 .5"></path> 
        <path d="M8 4m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path> 
        <path d="M7 17l-2 4"></path> 
        <path d="M16 21v-8.5a1.5 1.5 0 0 1 3 0v.5"></path> 
    </svg> 
    </div>
    
    <div class="indicator__body">
        <small class="text-body-secondary">Promedio de edades</small>
        <h5 class="text-body-primary"><?php echo $edad .' Años '; ?></h5>
    </div>

</div>