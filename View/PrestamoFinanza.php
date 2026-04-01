<?php require 'Components/Header.php';?>
<?php if (isset($_SESSION['TasaBCV'])) { ?>
    <div style="display: flex; gap:1rem;">

    <a onclick="aporte()"
    class="btn btn-success"> Aportar adelanto  
        <svg  xmlns="http://www.w3.org/2000/svg"  
        width="24"  
        height="24"  
        viewBox="0 0 24 24"  
        fill="none"  
        stroke="currentColor"  
        stroke-width="2"  stroke-linecap="round"  
        stroke-linejoin="round"  
        class="icon icon-tabler icons-tabler-outline icon-tabler-circle-plus">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
            <path d="M9 12h6" />
            <path d="M12 9v6" />
        </svg>
    </a>

    <a onclick="solicitudes()"
    class="btn btn-primary"> Solicitudes  
        <svg  xmlns="http://www.w3.org/2000/svg"  
        viewBox="0 0 24 24"  
        fill="none"  
        stroke="currentColor"  
        stroke-width="2"  
        stroke-linecap="round"  
        stroke-linejoin="round"  
        class="icon icon-tabler icons-tabler-outline icon-tabler-list-details">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M13 5h8" />
            <path d="M13 9h5" />
            <path d="M13 15h8" />
            <path d="M13 19h5" />
            <path d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
            <path d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
        </svg>
    </a>

    <a onclick="openModal()"
        class="btn btn-primary"> Historico de préstamos  
        <svg  xmlns="http://www.w3.org/2000/svg"  
        viewBox="0 0 24 24"  
        fill="none"  
        stroke="currentColor"  
        stroke-width="2"  
        stroke-linecap="round"  
        stroke-linejoin="round"  
        class="icon icon-tabler icons-tabler-outline icon-tabler-list-details">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M13 5h8" />
            <path d="M13 9h5" />
            <path d="M13 15h8" />
            <path d="M13 19h5" />
            <path d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
            <path d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
        </svg>
    </a>
</div>

    <?php  
    include_once 'Components/Modals/Modal-Historico-Prestamo.php';  
    include_once 'Components/Modals/Modal-Solicitudes.php';  
    include_once 'Components/Modals/Modal-Aporte-Prestamo.php';  
    }  ?>
</header>

    <main>
        <form id="form"
        method="POST" class="empleados needs-validation" novalidate>
            <div class="block Name">
                <h2> Préstamos </h2>
        </div>

        <div class="block form-1">
            <h4> Registrar préstamos</h4>
            <div class="empleados__content" style="display: flex; gap: 1rem;">
                <div>
                    <label for="cedula" class="form-label">Cédula</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="cedula" name="cedula"
                            maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                            onchange="buscarUsuario(this.value)">
                    
                        <?php
                        $modalBuscarConfig = [
                            'op'       => 6,
                            'modalId'  => 'modalBuscarEmpPtm',
                            'filtroId' => 'filtroBuscarEmpPtm',
                            'listaId'  => 'listaBuscarEmpPtm',
                            'loaderId' => 'loaderBuscarEmpPtm',
                            'vacioId'  => 'vacioBuscarEmpPtm',
                            'titulo'   => 'Buscar empleado',
                            'onSelect' => 'seleccionarEmpleadoPtm',
                        ];
                        include_once 'Components/Modals/Modal-BuscarEmpleadoGeneral.php';
                        ?>
                    
                        <input type="hidden" name="cedula1" id="cedula1">
                    </div>
                </div>

                <div>
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control bg-light" id="nombre" name="nombre" required 
                    readonly
                    oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" >
                </div>

                <div>
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control bg-light" id="apellido" name="apellido" required
                    readonly
                    oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                </div>

            </div>
           
            <div class="empleados__content" style="display: flex; gap: 1rem;">
            <div class="a1">
                <label for="monto" class="form-label">Monto</label>
                <div class="input-group has-validation">
                    <span class="input-group-text" id="inputGroupPrepend">$</span>
                    <input type="text" class="form-control" id="monto" 
                    name="monto" maxlength="7"
                    oninput="formatInput(this); updateCuotas(this.value)"
                    required>
                    <div class="invalid-feedback">
                        Monto requerido.
                    </div>
                </div>
            </div>

            <div class="a1">
                <label for="cuotas" class="form-label">Cuotas</label>
                <select class="form-control" id="cuotas" name="cuotas"
                onchange="updateCampos()"
                required>
                    <option value="">Seleccione una opción</option>
                </select>
            </div>
            </div>

            <div class="empleados__content">
                <div class="a2">
                    <label for="descuento" class="form-label">Monto a descontar por cuota</label>
                    <div class="input-group has-validation"> 
                        <span class="input-group-text" id="inputGroupPrepend">$</span>
                        <input type="text" class="form-control bg-light"
                        name="descuento" id="descuento" readonly>
                    </div>
                </div>
            </div>

            <div class="empleados__content" style="display: flex; gap: 1rem;">
                <div class="a2">	
                    <label for="fechasolicitud" class="form-label">Fecha de solicitud</label>
                    <div class="input-group has-validation">
                        <input type="date" class="form-control bg-light" 
                        id="fechasolicitud" name="fechasolicitud" 
                        aria-describedby="inputGroupPrepend" required readonly>
                    </div> 
                </div>

                <div class="a2">
                    <label for="fechalimite" class="form-label">Fecha limite del pago</label>
                    <div class="input-group has-validation">
                        <input type="date" class="form-control bg-light" id="fechalimite" 
                        name="fechalimite" 
                        aria-describedby="inputGroupPrepend"
                         required readonly 
                        onchange="dateinterval(this.value)">
                    </div> 
                </div>
            </div>


            <div class="empleados__content">
                <label for="info" class="form-label">Descripción</label>
                <input type="text" class="form-control" name="info" id="info">
                <input type="hidden" id="op" name="op" value="7">
                <input type="hidden" id="f_ingreso" name="f_ingreso"> 
            </div>
        
            <div class="col-12">
                <button class="btn btn-outline-primary" type="button" onclick="Guardar()">Registrar</button>
                <button class="btn btn-outline-danger" type="reset">Cancelar</button>
            </div>

        <script src="../JS/Close_modal.js"></script>
        <script src="../JS/validation-empleado.js"></script>
        <script src="../JS/Validate-decimalnumber.js"></script>
        <script src="../JS/Get-Empleado.js"></script>


        <div id="alerts"></div>
        </div> 
        
        <div class="block indicator">
        <?php include_once 'Components/Indicator/PromedioPrestamos.php'; ?>
        <?php include_once 'Components/Indicator/Tasadeuso.php'; ?>
        <?php include_once 'Components/Indicator/Tasa_de_reembolso.php'; ?>
        <?php include_once 'Components/Indicator/Frecuencia_Renovación.php'; ?>
        </div>

        <div class="block info">
        <?php include_once 'Components/Tables/Tablas-Prestamos.php'; ?>
        </div>

        </form>
    </main>
    
    <script>
    function seleccionarEmpleadoPtm(cedula) {
    $('#cedula').val(cedula);
    buscarUsuario(cedula);    // función existente en PrestamoFinanza.php
    }

    function openModal() {
        $('#historicoPrestamosModal').modal('show');
    }

    function solicitudes() {
        $('#solicitudesPrestamos').modal('show');
    }

    function aporte() {
        $('#aporte').modal('show');
    }

function filtrarTabla(cedula) {
    // Obtener la tabla
    var tabla = document.getElementById('cuerpoTabla');

    // Obtener las filas de la tabla
    var filas = tabla.getElementsByTagName('tr');

    // Filtrar las filas
    for (var i = 0; i < filas.length; i++) {
        var fila = filas[i];
        var celdaCedula = fila.cells[0];
        var textoCedula = celdaCedula.textContent;

        // Si la cédula coincide, mostrar la fila
        if (textoCedula.includes(cedula)) {
            fila.style.display = '';
        } else {
            // Si no coincide, ocultar la fila
            fila.style.display = 'none';
        }
    }
}

    function buscarUsuario(cedula) {
        if (cedula.length >= 7 && cedula.length <= 8) { // Asegúrate de que la cédula tenga 8 dígitos
            $.ajax({
                url: '../PHP/CTR/User_CTR.php', // Cambia esto por la ruta a tu script PHP
                type: 'POST',
                data: { cedula: cedula },
                success: function(response) {
                    // Suponiendo que la respuesta es un objeto JSON con nombre y apellido
                    const datos = JSON.parse(response);
                    if (datos) {
                        $('#nombre').val(datos.nombre);
                        $('#cedula1').val(datos.cedula);
                        $('#apellido').val(datos.apellido);
                        $('#f_ingreso').val(datos.f_ingreso);
                    } else {
                        // Si no se encuentra el usuario, puedes limpiar los campos o mostrar un mensaje
                        $('#nombre').val('');
                        $('#cedula1').val('');
                        $('#apellido').val('');
                    }
                },
                error: function() {
                    alert('Error en la búsqueda del usuario. Intente nuevamente.');
                }
            });
        } else {
            // Limpiar los campos si la cédula no tiene 8 dígitos
            $('#nombre').val('');
            $('#apellido').val('');
        }
    }

   function updateCuotas(monto) {
    const cuotasSelect = document.getElementById('cuotas');
    cuotasSelect.innerHTML = '';

    const cuotasOptions = [
        { min: 0, max: 50, options: [4] },
        { min: 50, max: 150, options: [4, 12] },
        { min: 150, max: 250, options: [4, 12, 24] },
        { min: 250, max: 2001, options: [4, 12, 24, 48] }
    ];

    const cuotasRange = cuotasOptions.find(range => monto >= range.min && monto < range.max);

    if (cuotasRange) {
        cuotasRange.options.forEach(option => {
            cuotasSelect.innerHTML += `<option value="${option}">${option}</option>`;
        });
        // Selecciona la primera opción por defecto
        cuotasSelect.value = cuotasRange.options[0];
        // Llama a la función updateCampos() para actualizar los campos
        updateCampos();
    }
}

function updateCampos() {
    const monto = document.getElementById('monto').value;
    const cuotas = document.getElementById('cuotas').value;
    const descuento = document.getElementById('descuento');
    descuento.value = (monto / cuotas).toFixed(2);

    const fechaSolicitud = document.getElementById('fechasolicitud');
    const fechaHoy = new Date();
    fechaSolicitud.value = fechaHoy.toISOString().split('T')[0];

    const fechaLimite = document.getElementById('fechalimite');
    const semanas = parseInt(cuotas);
    const fechaLimiteDate = new Date(fechaHoy.getTime() + semanas * 7 * 24 * 60 * 60 * 1000);
    fechaLimite.value = fechaLimiteDate.toISOString().split('T')[0];
}


function Guardar(){
    const formData = $('#form').serialize();
    $.ajax({
        url: '../PHP/CTR/SaveResult_CTR.php', 
        type: 'POST',
        data: formData,
        success: function(response) {
            console.log(response);
            if (response) {
                var data = JSON.parse(response);
                
                if (data.html) {
                    $('#alerts').html(data.html);

                    // Reset solo si NO es un error
                    if (!data.html.includes('notification--failure')) {
                        $('#form')[0].reset();
                    }

                } else {
                    alert(data.message);
                }
            } else {
                alert('Error al guardar los datos. Intente nuevamente.');
            }
        },
        error: function() {
            alert('Error en la conexión al servidor. Intente nuevamente.');
        }
    });
};
    </script>