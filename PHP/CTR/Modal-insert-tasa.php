<script>
    $(document).ready(function() {
        <?php if (is_null($tasaDelDia)) { ?>
            $('#tasaModal').modal('show');
            <?php } ?>
            
            $('#tasaForm').on('submit', function(event) {
            event.preventDefault(); // Prevenir el envío del formulario
            
            const tasa = $('#tasaInput').val();
            
            // Aquí se debe hacer una petición AJAX para guardar la tasa en el servidor
            $.ajax({
                type: 'POST',
                url: 'guardar_tasa.php', 
                data: { tasa: tasa },
                success: function(response) {
                    // Actualizar la tasa en la sesión y cerrar el modal
                    $('#tasaModal').modal('hide');
                    alert('Tasa guardada: ' + tasa + ' Bs.');
                    $('.BCV_Tasa').text('Tasa del día: ' + tasa + ' Bs.');
                },
                error: function(xhr, status, error) {
                    alert('Error al guardar la tasa. Intente nuevamente.');
                }
            });
        });
    });
</script>