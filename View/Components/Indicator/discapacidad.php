<?php
$data = $Empleado->Discapacidad();

$cantidad = 0;

foreach ($data as $item) {
    if ($item['discapacidad'] != 'Ninguna') {
        $cantidad += $item['cantidad'];
    }
}
?>

<div class="indicator__content">
    <div class="indicator__header">
    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-disabled"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11 5m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M11 7l0 8l4 0l4 5" /><path d="M11 11l5 0" /><path d="M7 11.5a5 5 0 1 0 6 7.5" /></svg>
        <small class="text-body-secondary">Cantidad de empleados con discapacidad</small>
        <span class="badge bg-secondary"> <?php echo $cantidad; ?> </span>
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
                // Crear el gráfico de pastel
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
                'rgba(128, 0, 128, 0.8)', // Púrpura (reemplaza al azul)
                'rgba(255, 99, 132, 0.8)', // Rojo
                'rgba(255, 206, 86, 0.8)', // Amarillo
                'rgba(75, 192, 192, 0.8)', // Verde
                'rgba(0, 128, 0, 0.8)', // Verde oscuro (reemplaza al rosa)
                'rgba(255, 159, 64, 0.8)' // Naranja
            ],
            borderColor: [
                'rgba(128, 0, 128, 1)', // Púrpura (reemplaza al azul)
                'rgba(255, 99, 132, 1)', // Rojo
                'rgba(255, 206, 86, 1)', // Amarillo
                'rgba(75, 192, 192, 1)', // Verde
                'rgba(0, 128, 0, 1)', // Verde oscuro (reemplaza al rosa)
                'rgba(255, 159, 64, 1)' // Naranja
            ],
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
                                text: label + ' (' + ((value / total) * 100).toFixed(1) + '%)',
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
                        var porcentaje = ((value / total) * 100).toFixed(1);
                        return label + ': ' + value + ' (' + porcentaje + '%)';
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