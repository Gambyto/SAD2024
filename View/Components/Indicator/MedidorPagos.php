<?php
    $pagos = $Nomina->obtenerPagosNomina(date('n'),date('Y'));
    $totalpagos = array_sum($pagos);
    $totalEmpleados = $Nomina->EmpleadosPagos(date('n'),date('Y'));
    $totalEmpleados = $totalEmpleados['cantidad_empleados'] ?? null;

    if (empty($totalEmpleados)) {
        $totalEmpleados = 1;
    }

    $promedio = $totalpagos / $totalEmpleados;

    $variacion = $Nomina->View_Variacion();
    $minAnio = null;
    $minMes = null;
    $maxAnio = null;
    $maxMes = null;
    
    foreach ($variacion as $row) {
      $Anio = $row['anio'];
      $mes = $row['mes'];
      if ($minAnio === null || ($Anio < $minAnio || ($Anio == $minAnio && $mes < $minMes))) {
        $minAnio = $Anio;
        $minMes = $mes;
      }
      if ($maxAnio === null || ($Anio > $maxAnio || ($Anio == $maxAnio && $mes > $maxMes))) {
        $maxAnio = $Anio;
        $maxMes = $mes;
      }
    }
    
    $minFecha = sprintf('%04d-%02d', $minAnio, $minMes);
    $maxFecha = sprintf('%04d-%02d', $maxAnio, $maxMes);
?>

<article>
    <div class="title">

        <h2>Pagos realizados en el mes</h2>

        <div class="filtro-mes">
        <input type="month" id="fecha" class="form-control" min="<?=$minFecha?>" max="<?=$maxFecha?>" value="<?=Date('Y-m')?>">
        </div>

        <button class="btn btn-secondary" title="variación de pagos" onclick="openModalVariacion()">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" 
            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" 
            width="24" height="24" stroke-width="2"> 
            <path d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"></path> 
            <path d="M7 8h10"></path> 
            <path d="M7 12h10"></path> 
            <path d="M7 16h10"></path> 
        </svg> 
    </button>

    <?php include_once 'Components/Modals/Modal-Variacion-Nomina.php';?>
</div>

    <!-- Gráfico de pagos -->
    <div style="width: 100%; height: 25rem;">
        <canvas id="graficoPagos"></canvas>
    </div>

    <!-- Total pagado y promedio -->
    <div class="infokpi">
        
        <p class="kpidata">Total pagado en el mes:  <span class="badge bg-success" id="totalpagado"> <?php echo number_format($totalpagos,2);?> $</span></p>
        <p class="kpidata">Promedio de pagos por empleado:  <span class="badge bg-success" id="promedioPagos"> <?php echo number_format($promedio,2);?> $</span></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
    // Obtener los pagos de la nómina desde PHP
    var pagos = <?php echo json_encode($pagos); ?>;
    var totalEmpleados = <?php echo $totalEmpleados; ?>;

    // Calcular el total pagado y el promedio
    var totalPagado = <?php echo $totalpagos;?>;
    var promedioPagos = <?php echo $promedio;?>;

    // Crear el gráfico de pagos
    var ctx = document.getElementById('graficoPagos').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar', // Tipo de gráfico: Barras verticales
        data: {
            labels: ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4'], // Etiquetas de los pagos
            datasets: [{
                label: 'Pagos del Mes',
                data: pagos, // Datos de los pagos
                backgroundColor: 'rgba(75, 192, 192, 0.8)', // Color de las barras
                borderColor: 'rgba(75, 192, 192, 1)', // Color del borde
                borderWidth: 1 // Ancho del borde
            }]
        },
        options: {
            responsive: true, // Hacer el gráfico responsivo
            maintainAspectRatio: false, // No mantener la relación de aspecto
            plugins: {
                legend: {
                    display: false, // Ocultar la leyenda
                }
            },
            scales: {
                y: {
                    beginAtZero: true // Comenzar el eje Y en cero
                }
            }
        }
    });

    function openModalVariacion() {
        $('#empleadoModal').modal('show');
    }

    function aplicarFiltro() {
    var fecha = document.getElementById('fecha').value;
    var mes = fecha.split('-')[1];
    var año = fecha.split('-')[0];

    // Llamar a la función que actualiza el gráfico y los datos de pagos
    actualizarGrafico(mes, año);
}

function actualizarGrafico(mes, año) {
    // Llamar a la función que obtiene los pagos de la nómina para el mes seleccionado y actualizar el gráfico y los datos mostrados.

    $.ajax({
        url: '../PHP/CTR/MedidorPagos_CTR.php', // Cambia esto a la ruta de tu script PHP que obtiene los pagos por mes
        type: 'POST',
        data: { mes: mes, año: año },
        success: function(response) {
            var datos = JSON.parse(response);
            console.log(response);
            // Actualizar el gráfico con los nuevos datos
            chart.data.datasets[0].data = datos.pagos;
            chart.update();

            // Actualizar el total pagado y promedio
            document.querySelector('#totalpagado').textContent = datos.totalPagado + ' $';
            document.querySelector('#promedioPagos').textContent = datos.promedioPagos + ' $';
        },
        error: function() {
            console.error('Error al obtener los datos de pagos.');
        }
    });
}
    const fechaInput = document.getElementById('fecha');

    fechaInput.addEventListener('change', () => {
    var fecha = fechaInput.value;
    var mes = fecha.split('-')[1];
    var año = fecha.split('-')[0];

    // Llamar a la función que actualiza el gráfico y los datos de pagos
    actualizarGrafico(mes, año);
    });

</script>
</article>