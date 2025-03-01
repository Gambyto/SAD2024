<?php // Obtener datos iniciales (sin filtro)
$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
"Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

$mesActual = date('m');
$anioActual = date('Y');

$datosDiarios = $Nomina->TasaDolar('diario', $mesActual, $anioActual);
$fechasDiarias = array();
$valoresDiarios = array();

foreach ($datosDiarios as $dato) {
    $fechasDiarias[] = date("d-m", strtotime($dato['fecha']));
    $valoresDiarios[] = $dato['tasa_del_dia'];
}

// Invertir el orden de los datos
$fechasDiarias = array_reverse($fechasDiarias);
$valoresDiarios = array_reverse($valoresDiarios);
 
$mesActual = $meses[$mesActual-1];
// Calcular la tasa más alta y el promedio de la tasa en el mes
$tasaMasAlta = max($valoresDiarios);
$promedioTasa = array_sum($valoresDiarios) / count($valoresDiarios);
?>
    <canvas id="graficoDiario" width="400" height="200"></canvas>

    <script>
// Inicializar el gráfico
const ctxDiario = document.getElementById('graficoDiario').getContext('2d');

// Obtener los valores mínimos y máximos
const valoresDiarios = <?php echo json_encode($valoresDiarios); ?>;
const minimo = Math.min(...valoresDiarios);
const maximo = Math.max(...valoresDiarios);

// Ajustar el mínimo y máximo para que sean múltiplos de 5
const minimoAjustado = Math.floor(minimo / 5) * 5 - 5;
const maximoAjustado = Math.ceil(maximo / 5) * 5 + 5;

const graficoDiario = new Chart(ctxDiario, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($fechasDiarias); ?>,
        datasets: [{
            label: 'Tasa del Dólar (Diaria)',
            data: valoresDiarios,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1,
            fill: false
        }]
    },
    options: {
        scales: {
            y: {
                min: minimoAjustado,
                max: maximoAjustado,
                beginAtZero: false
            }
        }
    }
});
    </script>
