<?php require 'Components/Header.php'; ?>

<!-- ──────────────────────────────────────────────
     Inyectar tasa BCV como variable JS global
     (la necesita el modal de totalización para
     calcular montos en Bs en el front-end)
────────────────────────────────────────────────── -->
<script>
    window.tasaBCV = <?php echo json_encode(isset($_SESSION['TasaBCV']) ? floatval($_SESSION['TasaBCV']) : 1); ?>;
</script>

<!-- ── Botones de cabecera ── -->
<div style="display:flex; gap:1rem; align-items:center;">
    <?php include_once 'Components/Modals/Modal-TotalizarISLR.php'; ?>
</div>

</header>

<main>
    <div id="alerts"></div>

     <!-- ══════════════════════════════════════
          FORMULARIO DE ISLR
     ══════════════════════════════════════ -->

    <form action="" id="form">
        <div class="form">

            <!-- ══ TÍTULO Y ACCIONES ══ -->
            <div class="block Name">
                <h2>Retención de Impuestos Sobre la Renta (ISLR)</h2>

                <div class="buttons">
                    <input type="button" value="Guardar" class="btn btn-outline-success"
                           name="guardar" onclick="Guardar()">
                    <input type="reset"  value="Nuevo"   class="btn btn-outline-danger"
                           name="reset"  onclick="limpiarFormularioISLR()">
                </div>
            </div>

            <!-- ══ DATOS DEL EMPLEADO ══ -->
            <div class="block item-1">
                <h4>Datos del empleado</h4>

                <!-- Cédula + botón buscar empleado -->
                <div class="empleados__content">
                    <label>Cédula</label>
                    <div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control" id="cedula" name="cedula"
                               maxlength="8" autocomplete="off"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                               onkeyup="buscarEmpleadoISLR(this.value)">

                        <!-- Botón que abre el modal de empleados -->
                        <button class="btn btn-outline-secondary" type="button"
                                data-bs-toggle="modal" data-bs-target="#modalBuscarEmpleadoISLR"
                                title="Ver empleados">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                 fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6s-1 0-1-1
                                         1-4 6-4 5 3 5 4-1 1-1 1H1z"/>
                                <path d="M13.5 5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-5
                                         a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                                <path fill-rule="evenodd"
                                      d="M13.5 5H10a.5.5 0 0 0 0 1h3.5a.5.5 0 0 0 0-1zm0
                                         2H10a.5.5 0 0 0 0 1h3.5a.5.5 0 0 0 0-1z"/>
                            </svg>
                        </button>

                        <input type="hidden" id="cedula1" name="cedula1">
                    </div>
                </div>

                <!-- Nombre / Apellido -->
                <div class="empleados__content" style="display:flex; gap:1rem;">
                    <div class="a1">
                        <label>Nombres</label>
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control" id="nombre"
                                   name="nombre" readonly>
                        </div>
                    </div>
                    <div class="a1">
                        <label>Apellidos</label>
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" class="form-control" id="apellido"
                                   name="apellido" readonly>
                        </div>
                    </div>
                </div>

                <!-- Cargo -->
                <div class="empleados__content">
                    <label>Cargo</label>
                    <div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control" id="cargo"
                               name="cargo" readonly>
                    </div>
                </div>
            </div>

            <!-- ══ SUELDO ══ -->
            <div class="block item-2">
                <h4>&nbsp;</h4>
                <div class="empleados__content" style="display:flex; gap:1rem;">
                    <div class="a1">
                        <label>Sueldo</label>
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control" id="sueldo"
                                   name="sueldo" readonly>
                        </div>
                    </div>
                    <div class="a1">
                        <label>&nbsp;</label>
                        <div class="input-group input-group-sm mb-3">
                            <span class="input-group-text">Bs.</span>
                            <input type="text" class="form-control" id="sueldobs"
                                   name="sueldobs" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══ RETENCIÓN ══ -->
            <div class="block item-3">
                <h4>Retención</h4>

                <div class="empleados__content">
                    <label>Porcentaje de retención</label>
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text">%</span>
                        <!--
                            Campo de solo lectura — se rellena automáticamente
                            al traer el empleado (2% general / 3% Gerente).
                        -->
                        <input type="text" class="form-control bg-light" id="reten"
                               name="reten" readonly
                               style="cursor:default;">
                    </div>
                </div>
            </div>

            <!-- ══ RESULTADO ══ -->
            <div class="block item-4">
                <div class="empleados__content-info">
                    <h6 class="info">Monto retenido</h6><br>
                    <p id="aporte">Bs 0.00</p>
                    <input type="hidden" id="aporte1" name="aporte1">
                    <input type="hidden" id="op"      name="op"     value="4">
                </div>
            </div>

            <!-- ══ TABLA ══ -->
            <div class="block item-5">
                <?php include_once 'Components/Tables/Tablas-ISLR.php'; ?>
            </div>
        </div><!-- /.form -->
    </form>


</main>

<!-- ══════════════════════════════════════
     MODAL: BUSCAR EMPLEADO (para ISLR)
════════════════════════════════════════ -->
<div class="modal fade" id="modalBuscarEmpleadoISLR" tabindex="-1"
     aria-labelledby="modalBuscarEmpleadoISLRLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="min-height:540px; max-height:540px;">

            <div class="modal-header">
                <h5 class="modal-title" id="modalBuscarEmpleadoISLRLabel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                         fill="currentColor" class="bi bi-people-fill me-2" viewBox="0 0 16 16">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        <path fill-rule="evenodd"
                              d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75
                              1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                        <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                    </svg>
                    Buscar empleado
                </h5>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-0" style="display:flex; flex-direction:column; overflow:hidden;">

                <!-- Buscador fijo -->
                <div class="px-3 pt-3 pb-2 flex-shrink-0">
                    <input type="text" id="filtroISLRModal" class="form-control form-control-sm"
                           placeholder="Filtrar por nombre o cédula..." autocomplete="off" maxlength="20">
                </div>

                <!-- Estado de carga -->
                <div id="islrModalLoader" class="text-center py-4 flex-shrink-0">
                    <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                    <span class="ms-2 text-muted small">Cargando empleados...</span>
                </div>

                <!-- Mensaje vacío -->
                <div id="islrModalVacio" class="text-center py-4 d-none flex-shrink-0">
                    <p class="text-muted small mb-0">No hay empleados registrados.</p>
                </div>

                <!-- Lista scrolleable -->
                <ul class="list-group list-group-flush" id="listaEmpleadosISLR"
                    style="overflow-y:auto; flex:1;"></ul>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary"
                        data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>

<?php include_once 'Components/Footer.php'; ?>

<script src="../JS/Validate-decimalnumber.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- ══════════════════════════════════════
     SCRIPTS PRINCIPALES DE ISLR
════════════════════════════════════════ -->
<script>
/* ----------------------------------------------------------------
   Determina el porcentaje de retención según el cargo
---------------------------------------------------------------- */
function porcentajePorCargo(cargo) {
    return (cargo || '').trim().toLowerCase() === 'gerente' ? 3 : 2;
}

/* ----------------------------------------------------------------
   Limpia el formulario
---------------------------------------------------------------- */
function limpiarFormularioISLR() {
    $('#cedula').val('');
    $('#cedula1').val('');
    $('#nombre').val('');
    $('#apellido').val('');
    $('#cargo').val('');
    $('#sueldo').val('');
    $('#sueldobs').val('');
    $('#reten').val('');
    $('#aporte').text('Bs 0.00');
    $('#aporte1').val('');
    // No limpiamos #alerts aquí — la alerta debe seguir visible después de guardar
}

/* ----------------------------------------------------------------
   Buscar empleado por cédula (onkeyup)
---------------------------------------------------------------- */
function buscarEmpleadoISLR(cedula) {
    if (cedula.length >= 7 && cedula.length <= 8) {
        // Al buscar un empleado nuevo, sí limpiamos la alerta anterior
        $('#alerts').html('');
        $.ajax({
            url: '../PHP/CTR/Search_General.php',
            type: 'POST',
            data: {
                cedula: cedula,
                op: 3,
                tasa: window.tasaBCV
            },
            success: function (response) {
                var datos;
                try { datos = JSON.parse(response); } catch(e) { datos = null; }

                if (datos && datos.cedula) {
                    rellenarFormularioISLR(datos);
                } else {
                    limpiarFormularioISLR();
                    $('#cedula').val(cedula); // conservar lo escrito
                }
            },
            error: function () {
                alert('Error en la búsqueda del empleado. Intente nuevamente.');
            }
        });
    } else {
        /* Limpiar si la cédula es demasiado corta */
        var cedulaActual = $('#cedula').val();
        limpiarFormularioISLR();
        $('#cedula').val(cedulaActual);
    }
}

/* ----------------------------------------------------------------
   Rellena todos los campos y calcula automáticamente
---------------------------------------------------------------- */
function rellenarFormularioISLR(datos) {
    var pct     = porcentajePorCargo(datos.cargo);
    var sueldoD = parseFloat(datos.sueldo) || 0;
    var sueldoBs= parseFloat(datos.sueldobs) || 0;
    var monto   = (sueldoD * window.tasaBCV * pct) / 100;

    $('#cedula1').val(datos.cedula);
    $('#nombre').val(datos.nombre);
    $('#apellido').val(datos.apellido);
    $('#cargo').val(datos.cargo);
    $('#sueldo').val(sueldoD.toFixed(2));
    $('#sueldobs').val(sueldoBs.toFixed(2));
    $('#reten').val(pct);

    /* Actualizar campo de monto */
    $('#aporte').text('Bs ' + monto.toFixed(2));
    $('#aporte1').val(monto.toFixed(2));
}

/* ----------------------------------------------------------------
   Guardar aporte ISLR
---------------------------------------------------------------- */
function Guardar() {
    var cedula = $('#cedula1').val();
    var aporte = $('#aporte1').val();

    // Limpiar alerta anterior al iniciar una nueva acción
    $('#alerts').html('');


    var formData = $('#form').serialize();
    $.ajax({
        url: '../PHP/CTR/SaveResult_CTR.php',
        type: 'POST',
        data: formData,
        success: function (response) {
            try {
                var data = JSON.parse(response);
                if (data.html) {
                    // Mostrar la alerta primero
                    $('#alerts').html(data.html);

                     // Elimina la alerta del DOM cuando termina su animación
                    $('#alerts .notification').one('animationend', function() {
                        $('#alerts').empty();
                    });

                    // Si fue exitoso (no es un error), limpiar el formulario
                    // pero SIN tocar #alerts
                    var esError = data.html.indexOf('alert-danger') !== -1;
                    if (!esError) {
                        limpiarFormularioISLR();
                        if (typeof refrescarTablaISLR === 'function') refrescarTablaISLR();
                    }
                } else {
                    alert(data.message || 'Respuesta inesperada del servidor.');
                }
            } catch (e) {
                alert('Error al procesar la respuesta del servidor.');
            }
        },
        error: function () {
            alert('Error en la conexión al servidor. Intente nuevamente.');
        }
    });
}


/* ================================================================
   MODAL: BUSCAR EMPLEADO (para ISLR)
================================================================ */
$(document).ready(function () {

    /* Array paralelo para filtrar — no depende de data-attributes ni del DOM */
    var indiceEmpleados = [];

    $('#modalBuscarEmpleadoISLR').on('show.bs.modal', function () {
        cargarListaEmpleadosISLR();
    });

    $('#filtroISLRModal').on('input', function () {
        var termino = $(this).val().toLowerCase().trim();
        $('#listaEmpleadosISLR li').each(function (i) {
            var entrada = indiceEmpleados[i] || '';
            $(this).toggle(termino === '' || entrada.includes(termino));
        });
    });

    function cargarListaEmpleadosISLR() {
        var $lista  = $('#listaEmpleadosISLR').empty();
        var $loader = $('#islrModalLoader').removeClass('d-none');
        var $vacio  = $('#islrModalVacio').addClass('d-none');
        $('#filtroISLRModal').val('');
        indiceEmpleados = [];

        $.ajax({
            url: '../PHP/CTR/Search_General.php',
            type: 'POST',
            data: { op: 6 },
            success: function (raw) {
                $loader.addClass('d-none');
                var empleados;
                try { empleados = JSON.parse(raw); } catch(e) { empleados = []; }

                if (!Array.isArray(empleados) || !empleados.length) {
                    $vacio.removeClass('d-none');
                    return;
                }

                empleados.forEach(function (emp) {
                    var pct    = porcentajePorCargo(emp.cargo);
                    var nombre = (emp.nombre + ' ' + emp.apellido).toLowerCase();
                    var cedula = String(emp.cedula);

                    /* Guardar texto de búsqueda en el índice paralelo */
                    indiceEmpleados.push(nombre + ' ' + cedula);

                    var $item = $(
                        '<li class="list-group-item list-group-item-action d-flex ' +
                        'justify-content-between align-items-center py-2 px-3"' +
                        ' style="cursor:pointer;">' +
                        '<div>' +
                        '<span class="fw-semibold small">' + emp.nombre + ' ' + emp.apellido + '</span><br>' +
                        '<span class="text-muted" style="font-size:.78rem;">' + (emp.cargo || '') +
                        ' &nbsp;·&nbsp; ' + pct + '%</span>' +
                        '</div>' +
                        '<span class="badge bg-secondary rounded-pill" style="font-size:.75rem;">' +
                        cedula + '</span>' +
                        '</li>'
                    );

                    $item.on('click', function () {
                        seleccionarEmpleadoISLR(emp.cedula);
                    });

                    $lista.append($item);
                });
            },
            error: function () {
                $loader.addClass('d-none');
                $vacio.removeClass('d-none');
            }
        });
    }

}); // document.ready — modal buscar empleado

function seleccionarEmpleadoISLR(cedula) {
    $('#modalBuscarEmpleadoISLR').one('hidden.bs.modal', function () {
        limpiarFormularioISLR();
        $('#cedula').val(cedula);
        buscarEmpleadoISLR(cedula);
    });
    $('#modalBuscarEmpleadoISLR').modal('hide');
}
</script>