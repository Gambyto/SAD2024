<?php
    $pagos = $Nomina->obtenerPagosNomina(01,2025);
    $totalpagos = array_sum($pagos);
    $totalEmpleados = $Nomina->EmpleadosPagos(01,2025);
    $totalEmpleados = $totalEmpleados['cantidad_empleados'] ?? null;

    if (empty($totalEmpleados)) {
        $totalEmpleados = 1;
    }

    $promedio = $totalpagos / $totalEmpleados;
?>

<article>
    <div class="title">

        <h2>Pagos realizados en el mes</h2>
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
<div class="filtro-mes">
        <select id="mes" name="mes">
            <option value="1">Enero</option>
            <option value="2">Febrero</option>
            <option value="3">Marzo</option>
            <option value="4">Abril</option>
            <option value="5">Mayo</option>
            <option value="6">Junio</option>
            <option value="7">Julio</option>
            <option value="8">Agosto</option>
            <option value="9">Septiembre</option>
            <option value="10">Octubre</option>
            <option value="11">Noviembre</option>
            <option value="12">Diciembre</option>
        </select>
        <button id="aplicar-filtro" onclick="aplicarFiltro()">Aplicar filtro</button>
    </div>
    <!-- Gráfico de pagos -->
    <div style="width: 100%; height: 25rem;">
        <canvas id="graficoPagos"></canvas>
    </div>

    <!-- Total pagado y promedio -->
    <div class="infokpi">
        <p class="kpidata">Total pagado en el mes:  <span class="badge bg-success" id="totalPagado"> <?php echo number_format($totalpagos,2);?> $</span></p>
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
        var mes = document.getElementById('mes').value;
        // Llamar a la función que actualiza el gráfico y los datos de pagos
        actualizarGrafico(mes);
    }

    function actualizarGrafico(mes) {
        // Llamar a la función que obtiene los pagos de la nómina para el mes seleccionado y actualizar el gráfico y los datos mostrados.

        $.ajax({
            url: '../PHP/CTR/MedidorPagos_CTR.php', // Cambia esto a la ruta de tu script PHP que obtiene los pagos por mes
            type: 'POST',
            data: { mes: mes },
            success: function(response) {
                var datos = JSON.parse(response);
                console.log(response);
 // Actualizar el gráfico con los nuevos datos
                chart.data.datasets[0].data = datos.pagos;
                chart.update();

                // Actualizar el total pagado y promedio
                document.getElementById('totalPagado').innerText = datos.totalPagado + ' $';
                document.getElementById('promedioPagos').innerText = datos.promedioPagos + ' $';
            },
            error: function() {
                console.error('Error al obtener los datos de pagos.');
            }
        });
    }
    </script>
</article>