<!-- Modal para actualizar empleado -->
<div class="modal fade" id="empleadoModal" tabindex="-1" aria-labelledby="empleadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="empleadoModalLabel">Actualizar Empleado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="FormEmpleadoModal" class="needs-validation" method="POST" novalidate>

                <div class="empleados__content" style="display: flex; gap: 1rem;">
                    <div class="mb-3">
                        <label for="cedulaModal" class="form-label">Cédula</label>
                        <input type="text" class="form-control" id="cedulaModal" 
                        required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nombreModal" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombreModal" 
                        oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                        required>
                    </div>
                    <div class="mb-3">
                        <label for="apellidoModal" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellidoModal" 
                        oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')"
                        required>
                    </div>
                </div>
                
                <div class="empleados__content" style="display: flex; gap: 1rem;">
                    <div class="mb-3 a1">
                        <label for="telefonoModal" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefonoModal" required 
                        placeholder="0424-1234567" 
                        oninput="formatPhoneNumber(this)">
                    </div>
                    <div class="mb-3 a1">
                        <label for="telefonoModal" class="form-label">Teléfono adicional</label>
                        <input type="text" class="form-control" id="telefono2Modal" 
                        placeholder="0424-1234567" 
                        oninput="formatPhoneNumber(this)">
                    </div>
                </div>
                
                <div class="empleados__content" style="display: flex; gap: 1rem;">
                    <div class="mb-3 a1">
                        <label for="direccionModal" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccionModal" required>
                    </div>
                </div>
                
                <div class="empleados__content" style="display: flex; gap: 1rem;">
                    <div class="mb-3 a1">
                        <label for="correoModal" class="form-label">Correo</label>
                        <div class="input-group">
                            <span class="input-group-text">@</span>
                            <input type="email" class="form-control" id="correoModal" required>
                        </div>
                    </div>
                    <div class="mb-3 a1">
                        <label for="sexoModal" class="form-label">Sexo</label>
                        <select class="form-select" id="sexoModal" required>
                            <option value="H">Hombre</option>
                            <option value="M">Mujer</option>
                        </select>
                    </div>
                    <div class="mb-3 a2">
                        <label for="edadModal" class="form-label">Edad</label>
                        <input type="date" class="form-control" 
                        id="edadModal"
                        readonly 
                        required>
                    </div>
                </div>
                
                <div class="empleados__content" style="display: flex; gap: 1rem;">
                    
                    <div class="mb-3 a1">
                        <label for="departamentoModal" class="form-label">Departamento</label>
                        <select class="form-select" id="departamentoModal" required>
                            <option value="s">Seleccione...</option>
                            <option value="Gerencia" >Gerencia</option>
                            <option value="Administración"> Administración</option>
                            <option value="Contabilidad" >Contabilidad</option>
                            <option value="Almacén" >Almacén</option>
                            <option value="Ventas" >Ventas</option>
                            <option value="Operador" >Operador</option>
                        </select>
                    </div>
                    <div class="mb-3 a1">
                        <label for="cargoModal" class="form-label">Cargo</label>
                        <select class="form-select" id="cargoModal" required>
                            <option value="s">Seleccione...</option>
                            <option value="Gerente" >Gerente</option>
                            <option value="Sub gerente" >Sub gerente</option>
                            <option value="Contador"> Contador</option>
                            <option value="Aux Contable" >Aux Contable</option>
                            <option value="Almacenista" >Almacenista</option>
                            <option value="Facturador" >Facturador</option>
                            <option value="Cobranza" >Cobranza</option>
                            <option value="Vendedor" >Vendedor</option>
                        </select>
                    </div>
                    <div class="mb-3 a1">
                        <label for="fechaIngresoModal" class="form-label">Fecha de Ingreso</label>
                        <input type="date" class="form-control" 
                        id="fechaIngresoModal"
                        readonly 
                        required>
                    </div>
                </div>

                
                <div class="empleados__content" style="display: flex; gap: 1rem;">
                    <div class="mb-3 a1">
                        <label for="sueldoModal" class="form-label">Sueldo</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control" id="sueldoModal" 
                            oninput="formatInput(this)" 
                            placeholder="00.00" 
                            maxlength="7"
                            required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"  id="btnU" onclick="guardarCambios()">Guardar Cambios</button> 
            </div>
        </form>
            
           
        </div>
    </div>
</div>
        <script src="../JS/phonenumbervalidate.js"></script>
        <script src="../JS/Validate-decimalnumber.js"></script>
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