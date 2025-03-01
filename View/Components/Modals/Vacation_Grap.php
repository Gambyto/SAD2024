<?php
$labels = array_column($data, 'anio'); // Extrae los años
$values = array_column($data, 'monto'); // Extrae los montos

$labels = array_reverse($labels); // Invierte el orden de los años
$values = array_reverse($values); // Invierte el orden de los montos
?>

<!-- Modal para actualizar empleado -->
<div class="modal fade" id="VacationModal" tabindex="-1" aria-labelledby="empleadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="empleadoModalLabel">Fluctuación del pago en las vacaciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <canvas id="vacationPayChart" width="400" height="200"></canvas>
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
    const cta = document.getElementById('vacationPayChart').getContext('2d');
    const vacationPayChart = new Chart(cta, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($labels); ?>, // Años
            datasets: [{
                label: 'Monto de Vacaciones',
                data: <?php echo json_encode($values); ?>, // Montos
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.1 // Curvatura de la línea
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>