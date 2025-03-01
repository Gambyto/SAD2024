<!-- Modal -->
<div class="modal fade" id="tasaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Ingrese la tasa del día</h1>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="tasaForm">
          <div class="mb-3">
          <label for="recipient-name" class="col-form-label">Tasa Bs:</label>
          <input type="text" class="form-control" id="tasaInput" oninput="formatInput(this)" placeholder="00.00" required>
          </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-outline-primary">Guardar</button>
        </div>
    </form>
    </div>
  </div>
</div>

<!-- Mostrar el modal si no hay tasa del día -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="../JS/Validate-decimalnumber.js"></script>
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
                url: '../PHP/CTR/Get_Empleado.php', 
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
