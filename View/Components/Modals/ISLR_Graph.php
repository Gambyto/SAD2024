<?php
$date = $Nomina->ISLR_Grap(); // Obtiene los datos de la base de datos
$labels = array_column($date, 'mes'); // Extrae los años
$values = array_column($date, 'monto'); // Extrae los montos

$labels = array_reverse($labels); // Invierte el orden de los años
$values = array_reverse($values); // Invierte el orden de los montos
?>

<!-- Modal para actualizar empleado -->
<div class="modal fade" id="ISLRModal" tabindex="-1" aria-labelledby="empleadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="empleadoModalLabel">Fluctuación del pago de ISLR (<?php echo $anio_end?>)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <canvas id="ISLR_GP" width="400" height="200"></canvas>
            </div>
            <div class="modal-footer">
                <!-- Pie de modal (opcional) -->
            </div>
        </div>
    </div>
</div>

<!-- Scripts necesarios -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    const ctb = document.getElementById('ISLR_GP').getContext('2d');
    const myChart = new Chart(ctb, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($meses)?>,
            datasets: [{
                label: 'Monto por mes',
                data: <?php echo json_encode($values)?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>