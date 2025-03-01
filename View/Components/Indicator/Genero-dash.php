<?php
    $genero = $Empleado->obtenerGenero();
    $total = $genero[0] + $genero[1];
?>
<div class="indicator__content">
    <div class="indicator__header">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" 
    stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" 
    width="24" height="24" stroke-width="2"> 
        <path d="M11 11m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path> 
        <path d="M19 3l-5 5"></path> <path d="M15 3h4v4"></path> 
        <path d="M11 16v6"></path> <path d="M8 19h6"></path> 
    </svg> 
    <small class="text-body-secondary">Cantidad de empleados</small>
    <span class="badge bg-secondary"> <?php echo $total; ?> 
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="20" height="20" stroke-width="2">
        <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
    </svg>
    </span>
    </div>
    
    <div class="indicator__body">
    <div class="chart-l">
        <canvas id="myChart"></canvas>
    </div>

    <script>
        // Obtener el número de hombres y mujeres desde PHP

        var hombres = <?php echo $genero[0]; ?>;
        var mujeres = <?php echo $genero[1]; ?>;

                // Crear el gráfico de barras verticales
                var ctx = document.getElementById('myChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar', // Tipo de gráfico: Barras verticales
            data: {
                labels: ['Hombres', 'Mujeres'], // Etiquetas
                datasets: [{
                    label: 'Cantidad',
                    data: [hombres, mujeres], // Datos
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)', // Azul para hombres
                        'rgba(255, 99, 132, 0.8)'  // Rojo para mujeres
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)', // Borde azul para hombres
                        'rgba(255, 99, 132, 1)'  // Borde rojo para mujeres
                    ],
                    borderWidth: 1 // Ancho del borde
                }]
            },
            options: {
                responsive: true, // Hacer el gráfico responsivo
                maintainAspectRatio: false, // No mantener la relación de aspecto
                plugins: {
                    legend: {
                        display: false, // Ocultar la leyenda
                    },
                    tooltip: {
                        enabled: true, // Activar los tooltips
                        callbacks: {
                            label: function(context) {
                                var label = context.dataset.label || '';
                                var value = context.formattedValue;
                                var total = hombres + mujeres;
                                var porcentaje = (value / total) * 100;
                                return label + ': ' + value + ' (' + porcentaje.toFixed(2) + '%)';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true, // Comenzar el eje Y desde cero
                        ticks: {
                            stepSize: 1 // Mostrar solo números enteros en el eje Y
                        }
                    }
                }
            }
        });
    </script>
    </div>

</div>