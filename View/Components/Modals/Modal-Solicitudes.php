<!-- Modal Solicitudes de Préstamos -->
<div class="modal fade" id="solicitudesPrestamos" tabindex="-1" aria-labelledby="solicitudesPrestamosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content modal-solicitudes">

            <div class="modal-header modal-sol-header">
                <div class="modal-sol-title-group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 12h6" /><path d="M9 16h6" /></svg>
                    <h5 class="modal-title" id="solicitudesPrestamosModalLabel">Solicitudes de Préstamos</h5>
                </div>
                <div class="modal-sol-header-right">
                    <span class="badge-pendientes" id="badgeTotalSolicitudes">0 pendientes</span>
                    <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6l-12 12"/><path d="M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="modal-body modal-sol-body">

                <!-- Toolbar: búsqueda + paginación -->
                <div class="sol-toolbar">
                    <div class="sol-search-wrap">
                        <svg class="sol-search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        <input type="text" id="buscarSolicitudes" name="buscarSolicitudes" class="sol-search-input" placeholder="Buscar por cédula…" autocomplete="off">
                    </div>
                    <nav class="sol-pagination" aria-label="Paginación">
                        <button class="sol-page-btn" id="anteriorSolicitudes" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l-6 6l6 6"/></svg>
                            Anterior
                        </button>
                        <span class="sol-page-info" id="infoPageSolicitudes">Página 1</span>
                        <button class="sol-page-btn" id="siguienteSolicitudes">
                            Siguiente
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6l-6 6"/></svg>
                        </button>
                    </nav>
                </div>

                <!-- Tabla -->
                <div class="sol-table-wrap">
                    <table class="sol-table">
                        <thead>
                            <tr>
                                <th>Cédula</th>
                                <th>Nombre</th>
                                <th>Monto</th>
                                <th>Descuento</th>
                                <th>N° Cuotas</th>
                                <th colspan="2">Descripción</th>
                                <th>Fecha solicitud</th>
                                <th>Estado</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaSolicitudes">
                            <!-- Datos dinámicos -->
                        </tbody>
                    </table>

                    <!-- Estado vacío -->
                    <div class="sol-empty" id="solEmpty" style="display:none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /></svg>
                        <p>No hay solicitudes pendientes</p>
                    </div>
                </div>

            </div>

            <div class="modal-footer modal-sol-footer">
                <span class="sol-footer-note">Las solicitudes aprobadas o denegadas serán procesadas de inmediato.</span>
            </div>

        </div>
    </div>
</div>

<!-- ── Modal de confirmación personalizado ── -->
<div id="solConfirmOverlay" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(15,23,42,0.55); backdrop-filter:blur(2px); align-items:center; justify-content:center;">
    <div id="solConfirmBox" style="background:#fff; border-radius:16px; box-shadow:0 24px 64px rgba(0,0,0,.22); width:100%; max-width:400px; margin:0 16px; overflow:hidden; transform:scale(.95); opacity:0; transition:transform .2s ease, opacity .2s ease;">
        <div style="padding:24px 24px 0;">
            <div id="solConfirmIcon" style="width:44px; height:44px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-bottom:14px;">
            </div>
            <p id="solConfirmTitle" style="font-size:1rem; font-weight:600; color:#1e293b; margin:0 0 6px;"></p>
            <p id="solConfirmMsg" style="font-size:.875rem; color:#64748b; margin:0 0 22px; line-height:1.5;"></p>
        </div>
        <div style="padding:0 24px 20px; display:flex; gap:10px; justify-content:flex-end;">
            <button id="solConfirmCancel" style="padding:9px 20px; border-radius:8px; border:1.5px solid #e2e8f0; background:#fff; color:#475569; font-size:.875rem; font-weight:600; cursor:pointer; transition:all .15s;">
                Cancelar
            </button>
            <button id="solConfirmOk" style="padding:9px 20px; border-radius:8px; border:none; font-size:.875rem; font-weight:600; cursor:pointer; transition:all .15s; color:#fff;">
                Confirmar
            </button>
        </div>
    </div>
</div>


<style>
/* ── Variables ───────────────────────────────────────────── */
:root {
    --sol-bg: #ffffff;
    --sol-surface: #f8fafc;
    --sol-border: #e2e8f0;
    --sol-text: #1e293b;
    --sol-text-muted: #64748b;
    --sol-accent: #3b82f6;
    --sol-accent-hover: #2563eb;
    --sol-green: #16a34a;
    --sol-green-bg: #f0fdf4;
    --sol-green-border: #bbf7d0;
    --sol-red: #dc2626;
    --sol-red-bg: #fef2f2;
    --sol-red-border: #fecaca;
    --sol-yellow: #d97706;
    --sol-yellow-bg: #fffbeb;
    --sol-header-bg: #1e293b;
    --sol-header-text: #f1f5f9;
    --sol-radius: 12px;
    --sol-radius-sm: 6px;
    --sol-shadow: 0 20px 60px rgba(0,0,0,.15);
}

/* ── Modal shell ─────────────────────────────────────────── */
.modal-solicitudes {
    border: none;
    border-radius: var(--sol-radius);
    overflow: hidden;
    box-shadow: var(--sol-shadow);
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
}

/* ── Header ──────────────────────────────────────────────── */
.modal-sol-header {
    background: var(--sol-header-bg);
    color: var(--sol-header-text);
    padding: 18px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: none;
}
.modal-sol-title-group {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--sol-header-text);
}
.modal-sol-title-group svg { opacity: .85; }
.modal-sol-title-group .modal-title {
    font-size: 1.05rem;
    font-weight: 600;
    letter-spacing: .01em;
    margin: 0;
}
.modal-sol-header-right {
    display: flex;
    align-items: center;
    gap: 12px;
}
.badge-pendientes {
    background: rgba(234,179,8,.18);
    color: #fbbf24;
    border: 1px solid rgba(234,179,8,.3);
    font-size: .75rem;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
    letter-spacing: .02em;
}
.btn-close-custom {
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.15);
    color: var(--sol-header-text);
    border-radius: var(--sol-radius-sm);
    width: 32px; height: 32px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: background .2s;
    padding: 0;
}
.btn-close-custom:hover { background: rgba(255,255,255,.2); }

/* ── Body ────────────────────────────────────────────────── */
.modal-sol-body {
    background: var(--sol-surface);
    padding: 20px 24px;
}

/* ── Toolbar ─────────────────────────────────────────────── */
.sol-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}
.sol-search-wrap {
    position: relative;
    flex: 1;
    min-width: 200px;
    max-width: 320px;
}
.sol-search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--sol-text-muted);
    pointer-events: none;
}
.sol-search-input {
    width: 100%;
    padding: 9px 14px 9px 38px;
    border: 1.5px solid var(--sol-border);
    border-radius: var(--sol-radius-sm);
    background: var(--sol-bg);
    color: var(--sol-text);
    font-size: .875rem;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.sol-search-input:focus {
    border-color: var(--sol-accent);
    box-shadow: 0 0 0 3px rgba(59,130,246,.12);
}
.sol-search-input::placeholder { color: #94a3b8; }

/* ── Paginación ──────────────────────────────────────────── */
.sol-pagination {
    display: flex;
    align-items: center;
    gap: 8px;
}
.sol-page-btn {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 7px 14px;
    border: 1.5px solid var(--sol-border);
    border-radius: var(--sol-radius-sm);
    background: var(--sol-bg);
    color: var(--sol-text);
    font-size: .8rem;
    font-weight: 500;
    cursor: pointer;
    transition: all .2s;
}
.sol-page-btn:hover:not(:disabled) {
    border-color: var(--sol-accent);
    color: var(--sol-accent);
    background: rgba(59,130,246,.05);
}
.sol-page-btn:disabled {
    opacity: .4;
    cursor: not-allowed;
}
.sol-page-info {
    font-size: .8rem;
    color: var(--sol-text-muted);
    font-weight: 500;
    padding: 0 4px;
    white-space: nowrap;
}

/* ── Table wrap ──────────────────────────────────────────── */
.sol-table-wrap {
    background: var(--sol-bg);
    border: 1.5px solid var(--sol-border);
    border-radius: var(--sol-radius);
    overflow: hidden;
}
.sol-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .85rem;
}
.sol-table thead tr {
    background: var(--sol-header-bg);
}
.sol-table thead th {
    color: #94a3b8;
    font-weight: 600;
    font-size: .75rem;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: 13px 14px;
    text-align: center;
    border: none;
    white-space: nowrap;
}
.sol-table tbody tr {
    border-bottom: 1px solid var(--sol-border);
    transition: background .15s;
}
.sol-table tbody tr:last-child { border-bottom: none; }
.sol-table tbody tr:hover { background: #f1f5f9; }
.sol-table td {
    padding: 12px 14px;
    color: var(--sol-text);
    text-align: center;
    vertical-align: middle;
}

/* ── Cell chips ──────────────────────────────────────────── */
.nombre-empleado { font-weight: 600; color: #334155; }
.monto-badge {
    display: inline-block;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    border-radius: 20px;
    padding: 3px 10px;
    font-weight: 600;
    font-size: .8rem;
}
.descuento-badge {
    display: inline-block;
    background: #faf5ff;
    color: #7c3aed;
    border: 1px solid #ddd6fe;
    border-radius: 20px;
    padding: 3px 10px;
    font-weight: 600;
    font-size: .8rem;
}
.cuotas-badge {
    display: inline-block;
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
    border-radius: 20px;
    padding: 3px 10px;
    font-weight: 700;
    font-size: .8rem;
}
.concepto-text {
    color: var(--sol-text-muted);
    font-size: .82rem;
}
.fecha-text {
    color: var(--sol-text-muted);
    font-size: .82rem;
    white-space: nowrap;
}
.estado-pendiente {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: var(--sol-yellow-bg);
    color: var(--sol-yellow);
    border: 1px solid #fde68a;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: .75rem;
    font-weight: 700;
    letter-spacing: .03em;
    white-space: nowrap;
}
.estado-pendiente::before {
    content: '';
    width: 6px; height: 6px;
    background: currentColor;
    border-radius: 50%;
    display: inline-block;
}

/* ── Action buttons ──────────────────────────────────────── */
.acciones-group {
    display: flex;
    gap: 7px;
    justify-content: center;
    align-items: center;
    flex-wrap: nowrap;
}
.btn-accion {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: var(--sol-radius-sm);
    font-size: .78rem;
    font-weight: 600;
    cursor: pointer;
    border: 1.5px solid transparent;
    transition: all .18s;
    white-space: nowrap;
}
.btn-aprobar {
    background: var(--sol-green-bg);
    color: var(--sol-green);
    border-color: var(--sol-green-border);
}
.btn-aprobar:hover {
    background: var(--sol-green);
    color: #fff;
    border-color: var(--sol-green);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(22,163,74,.25);
}
.btn-denegar {
    background: var(--sol-red-bg);
    color: var(--sol-red);
    border-color: var(--sol-red-border);
}
.btn-denegar:hover {
    background: var(--sol-red);
    color: #fff;
    border-color: var(--sol-red);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220,38,38,.25);
}

/* ── Empty state ─────────────────────────────────────────── */
.sol-empty {
    text-align: center;
    padding: 52px 24px;
    color: var(--sol-text-muted);
}
.sol-empty svg { opacity: .35; margin-bottom: 14px; }
.sol-empty p { font-size: .9rem; font-weight: 500; margin: 0; }

/* ── Footer ──────────────────────────────────────────────── */
.modal-sol-footer {
    background: var(--sol-surface);
    border-top: 1px solid var(--sol-border);
    padding: 12px 24px;
}
.sol-footer-note {
    font-size: .78rem;
    color: var(--sol-text-muted);
}

/* ── Row remove animation ────────────────────────────────── */
@keyframes rowFadeOut {
    from { opacity: 1; transform: translateX(0); }
    to   { opacity: 0; transform: translateX(20px); }
}
.row-removing {
    animation: rowFadeOut .3s ease forwards;
}
</style>


<!-- Scripts necesarios -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
// Variables globales (fuera del ready para que aprobarSolicitud/denegarSolicitud
// sean accesibles desde el onclick inline del HTML generado por PHP)
var paginaActualSolicitudes = 1;
var totalPaginasSolicitudes = 0;
var buscarSolicitudes = "";

function cargarSolicitudes(pagina) {
    $.ajax({
        url: 'Components/Tables/Tablas-Solicitud.php',
        type: 'GET',
        data: { pagina: pagina, busqueda: buscarSolicitudes },
        success: function(response) {
            try {
                var datos = JSON.parse(response);
                $('#tablaSolicitudes').html(datos.datos);
                totalPaginasSolicitudes = datos.totalPaginas;
                paginaActualSolicitudes = pagina;

                // Badge de pendientes
                var total = parseInt(datos.totalElementos) || 0;
                $('#badgeTotalSolicitudes').text(total + (total === 1 ? ' pendiente' : ' pendientes'));

                // Mostrar estado vacío o tabla
                if (!datos.datos || datos.datos.trim() === '') {
                    $('#tablaSolicitudes').closest('table').hide();
                    $('#solEmpty').show();
                    cerrarModalSiVacio();
                } else {
                    $('#tablaSolicitudes').closest('table').show();
                    $('#solEmpty').hide();
                }

                actualizarBotonesSolicitudes();
            } catch (e) {
                console.log("Error al parsear la respuesta:", e);
                console.log("Respuesta cruda:", response);
            }
        },
        error: function() {
            alert('Error al cargar los datos. Intente nuevamente.');
        }
    });
}

var _recargarAlCerrar = false;

function cerrarModalSiVacio() {
    _recargarAlCerrar = true;
    setTimeout(function() {
        try {
            var el = document.getElementById('solicitudesPrestamos');
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                var instancia = bootstrap.Modal.getInstance(el);
                if (instancia) { instancia.hide(); return; }
            }
        } catch(e) {}
        $('#solicitudesPrestamos').modal('hide');
    }, 900);
}

function actualizarBotonesSolicitudes() {
    $('#anteriorSolicitudes').prop('disabled', paginaActualSolicitudes <= 1);
    $('#siguienteSolicitudes').prop('disabled', paginaActualSolicitudes >= totalPaginasSolicitudes);

    var info = totalPaginasSolicitudes > 0
        ? 'Página ' + paginaActualSolicitudes + ' / ' + totalPaginasSolicitudes
        : 'Sin resultados';
    $('#infoPageSolicitudes').text(info);
}

function removerFila(id, callback) {
    var $fila = $('#registro-' + id);
    $fila.addClass('row-removing');
    setTimeout(function() {
        $fila.remove();
        if ($('#tablaSolicitudes tr').length === 0) {
            cargarSolicitudes(paginaActualSolicitudes);
        }
        if (typeof callback === 'function') callback();
    }, 320);
}

function solConfirm(opciones) {
    var overlay = document.getElementById('solConfirmOverlay');
    var box     = document.getElementById('solConfirmBox');
    var icon    = document.getElementById('solConfirmIcon');
    var title   = document.getElementById('solConfirmTitle');
    var msg     = document.getElementById('solConfirmMsg');
    var btnOk   = document.getElementById('solConfirmOk');
    var btnCan  = document.getElementById('solConfirmCancel');

    title.textContent = opciones.titulo;
    msg.textContent   = opciones.mensaje;

    if (opciones.tipo === 'aprobar') {
        icon.style.background  = '#f0fdf4';
        icon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5l10 -10"/></svg>';
        btnOk.style.background = '#16a34a';
        btnOk.textContent      = 'Sí, aprobar';
    } else {
        icon.style.background  = '#fef2f2';
        icon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6l-12 12"/><path d="M6 6l12 12"/></svg>';
        btnOk.style.background = '#dc2626';
        btnOk.textContent      = 'Sí, denegar';
    }

    overlay.style.display = 'flex';
    requestAnimationFrame(function() {
        box.style.transform = 'scale(1)';
        box.style.opacity   = '1';
    });

    function cerrar() {
        box.style.transform = 'scale(.95)';
        box.style.opacity   = '0';
        setTimeout(function() { overlay.style.display = 'none'; }, 200);
        btnOk.onclick  = null;
        btnCan.onclick = null;
        overlay.onclick = null;
    }

    btnOk.onclick = function() { cerrar(); opciones.onConfirm(); };
    btnCan.onclick = cerrar;
    overlay.onclick = function(e) { if (e.target === overlay) cerrar(); };
}

function aprobarSolicitud(id) {
    solConfirm({
        tipo: 'aprobar',
        titulo: 'Aprobar solicitud',
        mensaje: '¿Estás seguro de que deseas aprobar esta solicitud de préstamo?',
        onConfirm: function() {
            $.ajax({
                url: '../PHP/CTR/Solicitudes_CTR.php',
                type: 'POST',
                data: { id: id, accion: 'Aprovado' },
                success: function(response) {
                    if (!response.message) {
                        removerFila(id, function() {
                            $('#alerts').html(response.html);
                        });
                    } else {
                        alert('Error: Algo salió mal');
                    }
                },
                error: function() {
                    alert('Error al aprobar esta solicitud, intente de nuevo');
                }
            });
        }
    });
    return false;
}

function denegarSolicitud(id) {
    solConfirm({
        tipo: 'denegar',
        titulo: 'Denegar solicitud',
        mensaje: '¿Estás seguro de que deseas denegar esta solicitud? Esta acción no se puede deshacer.',
        onConfirm: function() {
            $.ajax({
                url: '../PHP/CTR/Solicitudes_CTR.php',
                type: 'POST',
                data: { id: id, accion: 'Denegado' },
                success: function(response) {
                    if (!response.message) {
                        removerFila(id, function() {
                            $('#alerts').html(response.html);
                        });
                    } else {
                        alert('Error: Algo salió mal');
                    }
                },
                error: function() {
                    alert('Error, intente de nuevo');
                }
            });
        }
    });
    return false;
}

// Registrar eventos solo cuando el DOM esté listo
$(document).ready(function() {

    $('#anteriorSolicitudes').on('click', function() {
        if (paginaActualSolicitudes > 1) {
            cargarSolicitudes(paginaActualSolicitudes - 1);
        }
    });

    $('#siguienteSolicitudes').on('click', function() {
        if (paginaActualSolicitudes < totalPaginasSolicitudes) {
            cargarSolicitudes(paginaActualSolicitudes + 1);
        }
    });

    $('#buscarSolicitudes').on('input', function() {
        buscarSolicitudes = $(this).val();
        cargarSolicitudes(1);
    });

    // Cargar datos cada vez que el modal se abre
    $('#solicitudesPrestamos').on('show.bs.modal', function() {
        _recargarAlCerrar = false;
        buscarSolicitudes = '';
        $('#buscarSolicitudes').val('');
        paginaActualSolicitudes = 1;
        cargarSolicitudes(1);
    });

    // Recargar la página en background cuando se cierra por quedarse sin registros
    $('#solicitudesPrestamos').on('hidden.bs.modal', function() {
        if (_recargarAlCerrar) {
            _recargarAlCerrar = false;
            // fetch silencioso: recarga el PHP del dashboard sin recargar el navegador
            // Si el dashboard no expone un endpoint parcial, hacemos location.reload()
            // con replaceState para que no aparezca en el historial y no se note
            history.replaceState(null, '', location.href);
            location.reload();
        }
    });

});
</script>