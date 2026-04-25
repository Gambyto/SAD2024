<?php
/*
  ISLR_Graph.php — VERSIÓN ACTUALIZADA
  
  CAMBIOS:
  - Botón de filtro por año (top-left del header del modal)
  - Botón PDF movido al header (top-right, junto al X)
  - Flecha lateral derecha que hace scroll a tabla de detalle por empleado
  - Tabla de detalle por empleado con aportes individuales (ISLR_Detail($anio))
  - Gráfico y tabla resumen se actualizan al cambiar el año vía AJAX
*/

$date   = $Nomina->ISLR_Grap();                      // 12 filas, siempre, ordenadas mes 1→12
$values = array_column($date, 'monto');              // índice 0=enero … 11=diciembre
$anios  = $Nomina->ISLR_GetAnios();                  // Nuevo método: devuelve lista de años con registros
// $meses y $anio_end ya están definidos en ISLR_indicator.php

$nombres_meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                  'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
?>

<div class="modal fade" id="ISLRModal" tabindex="-1" aria-labelledby="ISLRModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="border-radius:1rem; overflow:hidden;">

            <!-- ═══ HEADER ═══ -->
            <div class="modal-header" style="background:linear-gradient(135deg,#1a1a2e,#16213e); color:#fff; border-bottom:none; padding:1rem 1.4rem;">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <h5 class="modal-title mb-0" id="ISLRModalLabel" style="font-weight:700; letter-spacing:.5px;">
                        Fluctuación del pago de ISLR
                    </h5>

                    <!-- BADGE AÑO ACTIVO -->
                    <span class="badge bg-secondary" id="islr-badge-anio" style="font-size:.85rem;"><?php echo $anio_end ?? date('Y'); ?></span>

                    <!-- FILTRO AÑO — select nativo (sin Popper, sin overflow issues) -->
                    <select id="islrAnioSelect"
                            class="form-select form-select-sm"
                            style="width:auto; background:#fff; color:#1a1a2e; font-weight:600; border-radius:.5rem;"
                            onchange="_islrCargarAnio(this.value)">
                        <?php if (!empty($anios)): ?>
                            <?php foreach ($anios as $a): ?>
                                <option value="<?php echo $a['anio']; ?>"
                                    <?php echo ($a['anio'] == ($anio_end ?? date('Y'))) ? 'selected' : ''; ?>>
                                    <?php echo $a['anio']; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Derecha: BTN PDF + CLOSE -->
                <div class="d-flex align-items-center gap-2">
                    <a id="islrPdfBtn"
                       href="PlantillaPDF/ISLR-reporte.php?anio=<?php echo $anio_end ?? date('Y'); ?>"
                       target="_blank"
                       class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1"
                       style="border-radius:.5rem; font-size:.82rem; font-weight:600; background:rgba(220,53,69,.1); border-color:rgba(220,53,69,.5); color:#ff6b6b;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10 9 9 9 8 9"/>
                        </svg>
                        PDF
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <!-- ═══ BODY ═══ -->
            <div class="modal-body" id="ISLR-modal-body" style="position:relative; padding:1.2rem;">

                <!-- FLECHA DERECHA → scroll al detalle -->
                <button id="islr-arrow-btn"
                        onclick="islrScrollToDetail()"
                        title="Ver detalle por empleado"
                        style="
                            position: absolute;
                            right: -1px;
                            top: 50%;
                            transform: translateY(-50%);
                            z-index: 10;
                            background: linear-gradient(135deg,#ff6b6b,#ee5a24);
                            border: none;
                            border-radius: .6rem 0 0 .6rem;
                            width: 2rem;
                            height: 5rem;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            cursor: pointer;
                            box-shadow: -3px 0 12px rgba(238,90,36,.35);
                            transition: width .2s ease, background .2s ease;
                        "
                        onmouseenter="this.style.width='2.5rem'"
                        onmouseleave="this.style.width='2rem'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                         fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </button>

                <!-- Tarjetas resumen -->
                <div class="row mb-3" id="islr-summary-cards">
                    <div class="col-md-4">
                        <div class="card border-0 text-center p-3" style="background:#f8f9fa; border-radius:.8rem;">
                            <small class="text-muted" style="font-size:.75rem; text-transform:uppercase; letter-spacing:.5px;">Total año</small>
                            <strong class="fs-5" id="islr-total-anio">
                                <?php echo number_format(array_sum($values), 2); ?> Bs.
                            </strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 text-center p-3" style="background:#fff5f5; border-radius:.8rem;">
                            <small class="text-muted" style="font-size:.75rem; text-transform:uppercase; letter-spacing:.5px;">Mayor aporte</small>
                            <strong class="fs-5" id="islr-mayor-aporte">
                                <?php
                                $max_val = !empty($values) ? max($values) : 0;
                                $max_idx = $max_val > 0 ? array_search($max_val, $values) : -1;
                                echo $max_idx >= 0
                                    ? number_format($max_val, 2) . ' Bs. (' . $nombres_meses[$max_idx] . ')'
                                    : '—';
                                ?>
                            </strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 text-center p-3" style="background:#f0fff4; border-radius:.8rem;">
                            <small class="text-muted" style="font-size:.75rem; text-transform:uppercase; letter-spacing:.5px;">Meses con aporte</small>
                            <strong class="fs-5" id="islr-meses-aporte">
                                <?php echo count(array_filter($values, fn($v) => $v > 0)); ?> / 12
                            </strong>
                        </div>
                    </div>
                </div>

                <!-- Gráfico -->
                <canvas id="ISLR_GP" width="400" height="180"></canvas>

                <!-- Tabla mensual resumen -->
                <div class="mt-3" id="islr-tabla-resumen">
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="table-danger">
                            <tr>
                                <th>Mes</th>
                                <?php foreach ($nombres_meses as $nm): ?>
                                    <th class="text-center" style="font-size:.78rem;"><?php echo $nm; ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody id="islr-tabla-body">
                            <tr>
                                <td class="fw-bold">Monto Bs.</td>
                                <?php foreach ($values as $v): ?>
                                    <td class="text-center <?php echo $v > 0 ? '' : 'text-muted'; ?>">
                                        <?php echo $v > 0 ? number_format($v, 2) : '—'; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- ═══ SECCIÓN DETALLE POR EMPLEADO (oculta inicialmente) ═══ -->
                <div id="islr-detalle-section" style="display:none; margin-top:1.5rem;">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.8rem; padding-bottom:.5rem; border-bottom:2px solid rgba(128,128,128,.15);">
                        <h6 class="mb-0 fw-bold" style="color:#16213e; font-size:1rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 style="margin-right:.4rem; color:#ee5a24;">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                            Detalle de aportes por empleado — <span id="islr-detalle-anio-label"><?php echo $anio_end ?? date('Y'); ?></span>
                        </h6>
                        <button onclick="islrCollapseDetail()" class="btn btn-sm btn-outline-secondary" style="font-size:.78rem; border-radius:.5rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="18 15 12 9 6 15"/>
                            </svg>
                            Colapsar
                        </button>
                    </div>

                    <div id="islr-detalle-loading" style="text-align:center; padding:2rem; display:none;">
                        <div class="spinner-border spinner-border-sm text-danger" role="status"></div>
                        <span class="text-muted ms-2" style="font-size:.9rem;">Cargando detalle...</span>
                    </div>

                    <div id="islr-detalle-tabla-wrap" class="table-responsive">
                        <table class="table table-sm table-hover" style="font-size:.84rem;">
                            <thead style="background:linear-gradient(135deg,#1a1a2e,#16213e); color:#fff;">
                                <tr>
                                    <th style="border-radius:.5rem 0 0 0;">Empleado</th>
                                    <?php foreach ($nombres_meses as $nm): ?>
                                        <th class="text-center"><?php echo substr($nm,0,3); ?></th>
                                    <?php endforeach; ?>
                                    <th class="text-center" style="border-radius:0 .5rem 0 0;">Total</th>
                                </tr>
                            </thead>
                            <tbody id="islr-detalle-body">
                                <!-- Se carga vía JS al expandir -->
                                <tr>
                                    <td colspan="14" class="text-center text-muted py-3" style="font-size:.85rem;">
                                        Haz clic en la flecha → para cargar el detalle
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- ═══ FOOTER ═══ -->
            <div class="modal-footer" style="background:#f8f9fa; border-top:1px solid rgba(128,128,128,.1); padding:.7rem 1.4rem;">
                <small class="text-muted">Los meses sin aportes se muestran como 0 en el gráfico.</small>
            </div>

        </div>
    </div>
</div>

<script>
(function () {

    /* ── Variables de estado ─────────────────────────────── */
    let _anioActivo  = <?php echo json_encode($anio_end ?? date('Y')); ?>;
    let _detalleCargado = false;
    let _detalleVisible = false;

    /* ── Inicializar al abrir el modal ───────────────────── */
    document.getElementById('ISLRModal').addEventListener('shown.bs.modal', function () {
        _islrRenderChart(
            <?php echo json_encode($nombres_meses); ?>,
            <?php echo json_encode(array_map('floatval', $values)); ?>
        );
    });

    /* ── Render del gráfico ──────────────────────────────── */
   window._islrRenderChart = function(nombres, valores) {
        if (window._islrChartInstance) window._islrChartInstance.destroy();

        const ctb = document.getElementById('ISLR_GP').getContext('2d');
        window._islrChartInstance = new Chart(ctb, {
            type: 'bar',
            data: {
                labels: nombres,
                datasets: [{
                    label: 'Aporte ISLR (Bs.)',
                    data: valores,
                    backgroundColor: valores.map(v =>
                        v === 0 ? 'rgba(200,200,200,0.4)' : 'rgba(255, 99, 132, 0.7)'
                    ),
                    borderColor: valores.map(v =>
                        v === 0 ? 'rgba(200,200,200,0.8)' : 'rgba(255, 99, 132, 1)'
                    ),
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.parsed.y === 0
                                ? 'Sin aporte registrado'
                                : 'Bs. ' + ctx.parsed.y.toLocaleString('es-VE', {minimumFractionDigits:2})
                        }
                    },
                    legend: { display: true }
                },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    /* ── Cambio de año (manejado por onchange del select) ── */
    // El select#islrAnioSelect llama directamente a _islrCargarAnio(value)

    window._islrCargarAnio = function(anio) {
    const nombres_meses = <?php echo json_encode($nombres_meses); ?>;

    _anioActivo = anio;
    _detalleCargado = false;

    // Actualizar badge y label de detalle
    const badge = document.getElementById('islr-badge-anio');
    if (badge) badge.textContent = anio;
    const label = document.getElementById('islr-detalle-anio-label');
    if (label) label.textContent = anio;

    // Ocultar detalle al cambiar año
    if (_detalleVisible) islrCollapseDetail();

    // Sincronizar href del botón PDF con el año seleccionado
    const pdfBtn = document.getElementById('islrPdfBtn');
    if (pdfBtn) pdfBtn.href = `PlantillaPDF/ISLR-reporte.php?anio=${anio}`;

    fetch(`../PHP/CTR/ISLR_Controller.php?action=islr_data&anio=${anio}`, {
        headers: {'X-Requested-With': 'XMLHttpRequest'}
    })
    .then(r => {
        if (!r.ok) throw new Error('Error en la respuesta del servidor');
        return r.json();
    })
    .then(data => {
        const valores = data.values || new Array(12).fill(0);

        /* Tarjetas resumen */
        const total = valores.reduce((a,b) => a+b, 0);
        const elTotal = document.getElementById('islr-total-anio');
        if(elTotal) elTotal.textContent = total.toLocaleString('es-VE', {minimumFractionDigits:2}) + ' Bs.';

        const maxVal = Math.max(...valores);
        const maxIdx = valores.indexOf(maxVal);
        const elMayor = document.getElementById('islr-mayor-aporte');
        if(elMayor) {
            elMayor.textContent = maxVal > 0
                ? maxVal.toLocaleString('es-VE', {minimumFractionDigits:2}) + ' Bs. (' + nombres_meses[maxIdx] + ')'
                : '—';
        }

        const conAporte = valores.filter(v => v > 0).length;
        const elMeses = document.getElementById('islr-meses-aporte');
        if(elMeses) elMeses.textContent = conAporte + ' / 12';

        /* Tabla mensual */
        const tbody = document.getElementById('islr-tabla-body');
        if(tbody) {
            let filaHtml = '<tr><td class="fw-bold">Monto Bs.</td>';
            valores.forEach(v => {
                filaHtml += `<td class="text-center ${v > 0 ? '' : 'text-muted'}">
                    ${v > 0 ? v.toLocaleString('es-VE', {minimumFractionDigits:2}) : '—'}
                </td>`;
            });
            filaHtml += '</tr>';
            tbody.innerHTML = filaHtml;
        }

        /* Gráfico */
        _islrRenderChart(nombres_meses, valores);
        
        // Cargar también el detalle de empleados para ese año
        _islrCargarDetalle(anio); 
    })
    .catch(err => {
        console.error("Error AJAX ISLR:", err);
        // Ya no recargamos la página, así evitamos el bucle infinito
        alert("No se pudieron cargar los datos del año " + anio);
    });
}

    /* ── Flecha → scroll al detalle ─────────────────────── */
    window.islrScrollToDetail = function() {
        const section = document.getElementById('islr-detalle-section');
        section.style.display = 'block';
        _detalleVisible = true;

        // Animar la flecha
        const btn = document.getElementById('islr-arrow-btn');
        btn.style.background = 'linear-gradient(135deg,#c0392b,#e74c3c)';
        setTimeout(() => { btn.style.background = 'linear-gradient(135deg,#ff6b6b,#ee5a24)'; }, 400);

        // Scroll suave al detalle
        setTimeout(() => {
            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 80);

        // Cargar datos si no están cargados aún
        if (!_detalleCargado) {
            _islrCargarDetalle(_anioActivo);
        }
    };

    window.islrCollapseDetail = function() {
        const section = document.getElementById('islr-detalle-section');
        section.style.display = 'none';
        _detalleVisible = false;

        // Scroll de regreso al gráfico
        document.getElementById('ISLR_GP').scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    function _islrCargarDetalle(anio) {
        const loading = document.getElementById('islr-detalle-loading');
        const wrap    = document.getElementById('islr-detalle-tabla-wrap');
        const tbody   = document.getElementById('islr-detalle-body');
        const nombres_meses = <?php echo json_encode($nombres_meses); ?>;

        loading.style.display = 'block';
        wrap.style.display = 'none';

        fetch(`../PHP/CTR/ISLR_Controller.php?action=islr_detail&anio=${anio}`, {
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(r => r.json())
        .then(data => {
            loading.style.display = 'none';
            wrap.style.display = '';

            const empleados = data.empleados || [];

            if (empleados.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="14" class="text-center text-muted py-4" style="font-size:.88rem;">
                            No hay registros de aportes ISLR para el año ${anio}.
                        </td>
                    </tr>`;
                _detalleCargado = true;
                return;
            }

            let html = '';
            empleados.forEach((emp, i) => {
                const rowBg = i % 2 === 0 ? '' : 'style="background:#fafafa;"';
                html += `<tr ${rowBg}>`;
                html += `<td style="white-space:nowrap; font-weight:600; color:#16213e;">
                            ${emp.nombre}
                         </td>`;

                let totalEmp = 0;
                for (let m = 1; m <= 12; m++) {
                    const monto = parseFloat(emp['mes_' + m] || 0);
                    totalEmp += monto;
                    html += `<td class="text-center ${monto > 0 ? '' : 'text-muted'}">
                                ${monto > 0 ? monto.toLocaleString('es-VE', {minimumFractionDigits:2}) : '—'}
                             </td>`;
                }

                html += `<td class="text-center fw-bold" style="color:#c0392b; background:#fff5f5;">
                            ${totalEmp.toLocaleString('es-VE', {minimumFractionDigits:2})}
                         </td>`;
                html += '</tr>';
            });

            tbody.innerHTML = html;
            _detalleCargado = true;
        })
        .catch(() => {
            loading.style.display = 'none';
            wrap.style.display = '';
            tbody.innerHTML = `
                <tr>
                    <td colspan="14" class="text-center text-muted py-3" style="font-size:.85rem;">
                        No se pudo cargar el detalle. Verifica el endpoint AJAX.
                    </td>
                </tr>`;
        });
    }

})();
</script>