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
                        <label for="correoModal" class="form-label">Correo</label>
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
        function guardarCambios() {
            const empleadoData = {
                cedula: document.getElementById('cedulaModal').value,
                nombre: document.getElementById('nombreModal').value,
                apellido: document.getElementById('apellidoModal').value,
                tlf: document.getElementById('telefonoModal').value,
                second_tlf: document.getElementById('telefono2Modal').value,
                direccion: document.getElementById('direccionModal').value,
                correo: document.getElementById('correoModal').value,
                sexo: document.getElementById('sexoModal').value,
                edad: document.getElementById('edadModal').value,
                departamento: document.getElementById('departamentoModal').value,
                cargo: document.getElementById('cargoModal').value,
                ingreso: document.getElementById('fechaIngresoModal').value,
                sueldo: document.getElementById('sueldoModal').value,
                btnU : document.getElementById('btnU').value

            };

            $.ajax({
                url: '../PHP/CTR/Insert_Empleado_CTR.php', // Cambia esta URL según tu estructura de carpetas
                type: 'POST',
                data: empleadoData,
                success: function(response) {
                    alert(response);
                },
                error: function() {
                    alert('Error en la conexión al servidor.');
                }
            });
        }
</script>