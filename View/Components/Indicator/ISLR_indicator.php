<?php
$data = $Nomina->ISLR_Indicator();

// Convierte el mes de número a mes
$meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

if (empty($data)) {
  $mount_end = 0;
  $mount_ini = 0;
  $average = 0;
  $anio_end = 0;
  $anio_ini = 0;
  $mes_end = 'Sin datos';
  $mes_ini = 'Sin datos';
} else {
    if (count($data) == 1) {
        $data[1]['monto'] = 0;
        $mes_ini = 'Sin datos';
        $anio_ini = 'Sin datos';
    }else {
        $mount_ini = $data[1]['monto'];
        $anio_ini = $data[1]['anio'];
        $mes_ini = $data[1]['mes'];
        $mes_ini = $meses[$mes_ini - 1 ];    
    } 
    $mount_end = $data[0]['monto']; 
    $average = number_format((($mount_end - $mount_ini) / $mount_end) * 100 , 2);
    
    $mes_end = $data[0]['mes'];
    $anio_end = $data[0]['anio'];
    $mes_end = $meses[$mes_end - 1];
}

include_once 'Components/Modals/ISLR_Graph.php';
?>

<div class="indicator__content <?php if ($mount_end > $mount_ini) { echo 'danger'; } elseif ($mount_end == $mount_ini) { echo 'warning'; } else { echo 'success'; } ?>" style="min-width: 19rem;" onclick="openGraphModalISLR()">

    <div class="indicator__header">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
    <path d="M8.487 21h7.026a4 4 0 0 0 3.808 -5.224l-1.706 -5.306a5 5 0 0 0 -4.76 -3.47h-1.71a5 5 0 0 0 -4.76 3.47l-1.706 5.306a4 4 0 0 0 3.808 5.224"></path>
    <path d="M15 3q -1 4 -3 4t -3 -4z"></path>
    <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5"></path>
    <path d="M12 10v1"></path>
    <path d="M12 17v1"></path>
    </svg>
    <?php
    if ($mount_end > $mount_ini) {
        echo '<span class="badge bg-danger"> +'. $average. '%</span>';
    } elseif ($mount_end == $mount_ini) {
        echo '<span class="badge bg-warning"> '.$average.'%</span>';
    } else {
        echo '<span class="badge bg-success"> '. $average. '%</span>';
    }
    ?>
    </div>
    
    <div class="indicator__body">
        <small class="text-body-secondary">Total Aportado al ISLR</small>
        <h5 class="text-body-primary"><?php
        echo number_format($mount_end,2). ' Bs. En '. $mes_end. ' - '. $anio_end; 
        ?></h5>
        <small class="text-body-secondary"> Comparado con: <?php echo number_format($mount_ini,2). ' Bs. En '. $mes_ini. ' - '.$anio_ini; ?></small>
    </div>

</div>

<script>
    function openGraphModalISLR() {
        $('#ISLRModal').modal('show');
    }
</script>