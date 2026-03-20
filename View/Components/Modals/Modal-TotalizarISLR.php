<!--
  ============================================================
  MODAL "TOTALIZAR APORTES" — ISLR masivo
  Archivo: View/Components/Modals/Modal-TotalizarISLR.php
  ============================================================
  INTEGRACIÓN:
  - Incluir en View/ISLR.php con include_once
  - Requiere jQuery + Bootstrap (ya cargados en la vista)
  - Requiere la variable JS: tasaBCV (inyectada desde PHP en ISLR.php)
  ============================================================
-->

<!-- ── BOTÓN DISPARADOR ── -->
<a type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalTotalizarISLR">
    Totalizar Aportes
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0V4zm0 3h16v5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V7zm3 2a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1H3zm4 0a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1H7z"/>
    </svg>
</a>

<!-- ══════════════════════════════════════
     MODAL PRINCIPAL — LISTA DE EMPLEADOS
════════════════════════════════════════ -->
<div class="modal fade" id="modalTotalizarISLR" tabindex="-1"
     aria-labelledby="modalTotalizarISLRLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="modalTotalizarISLRLabel">Totalizar Aportes ISLR</h5>
                    <small class="text-muted">Mes en curso · <span id="tislr-fecha"></span></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-0">

                <!-- Toolbar -->
                <div class="d-flex align-items-center justify-content-between px-3 py-2 bg-light border-bottom flex-wrap gap-2">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="tislrChkTodos">
                        <label class="form-check-label fw-semibold" for="tislrChkTodos">Seleccionar todos</label>
                    </div>
                    <div class="d-flex gap-3 text-muted small">
                        <span>Seleccionados: <strong id="tislrCntSel" class="text-dark">0</strong></span>
                        <span>Total Bs: <strong id="tislrTotalSel" class="text-warning">Bs 0.00</strong></span>
                    </div>
                </div>

                <!-- Spinner -->
                <div id="tislr-loading" class="text-center py-5">
                    <div class="spinner-border text-warning" role="status"></div>
                    <p class="text-muted mt-2 small">Cargando empleados...</p>
                </div>

                <!-- Tabla -->
                <div id="tislr-tabla-wrap" class="table-responsive" style="display:none; max-height:420px; overflow-y:auto;">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th style="width:40px"></th>
                                <th>Empleado</th>
                                <th>Cargo</th>
                                <th>Sueldo</th>
                                <th>% Retención</th>
                                <th>Monto (Bs)</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="tislr-tbody"></tbody>
                    </table>
                </div>

            </div>

            <div class="modal-footer d-flex align-items-center justify-content-between">
                <small class="text-muted fst-italic" style="max-width:60%;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2c5.523 0 10 4.477 10 10a10 10 0 0 1-19.995.324l-.005-.324.004-.28C2.152 6.327 6.57 2 12 2zm0 9h-1l-.117.007a1 1 0 0 0 0 1.986l.117.007v3l.007.117a1 1 0 0 0 .876.876l.117.007h1l.117-.007a1 1 0 0 0 .876-.876l.007-.117-.007-.117a1 1 0 0 0-.764-.857l-.112-.02-.117-.006v-3l-.007-.117a1 1 0 0 0-.876-.876L12 11zm.01-3l-.127.007a1 1 0 0 0 0 1.986l.117.007.127-.007a1 1 0 0 0 0-1.986l-.117-.007z"/>
                    </svg>
                    El porcentaje se asigna automáticamente: <strong>Gerentes 3%</strong>, resto <strong>2%</strong>.
                    Los empleados con aporte en el mes actual aparecen bloqueados.
                </small>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-warning" id="tislr-registrar" disabled
                            data-bs-toggle="modal" data-bs-target="#modalConfirmarISLR">
                        Registrar aportes <span id="tislr-cnt-badge"></span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>


<!-- ══════════════════════════════════════
     MODAL CONFIRMACIÓN
════════════════════════════════════════ -->
<div class="modal fade" id="modalConfirmarISLR" tabindex="-1" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content tislr-alert-modal">

            <button type="button" class="btn-close tislr-alert-close"
                    data-bs-dismiss="modal" aria-label="Cerrar"></button>

            <div class="modal-body text-center tislr-alert-body">
                <div class="tislr-alert-icon tislr-icon-warn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73
                              0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898
                              0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                </div>
                <h5 class="tislr-alert-title">¿Confirmar totalización?</h5>
                <p id="tislr-confirm-resumen" class="tislr-alert-text"></p>
                <p class="tislr-alert-warn">Esta acción registrará los aportes ISLR y no se puede deshacer.</p>
            </div>

            <div class="modal-footer border-0 justify-content-center gap-2 pb-4">
                <button type="button" class="btn btn-light px-4" id="tislr-volver">Volver</button>
                <button type="button" class="btn btn-warning px-4" id="tislr-aceptar">
                    <span id="tislr-aceptar-txt">Confirmar</span>
                    <span id="tislr-spinner-confirm" class="spinner-border spinner-border-sm ms-1"
                          style="display:none"></span>
                </button>
            </div>

        </div>
    </div>
</div>


<!-- ══════════════════════════════════════
     MODAL RESULTADO
════════════════════════════════════════ -->
<div class="modal fade" id="modalResultadoISLR" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content tislr-alert-modal">

            <button type="button" class="btn-close tislr-alert-close"
                    data-bs-dismiss="modal" aria-label="Cerrar"></button>

            <div class="modal-body text-center tislr-alert-body">
                <div id="tislr-res-icon" class="tislr-alert-icon"></div>
                <h5 id="tislr-res-title" class="tislr-alert-title"></h5>
                <p  id="tislr-res-body"  class="tislr-alert-text"></p>
                <ul id="tislr-res-errores" class="tislr-error-list" style="display:none;"></ul>
            </div>

            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-warning px-5"
                        id="tislr-finalizar" data-bs-dismiss="modal">Aceptar</button>
            </div>

        </div>
    </div>
</div>


<!-- ══════════════════════════════════════
     ESTILOS
════════════════════════════════════════ -->
<style>
    .tislr-alert-modal {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(0,0,0,.18);
    }
    .tislr-alert-close {
        position: absolute; top: 1rem; right: 1rem; z-index: 1;
    }
    .tislr-alert-body { padding: 2.5rem 2rem 1rem; }
    .tislr-alert-icon {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }
    .tislr-alert-icon svg { filter: drop-shadow(0 4px 8px rgba(0,0,0,.12)); }
    .tislr-icon-warn  { color: #f59e0b; }
    .tislr-icon-ok    { color: #16a34a; }
    .tislr-icon-error { color: #dc2626; }
    .tislr-alert-title {
        font-size: 1.15rem; font-weight: 700;
        color: #0f172a; margin-bottom: .5rem;
    }
    .tislr-alert-text  { color: #64748b; font-size:.9rem; margin-bottom:.5rem; line-height:1.5; }
    .tislr-alert-warn  { color: #dc2626; font-size:.8rem; margin-bottom:0; }
    .tislr-error-list  {
        list-style:none; padding:0; margin:.5rem auto 0;
        max-width:340px; max-height:130px; overflow-y:auto;
        text-align:left; font-size:.8rem; color:#dc2626;
    }
    .tislr-error-list li { padding:.3rem 0; border-bottom:1px solid #fee2e2; }
</style>


<!-- ══════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════ -->
<script>
$(document).ready(function () {
    'use strict';

    /* ── Config ── */
    var URL_LISTAR  = '../PHP/CTR/Get_Empleados_ISLR.php';
    var URL_GUARDAR = '../PHP/CTR/TotalizarISLR_CTR.php';

    var empleadosData = [];
    var seleccionados = {};

    /* ── Helpers ── */
    function fmtBs(v)  { return 'Bs ' + parseFloat(v).toFixed(2); }
    function fmtPct(v) { return parseFloat(v).toFixed(0) + '%'; }

    function calcMonto(sueldo, pct) {
        /* El monto ya viene en Bs desde el servidor (sueldo en $ * tasa * pct / 100) */
        return parseFloat(sueldo) * (window.tasaBCV || 1) * parseFloat(pct) / 100;
    }

    function porcentajePorCargo(cargo) {
        return (cargo || '').trim().toLowerCase() === 'gerente' ? 3 : 2;
    }

    /* ── Abrir modal principal ── */
    $('#modalTotalizarISLR').on('show.bs.modal', function () {
        var hoy = new Date();
        $('#tislr-fecha').text(hoy.toLocaleDateString('es-VE', {
            month: 'long', year: 'numeric'
        }));
        cargarEmpleados();
    });

    /* ── Cargar empleados ── */
    function cargarEmpleados() {
        $('#tislr-loading').show();
        $('#tislr-tabla-wrap').hide();
        seleccionados = {};
        actualizarResumen();

        $.ajax({
            url: URL_LISTAR,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#tislr-loading').hide();
                empleadosData = Array.isArray(data) ? data : [];
                renderTabla(empleadosData);
            },
            error: function (xhr) {
                $('#tislr-loading').hide();
                $('#tislr-tabla-wrap').show();
                $('#tislr-tbody').html(
                    '<tr><td colspan="7" class="text-center text-danger py-3">' +
                    'Error al cargar empleados (' + xhr.status + ').</td></tr>'
                );
            }
        });
    }

    /* ── Renderizar tabla ── */
    function renderTabla(data) {
        var $tbody = $('#tislr-tbody').empty();
        $('#tislr-tabla-wrap').show();

        if (!data.length) {
            $tbody.append(
                '<tr><td colspan="7" class="text-center text-muted py-4">' +
                'No hay empleados disponibles.</td></tr>'
            );
            return;
        }

        $.each(data, function (i, emp) {
            var yaPagado = !!emp.yaPagado;
            var pct      = porcentajePorCargo(emp.cargo);
            var monto    = calcMonto(emp.sueldo, pct);

            var badge;
            if (yaPagado) {
                badge = '<span class="badge bg-warning text-dark">Ya registrado este mes</span>';
            } else {
                badge = '<span class="badge bg-primary">Pendiente</span>';
            }

            var $tr = $('<tr>');
            if (yaPagado) $tr.addClass('text-muted');

            $tr.html(
                '<td><input type="checkbox" class="form-check-input tislr-chk"' +
                    ' data-cedula="' + emp.cedula + '"' +
                    (yaPagado ? ' disabled' : '') + '></td>' +

                '<td><div class="fw-semibold">' + emp.nombre + ' ' + emp.apellido + '</div>' +
                    '<div class="text-muted small">C.I. ' + emp.cedula + '</div></td>' +

                '<td>' + (emp.cargo || '--') + '</td>' +
                '<td>' + parseFloat(emp.sueldo).toFixed(2) + ' $</td>' +
                '<td><span class="badge ' + (pct === 3 ? 'bg-danger' : 'bg-secondary') + '">' +
                    fmtPct(pct) + '</span></td>' +
                '<td class="fw-bold text-warning">' + fmtBs(monto) + '</td>' +
                '<td>' + badge + '</td>'
            );

            /* Checkbox change */
            $tr.find('.tislr-chk').on('change', function () {
                if ($(this).is(':checked')) {
                    seleccionados[emp.cedula] = { cedula: emp.cedula, sueldo: emp.sueldo, aporte: pct, monto: monto };
                    $tr.addClass('table-warning');
                } else {
                    delete seleccionados[emp.cedula];
                    $tr.removeClass('table-warning');
                }
                actualizarResumen();
            });

            $tbody.append($tr);
        });

        $('#tislr-tabla-wrap').show();
    }

    /* ── Seleccionar todos ── */
    $('#tislrChkTodos').on('change', function () {
        var disponibles = empleadosData.filter(function (e) { return !e.yaPagado; });
        if ($(this).is(':checked')) {
            disponibles.forEach(function (e) {
                var pct   = porcentajePorCargo(e.cargo);
                var monto = calcMonto(e.sueldo, pct);
                seleccionados[e.cedula] = { cedula: e.cedula, sueldo: e.sueldo, aporte: pct, monto: monto };
            });
        } else {
            seleccionados = {};
        }
        $('.tislr-chk:not(:disabled)').each(function () {
            var cedula = $(this).data('cedula');
            $(this).prop('checked', !!seleccionados[cedula]);
            $(this).closest('tr').toggleClass('table-warning', !!seleccionados[cedula]);
        });
        actualizarResumen();
    });

    /* ── Actualizar resumen ── */
    function actualizarResumen() {
        var lista  = Object.values(seleccionados);
        var n      = lista.length;
        var total  = lista.reduce(function (s, e) { return s + parseFloat(e.monto); }, 0);

        $('#tislrCntSel').text(n);
        $('#tislrTotalSel').text(fmtBs(total));
        $('#tislr-registrar').prop('disabled', n === 0);
        $('#tislr-cnt-badge').text(n ? '(' + n + ')' : '');

        var disponibles = (empleadosData || []).filter(function (e) { return !e.yaPagado; });
        var chk = document.getElementById('tislrChkTodos');
        if (chk) {
            chk.indeterminate = n > 0 && n < disponibles.length;
            chk.checked       = disponibles.length > 0 && n === disponibles.length;
        }
    }

    /* ── Al abrir confirmación ── */
    $('#modalConfirmarISLR').on('show.bs.modal', function () {
        var lista  = Object.values(seleccionados);
        var total  = lista.reduce(function (s, e) { return s + parseFloat(e.monto); }, 0);
        $('#tislr-confirm-resumen').text(
            'Se registrarán ' + lista.length + ' aporte(s) ISLR por un total de ' + fmtBs(total) + '.'
        );
    });

    /* ── Volver desde confirmación ── */
    $('#tislr-volver').on('click', function () {
        $('#modalConfirmarISLR').modal('hide');
        $('#modalTotalizarISLR').modal('show');
    });

    /* ── Ejecutar totalización ── */
    $('#tislr-aceptar').on('click', function () {
        var lista = Object.values(seleccionados);
        $('#tislr-aceptar').prop('disabled', true);
        $('#tislr-aceptar-txt').hide();
        $('#tislr-spinner-confirm').show();

        $.ajax({
            url: URL_GUARDAR,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ empleados: lista }),
            dataType: 'json',
            success: function (res) {
                $('#modalConfirmarISLR').modal('hide');
                mostrarResultado(res);
                /* Refrescar la tabla de aportes en la vista */
                if (typeof refrescarTablaISLR === 'function') refrescarTablaISLR();
            },
            error: function () {
                $('#modalConfirmarISLR').modal('hide');
                mostrarResultadoError('No se pudo conectar con el servidor.');
            },
            complete: function () {
                $('#tislr-aceptar').prop('disabled', false);
                $('#tislr-aceptar-txt').show();
                $('#tislr-spinner-confirm').hide();
            }
        });
    });

    /* ── Al cerrar el modal resultado, recargar empleados ── */
    $('#tislr-finalizar').on('click', function () {
        cargarEmpleados();
    });

    /* ── SVG helpers ── */
    var SVG_OK    = '<svg xmlns="http://www.w3.org/2000/svg" class="tislr-icon-ok" width="56" height="56" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
    var SVG_ERROR = '<svg xmlns="http://www.w3.org/2000/svg" class="tislr-icon-error" width="56" height="56" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';

    function mostrarResultado(res) {
        if (res.exitosos > 0) {
            $('#tislr-res-icon').html(SVG_OK);
            $('#tislr-res-title').text('Aportes registrados');
        } else {
            $('#tislr-res-icon').html(SVG_ERROR);
            $('#tislr-res-title').text('No se procesaron aportes');
        }
        $('#tislr-res-body').text(
            res.exitosos + ' aporte(s) exitoso(s), ' + res.fallidos + ' omitido(s) o con error.'
        );
        var problemas = (res.resultados || []).filter(function (r) { return r.status !== 'ok'; });
        if (problemas.length) {
            var $ul = $('#tislr-res-errores').empty().show();
            $.each(problemas, function (i, p) {
                $ul.append('<li>· C.I. ' + p.cedula + ' — ' + p.msg + '</li>');
            });
        } else {
            $('#tislr-res-errores').hide();
        }
        $('#modalResultadoISLR').modal('show');
    }

    function mostrarResultadoError(msg) {
        $('#tislr-res-icon').html(SVG_ERROR);
        $('#tislr-res-title').text('Error de conexión');
        $('#tislr-res-body').text(msg);
        $('#tislr-res-errores').hide();
        $('#modalResultadoISLR').modal('show');
    }

}); // document.ready
</script>