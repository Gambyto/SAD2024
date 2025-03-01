<?php
    $vendedores = array();
    $labels = array();
    $comisiones = array();
    
    $vendedores = $Nomina->Vendedores_Nomina();
    if (is_array($vendedores)) {
        foreach ($vendedores as $vendedor) {
            $labels[] = $vendedor['vendedor_nombre'];
            $comisiones[] = $vendedor['t_comiciones'];
        }
    }

    $totalcomiciones = array_sum($comisiones);
    $comisionmax = $Nomina->MAX_Vendedores($comisiones);

    $comi_max = $comisionmax['t_comiciones'];
    $vendedor_max = $comisionmax['vendedor_nombre'];

    $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',
    'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

    // Verifica si $vendedores tiene al menos un elemento antes de acceder a él
    $mes = isset($vendedores[1]['mes']) ? $meses[$vendedores[1]['mes'] - 1] : 'Mes no disponible';

?>

<article>
    <div class="title">

        <h2>Rendimiento de los vendedores</h2>
        <span class="badge bg-secondary"> <?php echo $mes?> </span>
        
    </button>

    
</div>

    <!-- Gráfico de pagos -->
    <div style="width: 100%; height: 25rem;">
        <canvas id="vendedoresChart"></canvas>
    </div>

    <!-- Total pagado y promedio -->
    <div class="infokpi">
        <p class="kpidata">Total pagado en el mes:  <span class="badge bg-success" id="totalPagado"> <?php echo number_format($totalcomiciones,2);?> $</span></p>
        <p class="kpidata">Comision más alta:  <span class="badge bg-success" id="promedioPagos"> <?php echo number_format($comi_max,2).'$ de '. $vendedor_max;?></span></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Datos obtenidos de la función Vendedores_Nomina()
        <?php
        $labels = json_encode($labels);
        $comisiones = json_encode($comisiones);
        ?>
        
        var labels = <?php echo $labels; ?>;
        var comisiones = <?php echo $comisiones; ?>;

        var ctx = document.getElementById('vendedoresChart').getContext('2d');
        var vendedoresChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Comisiones',
                    data: comisiones,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y', // Esto hace que el gráfico sea horizontal
                scales: {
                    x: {
                        beginAtZero: true
                    }
                },
                responsive: true,
            }
        });
    </script>
</article>