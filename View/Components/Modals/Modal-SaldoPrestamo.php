<?php
/**
 * ============================================================
 * CÁPSULA + MODAL "SALDO DISPONIBLE" — Préstamos del trabajador
 * ============================================================
 */
if ($_SESSION['type'] == 'Trabajador'):
?>

<!-- CÁPSULA DISPARADORA -->
<button type="button" class="btn btn-saldo-cap" onclick="spAbrirModal()">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
         viewBox="0 0 24 24" fill="none" stroke="currentColor"
         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 12V22H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h14l4 4v4z"/>
        <line x1="2" y1="10" x2="20" y2="10"/>
        <path d="M16 12h.01"/>
    </svg>
    Saldo disponible: <strong id="cap-saldo-txt">cargando…</strong>
    <span id="cap-dot" class="saldo-dot"></span>
</button>


<!-- ══════════════════════════════════════
     MODAL PRINCIPAL
════════════════════════════════════════ -->
<div class="modal fade" id="modalSaldoPrestamo" tabindex="-1" role="dialog"
     aria-labelledby="modalSaldoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="modalSaldoLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             style="margin-right:6px; vertical-align:middle;">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                            <line x1="1" y1="10" x2="23" y2="10"/>
                        </svg>
                        Mis Préstamos
                    </h5>
                    <small class="text-muted">Saldo disponible y préstamos activos</small>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body p-0">

                <!-- Spinner -->
                <div id="sp-loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted mt-2 small">Cargando información…</p>
                </div>

                <!-- Contenido -->
                <div id="sp-contenido" style="display:none;">

                    <!-- Tarjetas resumen -->
                    <div class="sp-resumen-grid px-3 pt-3 pb-2">
                        <div class="sp-card sp-card--limite">
                            <span class="sp-card__label">Límite total</span>
                            <span class="sp-card__valor" id="sp-limite">—</span>
                        </div>
                        <div class="sp-card sp-card--pendiente">
                            <span class="sp-card__label">Monto pendiente</span>
                            <span class="sp-card__valor" id="sp-pendiente">—</span>
                        </div>
                        <div class="sp-card sp-card--disponible">
                            <span class="sp-card__label">Saldo disponible</span>
                            <span class="sp-card__valor" id="sp-disponible">—</span>
                        </div>
                    </div>

                    <!-- Barra de uso -->
                    <div class="px-3 pb-3">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Uso del límite</span>
                            <span id="sp-pct-label">0%</span>
                        </div>
                        <div class="progress" style="height:10px; border-radius:5px;">
                            <div id="sp-barra" class="progress-bar" role="progressbar"
                                 style="width:0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>

                    <!-- Toolbar tabla -->
                    <div class="border-top px-3 py-2 bg-light d-flex align-items-center">
                        <span class="fw-semibold small">Préstamos activos</span>
                        <span id="sp-badge-count" class="badge badge-secondary ml-2">0</span>
                    </div>

                    <!-- Tabla -->
                    <div class="table-responsive" style="max-height:320px; overflow-y:auto;">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="thead-light" style="position:sticky; top:0; z-index:1;">
                                <tr>
                                    <th>#</th>
                                    <th>Concepto</th>
                                    <th>Monto original</th>
                                    <th>Cuota/sem</th>
                                    <th>Pendiente</th>
                                    <th>Progreso</th>
                                    <th>Fecha límite</th>
                                </tr>
                            </thead>
                            <tbody id="sp-tbody"></tbody>
                        </table>
                    </div>

                </div><!-- /#sp-contenido -->

            </div><!-- /.modal-body -->

            <!-- Footer -->
            <div class="modal-footer d-flex align-items-center justify-content-between">
                <small class="text-muted font-italic">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                         viewBox="0 0 24 24" fill="currentColor"
                         style="vertical-align:middle; margin-right:4px;">
                        <path d="M12 2c5.523 0 10 4.477 10 10a10 10 0 0 1-19.995.324L2 12l.004-.28C2.152 6.327 6.57 2 12 2zm0 9h-1l-.117.007a1 1 0 0 0 0 1.986l.117.007v3l.007.117a1 1 0 0 0 .876.876L12 17h1l.117-.007a1 1 0 0 0 .876-.876L14 16l-.007-.117a1 1 0 0 0-.764-.857l-.112-.02L13 15v-3l-.007-.117a1 1 0 0 0-.876-.876L12 11zm.01-3l-.127.007a1 1 0 0 0 0 1.986l.117.007.127-.007a1 1 0 0 0 0-1.986L12.01 8z"/>
                    </svg>
                    Saldo = $2,000.00 &minus; suma de cuotas pendientes de préstamos activos.
                </small>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>


<!-- ══════════════════════════════════════
     ESTILOS
════════════════════════════════════════ -->
<style>
    /* ── Cápsula ── */
    .btn-saldo-cap {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: .875rem;
        font-weight: 500;
        background: #f0f4ff;
        color: #1e40af;
        border: 1.5px solid #93c5fd;
        transition: background .2s, transform .15s;
    }
    .btn-saldo-cap:hover { background: #dbeafe; transform: translateY(-1px); color: #1e40af; }

    .btn-saldo-cap.saldo-verde    { background:#f0fdf4; color:#166534; border-color:#86efac; }
    .btn-saldo-cap.saldo-amarillo { background:#fefce8; color:#854d0e; border-color:#fde047; }
    .btn-saldo-cap.saldo-rojo     { background:#fef2f2; color:#991b1b; border-color:#fca5a5; }

    .saldo-dot {
        width: 9px; height: 9px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
        background: #93c5fd;
    }

    /* ── Tarjetas ── */
    .sp-resumen-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    .sp-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 14px 10px;
        border-radius: 10px;
        gap: 4px;
    }
    .sp-card__label {
        font-size: .72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
        opacity: .7;
    }
    .sp-card__valor { font-size: 1.2rem; font-weight: 700; }

    .sp-card--limite     { background: #f3f4f6; color: #374151; }
    .sp-card--pendiente  { background: #fef2f2; color: #991b1b; }
    .sp-card--disponible { background: #f0fdf4; color: #166534; }

    /* ── Header con X alineada ── */
    #modalSaldoPrestamo .modal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }
    #modalSaldoPrestamo .modal-header .close {
        margin: -0.5rem -0.5rem -0.5rem auto;
        padding: 0.5rem;
        font-size: 1.5rem;
        line-height: 1;
        color: #000;
        opacity: .5;
        background: transparent;
        border: 0;
        cursor: pointer;
    }
    #modalSaldoPrestamo .modal-header .close:hover { opacity: .75; }

    @media (max-width: 576px) {
        .sp-resumen-grid { grid-template-columns: 1fr; }
    }
</style>


<!-- ══════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════ -->
<script>
document.addEventListener('DOMContentLoaded', function () {
(function ($) {
    'use strict';

    var URL_SALDO = '../PHP/CTR/Get_Saldo_Prestamos.php';

    /* ── Utilidades ── */
    function fmt(v) { return '$' + parseFloat(v || 0).toFixed(2); }

    function colorSaldo(saldo) {
        if (saldo >= 1000) return { clase: 'saldo-verde',    dot: '#16a34a' };
        if (saldo >= 500)  return { clase: 'saldo-amarillo', dot: '#ca8a04' };
        return                    { clase: 'saldo-rojo',     dot: '#dc2626' };
    }

    function colorBarra(pct) {
        if (pct >= 80) return 'bg-danger';
        if (pct >= 50) return 'bg-warning';
        return 'bg-success';
    }

    /* ── Actualizar cápsula ── */
    function actualizarCapsula(saldo) {
        var c = colorSaldo(saldo);
        $('#cap-saldo-txt').text(fmt(saldo));
        $('#cap-dot').css('background', c.dot);
        $('.btn-saldo-cap')
            .removeClass('saldo-verde saldo-amarillo saldo-rojo')
            .addClass(c.clase);
    }

    /* ── Renderizar modal ── */
    function renderContenido(res) {
        var saldo     = parseFloat(res.saldo     || 0);
        var pendiente = parseFloat(res.pendiente || 0);
        var limite    = parseFloat(res.limite    || 2000);
        var pct       = parseFloat(res.porcentaje|| 0);
        var prestamos = res.prestamos || [];

        $('#sp-limite').text(fmt(limite));
        $('#sp-pendiente').text(fmt(pendiente));
        $('#sp-disponible').text(fmt(saldo));

        $('#sp-barra')
            .css('width', pct + '%')
            .attr('aria-valuenow', pct)
            .removeClass('bg-success bg-warning bg-danger')
            .addClass(colorBarra(pct));
        $('#sp-pct-label').text(pct + '%');

        $('#sp-badge-count').text(prestamos.length);

        var $tbody = $('#sp-tbody').empty();

        if (!prestamos.length) {
            $tbody.append(
                '<tr><td colspan="7" class="text-center text-muted py-4">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" ' +
                'viewBox="0 0 24 24" stroke="#adb5bd" stroke-width="1.5" ' +
                'style="display:block;margin:0 auto .5rem">' +
                '<circle cx="12" cy="12" r="10"/>' +
                '<line x1="12" y1="8" x2="12" y2="12"/>' +
                '<line x1="12" y1="16" x2="12.01" y2="16"/>' +
                '</svg>No tienes préstamos activos en este momento.</td></tr>'
            );
        } else {
            $.each(prestamos, function (i, p) {
                var progreso = parseFloat(p.progreso || 0);
                $tbody.append(
                    '<tr>' +
                        '<td><span class="badge badge-primary">#' + p.id_prestamos + '</span></td>' +
                        '<td>' + $('<span>').text(p.concepto || 'Sin concepto').html() + '</td>' +
                        '<td>' + fmt(p.monto_original)  + '</td>' +
                        '<td>' + fmt(p.cuota_semanal)   + '</td>' +
                        '<td class="font-weight-bold text-danger">' + fmt(p.monto_pendiente) + '</td>' +
                        '<td style="min-width:130px;">' +
                            '<div class="progress mb-1" style="height:7px;border-radius:4px;">' +
                                '<div class="progress-bar bg-primary" style="width:' + progreso + '%"></div>' +
                            '</div>' +
                            '<small class="text-muted">' + progreso + '% pagado</small>' +
                        '</td>' +
                        '<td><small>' + (p.fecha_limite || '—') + '</small></td>' +
                    '</tr>'
                );
            });
        }

        actualizarCapsula(saldo);
        $('#sp-loading').hide();
        $('#sp-contenido').show();
    }

    /* ── Error ── */
    function mostrarError(msg) {
        $('#sp-loading').html(
            '<div class="py-4 text-center">' +
            '<p class="text-danger"><strong>Error:</strong> ' +
            $('<span>').text(msg).html() + '</p></div>'
        );
    }

    /* ── Función global llamada desde onclick de la cápsula ── */
    window.spAbrirModal = function () {
        /* reset estado */
        $('#sp-loading').show().find('p').text('Cargando información…');
        $('#sp-loading div.spinner-border').show();
        $('#sp-contenido').hide();

        /* abrir modal — mismo método que usa openModal() en el proyecto */
        try {
            $('#modalSaldoPrestamo').modal('show');
        } catch(e) {
            /* fallback nativo si Bootstrap JS aún no cargó */
            document.getElementById('modalSaldoPrestamo').style.display = 'block';
            document.getElementById('modalSaldoPrestamo').classList.add('show');
            document.body.classList.add('modal-open');
        }

        /* lanzar AJAX */
        $.ajax({
            url: URL_SALDO,
            type: 'GET',
            success: function (raw) {
                var res;
                try {
                    res = (typeof raw === 'object') ? raw : JSON.parse(raw);
                } catch (e) {
                    mostrarError('Respuesta inválida del servidor: ' + String(raw).substring(0, 200));
                    return;
                }
                if (!res || res.error) {
                    mostrarError(res ? res.error : 'Respuesta vacía.');
                    return;
                }
                renderContenido(res);
            },
            error: function (xhr) {
                mostrarError('HTTP ' + xhr.status + ' — No se pudo conectar.');
            }
        });
    };

    /* ── Cerrar modal (por si data-dismiss no responde) ── */
    $(document).on('click', '[data-dismiss="modal"]', function () {
        $('#modalSaldoPrestamo').modal('hide');
    });

    /* ── Carga inicial de la cápsula al cargar la página ── */
    $(function () {
        $.ajax({
            url: URL_SALDO,
            type: 'GET',
            success: function (raw) {
                try {
                    var res = (typeof raw === 'object') ? raw : JSON.parse(raw);
                    if (res && !res.error) actualizarCapsula(parseFloat(res.saldo || 0));
                } catch (e) { /* silencioso, la cápsula queda en estado por defecto */ }
            }
        });
    });

})(jQuery);
}); // DOMContentLoaded
</script>

<?php endif; ?>


<?php
/*
 * ============================================================
 * CTR → PHP/CTR/Get_Saldo_Prestamos.php
 * ============================================================
 *
 * <?php
 * ob_start();
 * session_start();
 * require_once '../../user_Original.php';
 *
 * ob_clean();
 * header('Content-Type: application/json; charset=utf-8');
 *
 * if (!isset($_SESSION['id']) || $_SESSION['type'] !== 'Trabajador') {
 *     echo json_encode(['error' => 'No autorizado']);
 *     exit;
 * }
 *
 * $cedula = $_SESSION['cedula'];   // ajusta al nombre real de tu variable de sesión
 *
 * try {
 *     $saldo_info = $Nomina->Get_Saldo_Prestamos($cedula);
 *     $prestamos  = $Nomina->Get_Prestamos_Activos_Trabajador($cedula);
 *
 *     foreach ($prestamos as &$p) {
 *         $p['fecha_limite'] = $p['fecha_limite']
 *             ? date('d/m/Y', strtotime($p['fecha_limite']))
 *             : '—';
 *     }
 *
 *     echo json_encode([
 *         'limite'     => $saldo_info['limite'],
 *         'pendiente'  => $saldo_info['pendiente'],
 *         'saldo'      => $saldo_info['saldo'],
 *         'porcentaje' => $saldo_info['porcentaje'],
 *         'prestamos'  => $prestamos,
 *     ]);
 * } catch (Exception $e) {
 *     echo json_encode(['error' => $e->getMessage()]);
 * }
 * ============================================================
 */
?>