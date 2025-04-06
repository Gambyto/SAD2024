<!-- Modal para actualizar empleado -->
<div class="modal fade" id="empleadoModal" tabindex="-1" aria-labelledby="empleadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="empleadoModalLabel">Actualizar datos de usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> 
            <div class="modal-body">
            <form id="FormEmpleadoModal" class="needs-validation" method="POST" novalidate>

                <div class="empleados__content" style="display: flex; gap: 1rem;">
                    <div class="mb-3">
                        <label for="nombreModal" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombreModal" 
                        oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                        readonly>
                    </div>
                    <div class="mb-3">
                        <label for="apellidoModal" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellidoModal" 
                        oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                        readonly>
                    </div>
                    <div class="mb-3">
                        <label for="cedulaModal" class="form-label">Cédula</label>
                        <input type="text" class="form-control" id="cedulaModal" 
                        required readonly>
                    </div>
                </div>
                
                <div class="empleados__content" style="display: flex; gap: 1rem;">
                    <div class="mb-3 a1">
                        <label for="correoModal" class="form-label">Usuario</label>
                        <div class="input-group">
                            <span class="input-group-text">@</span>
                            <input type="text" class="form-control" id="correoModal" required>
                        </div>
                    </div>
                </div>


                <div class="empleados__content" style="display: flex; gap: 1rem;">
                    <div class="mb-3 a1">
                        <label for="telefonoModal" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="telefonoModal" required >
                    </div>

                    <div class="mb-3 a1">
                        <label for="telefonoModal" class="form-label">tipo de usuario</label>
                        <select class="form-select" id="telefono2Modal" name="tipo" required>
                        <option selected disabled value="">Choose...</option>
                        <option value="Gerencia">Gerencia</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Trabajador">Trabajador</option>
                    </select>
                    </div>
                </div>
                

                

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary"  id="btnU" onclick="guardarCambios()">Guardar Cambios</button> 
            </div>
        </form>
            
           
        </div>
    </div>
</div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <script>
                    // Agregar un evento de clic al botón de close del modal
        $('#empleadoModal .btn-close').on('click', function() {
            // Cerrar el modal manualmente
            $('#empleadoModal').modal('hide');
        });
        function guardarCambios() {
            const empleadoData = {
                cedula: $('#cedulaModal').val(),
                username: $('#correoModal').val(),
                pass: $('#telefonoModal').val(),
                tipo: $('#telefono2Modal').val(),
                op: 10

            };

            $.ajax({
                url: '../PHP/CTR/SaveResult_CTR.php', // Cambia esta URL según tu estructura de carpetas
                type: 'POST',
                data: empleadoData,
                success: function(response) {
                    
                },
                error: function() {
                    alert('Error en la conexión al servidor.');
                }
            });
        }
</script>