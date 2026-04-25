<?php
$data = $Nomina->Vacation_Pay_Indicator();

$mount_end = 0;
$mount_ini = 0;
$average   = 0;
$anio_end  = 'Sin datos';
$anio_ini  = 'Sin datos';

if (!empty($data)) {
    $mount_end = (float)($data[0]['monto'] ?? 0);
    $mount_ini = (float)($data[1]['monto'] ?? 0); // seguro aunque solo haya 1 elemento

    // Variación respecto al período anterior (base = mount_ini)
    if ($mount_ini != 0) {
        $average = number_format((($mount_end - $mount_ini) / $mount_ini) * 100, 2);
    } elseif ($mount_end > 0) {
        $average = '100.00'; // subió desde 0
    }

    $anio_end = $data[0]['anio'] ?? 'Sin datos';
    $anio_ini = $data[1]['anio'] ?? 'Sin datos';
}

// Estado del indicador
if ($mount_end > $mount_ini)       { $estado = 'danger';  $badge = 'bg-danger';  $signo = '+'; }
elseif ($mount_end == $mount_ini)  { $estado = 'warning'; $badge = 'bg-warning'; $signo = '';  }
else                               { $estado = 'success'; $badge = 'bg-success'; $signo = '';  }

include_once 'Components/Modals/Vacation_Grap.php';
?>

<div class="indicator__content <?= $estado ?>" style="min-width: 19rem;" onclick="openGraphModal()">
    <div class="indicator__header">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
             width="24" height="24" stroke-width="2">
            <path d="M17.553 16.75a7.5 7.5 0 0 0 -10.606 0"/>
            <path d="M18 3.804a6 6 0 0 0 -8.196 2.196l10.392 6a6 6 0 0 0 -2.196 -8.196z"/>
            <path d="M16.732 10c1.658 -2.87 2.225 -5.644 1.268 -6.196c-.957 -.552 -3.075 1.326 -4.732 4.196"/>
            <path d="M15 9l-3 5.196"/>
            <path d="M3 19.25a2.4 2.4 0 0 1 1 -.25a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 1 .25"/>
        </svg>
        <span class="badge <?= $badge ?>"> <?= $signo . $average ?>%</span>
    </div>

    <div class="indicator__body">
        <small class="text-body-secondary">Total pagado por vacaciones</small>
        <h5 class="text-body-primary">
            <?= number_format($mount_end, 2) ?> $ en <?= htmlspecialchars($anio_end) ?>
        </h5>
        <small class="text-body-secondary">
            Comparado con: <?= number_format($mount_ini, 2) ?> $ en <?= htmlspecialchars($anio_ini) ?>
        </small>
    </div>
</div>

<script>
    function openGraphModal() {
        $('#VacationModal').modal('show');
    }
</script>