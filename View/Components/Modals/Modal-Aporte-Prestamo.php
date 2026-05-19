
<div class="modal fade" id="aporte" tabindex="-1"
     aria-labelledby="empleadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content overflow-hidden" style="min-height: 520px;">

            <div class="modal-header">
                <h5 class="modal-title" id="empleadoModalLabel">
                    Aportar adelanto de préstamo
                </h5>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- ══ CONTENEDOR DESLIZANTE ══════════════════════════════════
                 Tiene dos "pantallas" lado a lado dentro de un viewport
                 que solo muestra una a la vez. Se desplaza con translateX.
            ═══════════════════════════════════════════════════════════════ -->
            <div id="aporte-slider-track"
                 style="display:flex; transition: transform .3s ease; width:200%;">

                <!-- ── PANTALLA A: Formulario de aporte ── -->
                <div style="width:50%; flex-shrink:0;">
                    <div class="modal-body">
                        <form id="formaporte" class="needs-validation"
                              style="gap:1rem; display:flex; flex-direction:column;">

                            <div class="empleados__content" style="display:flex; gap:1rem; align-items:flex-end;">
                                <div>
                                    <label class="form-label">Cédula</label>
                                    <input type="text" class="form-control" id="Mcedula"
                                           name="cedula" required
                                           pattern="\d{8}" maxlength="8"
                                           oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                                           onkeyup="buscarEmpleado(this.value)">
                                </div>

                                <!-- BOTÓN que abre el panel buscador -->
                                <div>
                                    <button type="button"
                                            class="btn btn-outline-secondary"
                                            onclick="abrirBuscadorAporte()"
                                            title="Buscar empleado con préstamo activo">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                             fill="currentColor" class="bi bi-person-lines-fill"
                                             viewBox="0 0 16 16">
                                            <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6s-1
                                                     0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1z"/>
                                            <path d="M13.5 5a.5.5 0 0 1 .5.5v2a.5.5 0 0
                                                     1-.5.5h-5a.5.5 0 0 1 0-1H13V5.5a.5.5
                                                     0 0 1 .5-.5z"/>
                                            <path fill-rule="evenodd"
                                                  d="M13.5 5H10a.5.5 0 0 0 0 1h3.5a.5.5 0 0
                                                     0 0-1zm0 2H10a.5.5 0 0 0 0 1h3.5a.5.5
                                                     0 0 0 0-1z"/>
                                        </svg>
                                    </button>
                                </div>

                                <div>
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="Mnombre"
                                           name="nombre" required
                                           oninput="this.value=this.value.replace(/[^a-zA-Z]/g,'')">
                                </div>
                                <div>
                                    <label class="form-label">Apellido</label>
                                    <input type="text" class="form-control" id="Mapellido"
                                           name="apellido" required
                                           oninput="this.value=this.value.replace(/[^a-zA-Z]/g,'')">
                                </div>
                            </div>

                            <div class="empleados__content" style="display:flex; gap:1rem;">
                                <div class="a1">
                                    <label class="form-label">Monto del préstamo</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text">$</span>
                                        <input type="text" class="form-control" id="Mmonto"
                                               name="monto" maxlength="7"
                                               oninput="formatInput(this)"
                                               required readonly>
                                        <div class="invalid-feedback">Monto requerido.</div>
                                    </div>
                                </div>
                                <div class="a1">
                                    <label class="form-label">Deuda pendiente</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text">$</span>
                                        <input type="text" class="form-control" id="monto_desc"
                                               name="monto_desc" maxlength="7"
                                               oninput="formatInput(this)" required>
                                        <div class="invalid-feedback">Monto requerido.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="empleados__content" style="display:flex; gap:1rem;">
                                <div class="a2">
                                    <label class="form-label">Aporte</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="text" class="form-control"
                                               name="descuento" id="Mdescuento"
                                               onkeyup="actualizarDeudaParcial()">
                                    </div>
                                </div>
                                <div class="a2">
                                    <label class="form-label">Deuda Parcial</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="text" class="form-control"
                                               name="parcial" id="parcial" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="empleados__content" style="display:flex; gap:1rem;">
                                <div class="a2">
                                    <label class="form-label">Tipo de pago</label>
                                    <select class="form-control" id="tpago" name="tpago" required>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Pago movil">Pago móvil</option>
                                        <option value="Transferencia">Transferencia</option>
                                    </select>
                                </div>
                                <div class="a2">
                                    <label class="form-label">Referencia</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                               id="referencia" name="referencia"
                                               required readonly>
                                    </div>
                                    <input type="hidden" id="idp" name="idp">
                                </div>
                            </div>

                        </form>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-outline-primary" id="aporte-btn"
                                onclick="agg_aporte()">Aportar</button>
                        <button class="btn btn-outline-danger" id="cancel"
                                type="reset">Cancelar</button>
                    </div>
                </div>
                <!-- ── FIN PANTALLA A ── -->


                <!-- ── PANTALLA B: Buscador de empleados ── -->
                <div style="width:50%; flex-shrink:0; display:flex; flex-direction:column;">

                    <!-- Cabecera del buscador (imita modal-header) -->
                    <div class="modal-header border-bottom">
                        <button type="button" class="btn btn-sm btn-link ps-0 text-decoration-none"
                                onclick="cerrarBuscadorAporte()">
                            ← Volver al formulario
                        </button>
                        <span class="fw-semibold">Empleados con préstamo activo</span>
                    </div>

                    <div class="modal-body p-0"
                         style="display:flex; flex-direction:column; overflow:hidden; flex:1;">

                        <!-- Filtro -->
                        <div class="px-3 pt-3 pb-2 flex-shrink-0">
                            <input type="text" id="filtroBuscadorAporte"
                                   class="form-control form-control-sm"
                                   placeholder="Filtrar por nombre o cédula..."
                                   autocomplete="off" maxlength="30">
                        </div>

                        <!-- Badge -->
                        <div class="px-3 pb-2 flex-shrink-0">
                            <span class="badge bg-warning text-dark">
                                Solo empleados con deuda pendiente
                            </span>
                        </div>

                        <!-- Loader -->
                        <div id="loaderBuscadorAporte"
                             class="text-center py-4 flex-shrink-0 d-none">
                            <div class="spinner-border spinner-border-sm text-secondary"
                                 role="status"></div>
                            <span class="ms-2 text-muted small">Cargando...</span>
                        </div>

                        <!-- Vacío -->
                        <div id="vacioBuscadorAporte"
                             class="text-center py-4 d-none flex-shrink-0">
                            <p class="text-muted small mb-0">
                                No hay empleados con préstamo activo.
                            </p>
                        </div>

                        <!-- Lista -->
                        <ul class="list-group list-group-flush" id="listaBuscadorAporte"
                            style="overflow-y:auto; flex:1;"></ul>
                    </div>

                </div>
                <!-- ── FIN PANTALLA B ── -->

            </div>
            <!-- ══ FIN SLIDER TRACK ══ -->

        </div>
    </div>
</div>


<script src="../JS/Close_modal.js"></script>
<script src="../JS/phonenumbervalidate.js"></script>
<script src="../JS/Validate-decimalnumber.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
/* ════════════════════════════════════════════════════════════
   PANEL DESLIZANTE — Buscador embebido
════════════════════════════════════════════════════════════ */
var _buscadorCargado = false;

function abrirBuscadorAporte() {
    // Deslizar a pantalla B
    $('#aporte-slider-track').css('transform', 'translateX(-50%)');

    // Cargar lista solo la primera vez (el flag se setea dentro del success)
    if (!_buscadorCargado) {
        cargarEmpleadosAporte();
    }

    // Enfocar filtro
    setTimeout(function () {
        $('#filtroBuscadorAporte').focus();
    }, 320);
}

function cerrarBuscadorAporte() {
    // Volver a pantalla A
    $('#aporte-slider-track').css('transform', 'translateX(0)');
}

// Resetear al cerrar el modal principal
$('#aporte').on('hidden.bs.modal', function () {
    $('#aporte-slider-track').css('transform', 'translateX(0)');
});

function cargarEmpleadosAporte() {
    var $lista  = $('#listaBuscadorAporte').empty();
    var $loader = $('#loaderBuscadorAporte').removeClass('d-none');
    var $vacio  = $('#vacioBuscadorAporte').addClass('d-none'); // siempre ocultar al inicio
    $('#filtroBuscadorAporte').val('');

    $.ajax({
        url     : '../PHP/CTR/Search_General.php',
        type    : 'POST',
        data    : { op: 11 },
        dataType: 'json', // FIX: jQuery parsea automáticamente; evita el JSON.parse() doble que rompía la lista
        success : function (empleados) {
            $loader.addClass('d-none');

            if (!Array.isArray(empleados) || !empleados.length) {
                $vacio.removeClass('d-none');
                return;
            }

            // FIX: marcar como cargado solo cuando el AJAX tiene éxito real
            _buscadorCargado = true;

            empleados.forEach(function (emp) {
                var $item = $(
                    '<li class="list-group-item list-group-item-action d-flex ' +
                    'justify-content-between align-items-center py-2 px-3" ' +
                    'style="cursor:pointer;">' +
                        '<div>' +
                            '<span class="fw-semibold small">' +
                                emp.nombre + ' ' + emp.apellido +
                            '</span><br>' +
                            '<span class="text-muted" style="font-size:.78rem;">' +
                                (emp.cargo || '') +
                            '</span>' +
                        '</div>' +
                        '<div class="text-end">' +
                            '<span class="badge bg-secondary rounded-pill mb-1" ' +
                                  'style="font-size:.75rem;">' +
                                emp.cedula +
                            '</span><br>' +
                            '<span class="badge bg-danger rounded-pill" ' +
                                  'style="font-size:.72rem;">$ ' +
                                parseFloat(emp.monto_desc).toFixed(2) +
                            '</span>' +
                        '</div>' +
                    '</li>'
                );

                $item.on('click', function () {
                    // Llenar formulario
                    $('#Mcedula').val(emp.cedula);
                    $('#Mnombre').val(emp.nombre);
                    $('#Mapellido').val(emp.apellido);
                    $('#Mmonto').val(parseFloat(emp.monto_prestamo).toFixed(2));
                    $('#monto_desc').val(parseFloat(emp.monto_desc).toFixed(2));
                    $('#Mdescuento').val(parseFloat(emp.descuento).toFixed(2));
                    $('#idp').val(emp.id_prestamos);
                    if (typeof actualizarDeudaParcial === 'function') {
                        actualizarDeudaParcial();
                    }
                    // Volver al formulario
                    cerrarBuscadorAporte();
                });

                $lista.append($item);
            });
        },
        error: function (xhr) {
            $loader.addClass('d-none');
            $vacio.removeClass('d-none');
            // _buscadorCargado queda en false → permite reintentar al abrir de nuevo
            console.error('Error cargando empleados con préstamo:', xhr.responseText);
        }
    });
}

/* Filtro en tiempo real */
$(document).on('input', '#filtroBuscadorAporte', function () {
    var t = $(this).val().toLowerCase().trim();
    $('#listaBuscadorAporte li').each(function () {
        $(this).toggle(t === '' || $(this).text().toLowerCase().indexOf(t) > -1);
    });
});


/* ════════════════════════════════════════════════════════════
   LÓGICA ORIGINAL DEL FORMULARIO (sin cambios)
════════════════════════════════════════════════════════════ */
function cerrarModal() {
    $(this).closest('.modal').modal('hide');
}
$('#aporte-btn, #cancel').on('click', function () {
    cerrarModal.call(this);
    $('#Mnombre').val('');
    $('#Mapellido').val('');
    $('#Mmonto').val('');
    $('#monto_desc').val('');
    $('#Mdescuento').val('');
    $('#idp').val('');
    $('#parcial').val('');
});

function agg_aporte() {
    const formData = $('#formaporte').serialize();
    $.ajax({
        url : '../PHP/CTR/SaveResult_CTR.php',
        type: 'POST',
        data: formData + '&op=6',
        success: function (response) {
            if (response) {
                var data = JSON.parse(response);
                if (data.html) {
                    $('#alerts').html(data.html);
                    $('#alerts .notification').one('animationend', function () {
                        $('#alerts').empty();
                    });
                    // Recarga la tabla si el aporte fue exitoso
                    if (!data.html.includes('notification--failure')) {
                        recargarTablaPrestamos();
                    }
                } else {
                    alert(data.message);
                }
                $('#formaporte')[0].reset();
            } else {
                alert('Error al guardar los datos. Intente nuevamente.');
            }
        },
        error: function () {
            alert('Error en la conexión al servidor. Intente nuevamente.');
        }
    });
}

$('#descuento').on('change', function () { actualizarDeudaParcial(); });

$('#tpago').on('change', function () {
    const seleccionado = $(this).val();
    if (seleccionado === 'Efectivo') {
        $('#referencia').val('No aplica').prop('readonly', true)
                        .attr('placeholder', '').attr('maxlength', '');
    } else {
        $('#referencia').val('').prop('readonly', false)
                        .attr('placeholder', 'Los últimos 4 dígitos').attr('maxlength', '4');
    }
});

$('#referencia').on('keypress', function (e) {
    if (e.which !== 8 && e.which !== 0 && (e.which < 48 || e.which > 57)) return false;
});

function buscarEmpleado(cedula) {
    if (cedula.length >= 7 && cedula.length <= 8) {
        $.ajax({
            url : '../PHP/CTR/Aporte_data_CTR.php',
            type: 'POST',
            data: { cedula: cedula },
            success: function (response) {
                const datos = JSON.parse(response);
                if (datos) {
                    $('#Mnombre').val(datos.nombre);
                    $('#Mapellido').val(datos.apellido);
                    $('#Mmonto').val(datos.monto);
                    $('#monto_desc').val(datos.monto_desc);
                    $('#Mdescuento').val(datos.descuento);
                    $('#idp').val(datos.id_prestamos);
                    actualizarDeudaParcial();
                    $('#alerts').html(datos.html);
                    $('#alerts .notification').one('animationend', function () {
                        $('#alerts').empty();
                    });
                }
            },
            error: function () {
                alert('Error en la búsqueda del usuario. Intente nuevamente.');
            }
        });
    }
}

function actualizarDeudaParcial() {
    const deuda  = parseFloat($('#monto_desc').val()) || 0;
    const aporte = parseFloat($('#Mdescuento').val()) || 0;
    $('#parcial').val(Math.max(0, deuda - aporte).toFixed(2));
}
</script>