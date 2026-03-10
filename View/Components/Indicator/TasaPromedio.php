<?php // Obtener datos iniciales (sin filtro)
$mes_old = date('m')-1;
if ($mes_old == 0) {
    $mes_old = 12;
    $anio_old = date('Y')-1;
} else { $anio_old = date('Y'); }

$datosDiarios_old = $Nomina->TasaDolar('diario', $mes_old, $anio_old);
$valoresDiarios_old = array();

foreach ($datosDiarios_old as $dato) {
    $valoresDiarios_old[] = $dato['tasa_del_dia'];
}

$mes_old = $meses[$mes_old-1];

$valoresDiarios_old = array_reverse($valoresDiarios_old);

$promedioTasa_old = (count($valoresDiarios_old) != 0) ? array_sum($valoresDiarios_old) / count($valoresDiarios_old) : 0;
$promedioTasa_old = number_format($promedioTasa_old, 2);
$promedioTasa = number_format($promedioTasa, 2);

$average = ($promedioTasa != 0 && $promedioTasa_old != 0) ? (($promedioTasa - $promedioTasa_old) / $promedioTasa) * 100 : 0;
$average = number_format($average, 2);
?>

<div class="indicator__content" id="tasa-promedio" style="min-width: 19rem; min-height: 10rem;">

    <div class="indicator__header">
    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-coin">
    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
    <path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1" />
    <path d="M12 7v10" />
    </svg>
    <?php
    if ($promedioTasa > $promedioTasa_old) {
        echo '<span class="badge bg-danger"> +'. $average. '%</span>';
    } elseif ($promedioTasa == $promedioTasa_old) {
        echo '<span class="badge bg-warning"> '. $average. '%</span>';
    }else {
        echo '<span class="badge bg-sucsses"> -'. $average. '%</span>';
    }
    ?>
    </div>
    
    <div class="indicator__body">
        <small class="text-body-secondary">Promedio de la tasa de cambio del dólar </small>
        <h5 class="text-body-primary"><?php
        echo $promedioTasa . ' Bs. En '. $mesActual. ' - '. $anioActual; 
        ?></h5>
        <small class="text-body-secondary"> Comparado con: <?php echo $promedioTasa_old. ' Bs. En '. $mes_old. ' - '.$anio_old; ?></small>
    </div>

</div>