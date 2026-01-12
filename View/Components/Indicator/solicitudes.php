<?php
 $solicitudes= $Nomina->Solicitudes_pendientes();
 $solicitudes = count($solicitudes);

 if($solicitudes > 10){
    $class = 'btn-outline-danger';
 }else{
    $class = 'btn-outline-warning';
 }

 if ($solicitudes == 0 ){
    $indicator = '';}
 elseif ($solicitudes > 10) 
 { $indicator = 'danger'; } 
 else  
 { $indicator = 'warning'; } 
  
?>


<div class="indicator__content <?php echo $indicator;?>" style="max-height: 10rem;">
    <div class="indicator__header">
    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  
    viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  
    stroke-linecap="round"  stroke-linejoin="round">
    <path stroke="none" d="M0 0h24v24H0z" fill="none"/> 
    <path d="M3 21l18 0" />
    <path d="M9 8l1 0" />
    <path d="M9 12l1 0" />
    <path d="M9 16l1 0" />
    <path d="M14 8l1 0" />
    <path d="M14 12l1 0" />
    <path d="M14 16l1 0" />
    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" />
</svg>
    </div>
    
    <div class="indicator__body ">
        <small class="text-body-secondary">Solicitudes de prestamos</small>
        <h5 class="text-body-primary">
            <?php
            if ($solicitudes > 0) {
                echo $solicitudes.' solicitudes pendientes';
                } else {
                    echo 'No hay solicitudes pendientes';
                    }
            ?>
        </h5>
        
        <?php if ($solicitudes != 0) {?>
        <div class="btn <?php echo $class;?> indicator_button"  onclick="solicitudes()">
            Ver solicitudes
        </div>
        <?php }?>
    </div>
     
    <?php include_once '../View/Components/Modals/Modal-Solicitudes.php';?>
</div>

<script> 
     function solicitudes() {
        $('#solicitudesPrestamos').modal('show');
    }
</script>