<?php
$data = $Nomina->ISLR_Indicator();

$meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
          'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

$mount_end = 0;
$mount_ini = 0;
$average   = '0.00';
$anio_end  = 'Sin datos';
$anio_ini  = 'Sin datos';
$mes_end   = 'Sin datos';
$mes_ini   = 'Sin datos';

if (!empty($data)) {
    $mount_end = (float)$data[0]['monto'];
    $mes_end   = $meses[((int)$data[0]['mes']) - 1] ?? 'Sin datos';
    $anio_end  = $data[0]['anio'];

    if (isset($data[1])) {
        $mount_ini = (float)$data[1]['monto'];
        $mes_ini   = $meses[((int)$data[1]['mes']) - 1] ?? 'Sin datos';
        $anio_ini  = $data[1]['anio'];
    }

    // Variación respecto al período anterior (base = mount_ini)
    if ($mount_ini != 0) {
        $average = number_format((($mount_end - $mount_ini) / $mount_ini) * 100, 2);
    } elseif ($mount_end > 0) {
        $average = '100.00'; // subió desde 0
    }
}

// Estado del indicador
if ($mount_end > $mount_ini)       { $estado = 'danger';  $badge = 'bg-danger';  $signo = '+'; }
elseif ($mount_end == $mount_ini)  { $estado = 'warning'; $badge = 'bg-warning'; $signo = '';  }
else                               { $estado = 'success'; $badge = 'bg-success'; $signo = '';  }

include_once 'Components/Modals/ISLR_Graph.php';
?>

<div class="indicator__content <?= $estado ?>" style="min-width: 19rem;" onclick="openGraphModalISLR()">
    <div class="indicator__header">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
             width="24" height="24" stroke-width="2">
            <path d="M8.487 21h7.026a4 4 0 0 0 3.808 -5.224l-1.706 -5.306a5 5 0 0 0 -4.76 -3.47h-1.71a5 5 0 0 0 -4.76 3.47l-1.706 5.306a4 4 0 0 0 3.808 5.224"/>
            <path d="M15 3q -1 4 -3 4t -3 -4z"/>
            <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5"/>
            <path d="M12 10v1"/><path d="M12 17v1"/>
        </svg>
        <span class="badge <?= $badge ?>"> <?= $signo . $average ?>%</span>
    </div>

    <div class="indicator__body">
        <small class="text-body-secondary">Total Aportado al ISLR</small>
        <h5 class="text-body-primary">
            <?= number_format($mount_end, 2) ?> Bs. en <?= htmlspecialchars($mes_end) ?> - <?= $anio_end ?>
        </h5>
        <small class="text-body-secondary">
            Comparado con: <?= number_format($mount_ini, 2) ?> Bs. en <?= htmlspecialchars($mes_ini) ?> - <?= $anio_ini ?>
        </small>
    </div>
</div>

<script>
    function openGraphModalISLR() {
        $('#ISLRModal').modal('show');
    }
</script>