<?php
$data = $Empleado->Discapacidad();
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
        <small class="text-body-secondary">Cantidad de empleados con discapacidad</small>
        <span class="badge bg-secondary"> <?php echo count($data); ?> </span>
    </div>
    
    <div class="indicator__body">
        <div class="chart-l">
            <canvas id="myChart2"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Obtener los datos desde PHP
                var data = <?php echo json_encode($data); ?>;
                console.log(data);

                // Crear el gráfico de pastel
                var ctd = document.getElementById('myChart2').getContext('2d');
                var chart = new Chart(ctd, {
                    type: 'pie', // Tipo de gráfico: Pastel
                    data: {
                        labels: data.map(function(item) { return item.discapacidad; }), // Etiquetas
                        datasets: [{
                            label: 'Cantidad',
                            data: data.map(function(item) { return item.cantidad; }), // Datos
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.8)', // Azul
                                'rgba(255, 99, 132, 0.8)', // Rojo
                                'rgba(255, 206, 86, 0.8)', // Amarillo
                                'rgba(75, 192, 192, 0.8)', // Verde
                                'rgba(153, 102, 255, 0.8)', // Morado
                                'rgba(255, 159, 64, 0.8)' // Naranja
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)', // Azul
                                'rgba(255, 99, 132, 1)', // Rojo
                                'rgba(255, 206, 86, 1)', // Amarillo
                                'rgba(75, 192, 192, 1)', // Verde
                                'rgba(153, 102, 255, 1)', // Morado
                                'rgba(255, 159, 64, 1)' // Naranja
                            ],
                            borderWidth: 1 // Ancho del borde
                        }]
                    },
                    options: {
    responsive: true, // Hacer el gráfico responsivo
    maintainAspectRatio: false, // No mantener la relación de aspecto
    plugins: {
        legend: {
            display: true, // Mostrar la leyenda
            position: 'left', // Mostrar la leyenda del lado izquierdo
            labels: {
                generateLabels: function(chart) {
                    var data = <?php echo json_encode($data); ?>;
                    var labels = [];
                    var datasets = chart.data.datasets;
                    var legendLabels = [];
                    var total = 0;

                    for (var i = 0; i < data.length; i++) {
                        var label = data[i].discapacidad;
                        var value = parseInt(data[i].cantidad);

                        total += value;

                        if (label === null || label === "Ninguna") {
                            labels.push("Ninguna");
                        } else {
                            labels.push(label);
                        }
                    }

                    for (var i = 0; i < labels.length; i++) {
                        var label = labels[i];
                        var value = 0;

                        for (var j = 0; j < data.length; j++) {
                            if (data[j].discapacidad === null || data[j].discapacidad === "Ninguna") {
                                if (label === "Ninguna") {
                                    value += parseInt(data[j].cantidad);
                                }
                            } else {
                                if (label === data[j].discapacidad) {
                                    value += parseInt(data[j].cantidad);
                                }
                            }
                        }

                        legendLabels.push({
                            text: label + ' (' + (value / total) * 100 + '%)',
                            fillStyle: datasets[0].backgroundColor[i],
                            strokeStyle: datasets[0].borderColor[i],
                            lineWidth: 2
                        });
                    }

                    return legendLabels;
                }
            }
        },
        tooltip: {
            enabled: true, // Activar los tooltips
            callbacks: {
                label: function(context) {
                    var label = context.dataset.label || '';
                    var value = context.formattedValue;
                    var total = 0;
                    for (var i = 0; i < data.length; i++) {
                        total += parseInt(data[i].cantidad);
                    }
                    var porcentaje = (value / total) * 100;
                    return label + ': ' + value + ' (' + porcentaje.toFixed(2) + '%)';
                }
            }
        }
    }
}
                });
            });
        </script>
    </div>
</div>