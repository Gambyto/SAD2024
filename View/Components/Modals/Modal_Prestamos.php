<?php
/**
 * Modal_Prestamos.php
 * Modal unificado para los 4 indicadores de préstamos.
 * Incluye slider/carousel entre: Tasa de Uso, Promedio, Reembolso, Frecuencia de Renovación.
 * Cada panel tiene botón PDF propio.
 *
 * Ubicación sugerida: View/Modales/Modal_Prestamos.php
 * Incluir con: <?php include 'Modales/Modal_Prestamos.php'; ?>
 */
?>

<!-- ══════════════════════════════════════════════════════════════════════════
     MODAL PRÉSTAMOS
══════════════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="PrestamosModal" tabindex="-1"
     aria-labelledby="PrestamosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width:950px;">
        <div class="modal-content" style="border-radius:1rem; overflow:hidden;">

            <!-- ══ HEADER ══ -->
            <div class="modal-header"
                 style="background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
                        color:#fff; border-bottom:none; padding:1rem 1.4rem; flex-direction:column; gap:.6rem;">

                <!-- Fila 1: Título + Botón PDF + Close -->
                <div class="d-flex align-items-center w-100 gap-2">
                    <h5 class="modal-title mb-0 fw-bold" id="PrestamosModalLabel"
                        style="letter-spacing:.4px; flex:1;">
                        Análisis de Préstamos
                    </h5>

                    <!-- PDF dinámico según slide activo -->
                    <a id="prestamos-pdf-btn"
                       href="#" target="_blank"
                       class="btn btn-sm d-flex align-items-center gap-1"
                       style="border-radius:.5rem; font-size:.8rem; font-weight:600;
                              background:rgba(239,68,68,.15); border:1px solid rgba(239,68,68,.4);
                              color:#fca5a5;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                        PDF
                    </a>
                    <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Fila 2: Navegación de slides (pills) -->
                <div class="d-flex gap-2 flex-wrap w-100" id="prestamos-nav-pills">
                    <?php
                    $slides = [
                        ['icon'=>'📊','label'=>'Tasa de Uso'],
                        ['icon'=>'💵','label'=>'Promedios'],
                        ['icon'=>'📋','label'=>'Reembolso'],
                        ['icon'=>'🔄','label'=>'Renovación'],
                    ];
                    foreach ($slides as $i => $s):
                    ?>
                    <button class="prestamos-nav-pill btn btn-sm <?php echo $i===0?'active':''; ?>"
                            data-slide="<?php echo $i; ?>"
                            onclick="_prestamosGoTo(<?php echo $i; ?>)"
                            style="border-radius:2rem; font-size:.78rem; font-weight:600;
                                   background:<?php echo $i===0?'rgba(255,255,255,.22)':'rgba(255,255,255,.07)'; ?>;
                                   border:1px solid rgba(255,255,255,<?php echo $i===0?'.35':'.12'; ?>);
                                   color:#fff; transition:all .2s ease;">
                        <?php echo $s['icon'].' '.$s['label']; ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ══ BODY — Wrapper del carousel ══ -->
            <div class="modal-body p-0" style="background:#f8fafc; overflow:hidden; position:relative;">

                <!-- Loading overlay -->
                <div id="prestamos-loading"
                     style="display:none; position:absolute; inset:0; z-index:20;
                            background:rgba(248,250,252,.88); border-radius:0 0 1rem 1rem;
                            align-items:center; justify-content:center;">
                    <div class="text-center">
                        <div class="spinner-border text-primary mb-2" role="status"></div>
                        <p class="text-muted" style="font-size:.85rem;">Cargando datos…</p>
                    </div>
                </div>

                <!-- Viewport: recorta los slides que están fuera -->
                <div style="overflow:hidden; width:100%;">

                <!-- Slides container -->
                <div id="prestamos-slides-container"
                     style="display:flex; transition:transform .35s cubic-bezier(.4,0,.2,1); width:400%; align-items:flex-start;">

                    <!-- ─── SLIDE 0: TASA DE USO ─── -->
                    <div class="prestamos-slide p-4" style="width:25%; flex-shrink:0;">
                        <div class="row g-3 mb-3" id="uso-kpis">
                            <!-- KPIs inyectados vía JS -->
                        </div>
                        <div class="row g-3">
                            <div class="col-md-5">
                                <div class="card border-0 shadow-sm" style="border-radius:.8rem;">
                                    <div class="card-header border-0" style="background:#f1f5f9; border-radius:.8rem .8rem 0 0;">
                                        <small class="fw-bold text-muted" style="font-size:.72rem; text-transform:uppercase; letter-spacing:.4px;">Uso por Departamento</small>
                                    </div>
                                    <div class="card-body p-2">
                                        <div id="uso-deptos-list" style="max-height:220px; overflow-y:auto;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="card border-0 shadow-sm" style="border-radius:.8rem;">
                                    <div class="card-header border-0" style="background:#f1f5f9; border-radius:.8rem .8rem 0 0;">
                                        <small class="fw-bold text-muted" style="font-size:.72rem; text-transform:uppercase; letter-spacing:.4px;">Top 5 — Mayor Deuda Pendiente</small>
                                    </div>
                                    <div class="card-body p-2">
                                        <div id="uso-top5" style="max-height:220px; overflow-y:auto;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <canvas id="uso-chart" height="90"></canvas>
                        </div>
                    </div>

                    <!-- ─── SLIDE 1: PROMEDIO DE PRÉSTAMOS ─── -->
                    <div class="prestamos-slide p-4" style="width:25%; flex-shrink:0;">
                        <div class="row g-3 mb-3" id="prom-kpis"></div>
                        <div class="row g-3">
                            <div class="col-12">
                                <canvas id="prom-chart" height="110"></canvas>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="fw-bold text-muted d-block mb-2"
                                   style="font-size:.72rem; text-transform:uppercase; letter-spacing:.4px;">
                                Préstamos Activos (últimos 20)
                            </small>
                            <div style="overflow-x:auto;">
                                <table class="table table-sm table-hover mb-0" style="font-size:.82rem;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Empleado</th>
                                            <th class="text-end">Monto Orig.</th>
                                            <th class="text-end">Pendiente</th>
                                            <th class="text-end">Cuota Sem.</th>
                                        </tr>
                                    </thead>
                                    <tbody id="prom-tabla"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ─── SLIDE 2: TASA DE REEMBOLSO ─── -->
                    <div class="prestamos-slide p-4" style="width:25%; flex-shrink:0;">
                        <div class="row g-3 mb-3" id="rem-kpis"></div>
                        <div class="row g-3">
                            <div class="col-md-5">
                                <canvas id="rem-chart-doughnut" height="180"></canvas>
                            </div>
                            <div class="col-md-7">
                                <canvas id="rem-chart-bar" height="180"></canvas>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="fw-bold text-muted d-block mb-2"
                                   style="font-size:.72rem; text-transform:uppercase; letter-spacing:.4px;">
                                Detalle por Empleado
                            </small>
                            <div style="overflow-x:auto; max-height:200px; overflow-y:auto;">
                                <table class="table table-sm table-hover mb-0" style="font-size:.8rem;">
                                    <thead class="table-dark" style="position:sticky; top:0;">
                                        <tr>
                                            <th>Empleado</th>
                                            <th class="text-end">Original</th>
                                            <th class="text-end">Pagado</th>
                                            <th class="text-end">Pendiente</th>
                                            <th class="text-center">Progreso</th>
                                            <th class="text-center">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody id="rem-tabla"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ─── SLIDE 3: FRECUENCIA DE RENOVACIÓN ─── -->
                    <div class="prestamos-slide p-4" style="width:25%; flex-shrink:0;">
                        <div class="row g-3 mb-3" id="frec-kpis"></div>
                        <div class="row g-3">
                            <div class="col-12">
                                <canvas id="frec-chart" height="120"></canvas>
                            </div>
                        </div>
                        <div class="mt-3 p-3" style="background:#fff; border-radius:.8rem; border-left:4px solid #6366f1;">
                            <small class="fw-bold text-muted d-block mb-1"
                                   style="font-size:.72rem; text-transform:uppercase; letter-spacing:.4px;">
                                ¿Cómo se calcula?
                            </small>
                            <p class="mb-0" style="font-size:.82rem; color:#374151; line-height:1.6;">
                                La frecuencia de renovación se calcula como
                                <strong>(total de préstamos realizados × 0.033) × 100</strong>.
                                Un valor menor a <strong>40%</strong> es favorable (bajo riesgo de sobre-endeudamiento),
                                entre 40–60% requiere atención, y mayor al 60% indica alta rotación.
                            </p>
                        </div>
                        <div class="mt-3">
                            <small class="fw-bold text-muted d-block mb-2"
                                   style="font-size:.72rem; text-transform:uppercase; letter-spacing:.4px;">
                                Empleados con múltiples préstamos
                            </small>
                            <div id="frec-multi-list" style="max-height:160px; overflow-y:auto;"></div>
                        </div>
                    </div>

                </div><!-- /slides-container -->
                </div><!-- /viewport -->

            </div><!-- /modal-body -->

            <!-- ══ FOOTER: flechas de navegación ══ -->
            <div class="modal-footer"
                 style="background:#f1f5f9; border-top:1px solid #e2e8f0; padding:.7rem 1.2rem;
                        justify-content:space-between;">
                <button class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1"
                        id="prestamos-prev-btn" onclick="_prestamosPrev()" style="border-radius:.6rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                    Anterior
                </button>

                <!-- Dots -->
                <div class="d-flex gap-2 align-items-center" id="prestamos-dots">
                    <?php for ($i = 0; $i < 4; $i++): ?>
                    <span class="prestamos-dot"
                          data-slide="<?php echo $i; ?>"
                          onclick="_prestamosGoTo(<?php echo $i; ?>)"
                          style="width:9px; height:9px; border-radius:50%; cursor:pointer;
                                 background:<?php echo $i===0?'#3b82f6':'#cbd5e1'; ?>;
                                 transition:all .2s ease; display:inline-block;"></span>
                    <?php endfor; ?>
                </div>

                <button class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1"
                        id="prestamos-next-btn" onclick="_prestamosNext()" style="border-radius:.6rem;">
                    Siguiente
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <polyline points="9 6 15 12 9 18"/>
                    </svg>
                </button>
            </div>

        </div><!-- /modal-content -->
    </div><!-- /modal-dialog -->
</div><!-- /modal -->


<!-- ══════════════════════════════════════════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════════════════════════════════════════ -->
<script>
(function() {
    /* ────────────────── estado ────────────────── */
    let _slide        = 0;
    let _charts       = {};
    let _loaded       = [false, false, false, false];
    let _bsModal      = null;

    const TOTAL       = 4;
    const CTR_PATH    = '../PHP/CTR/Prestamos_Controller.php'; // ← ajusta si es necesario
    const PDF_PATHS   = [
        'PlantillaPDF/Prestamos-uso.php',
        'PlantillaPDF/Prestamos-promedio.php',
        'PlantillaPDF/Prestamos-reembolso.php',
        'PlantillaPDF/Prestamos-renovacion.php',
    ];
    const MESES = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

    /* ────────────────── API pública ────────────────── */
    window.openPrestamosModal = function(slideIndex) {
        _slide = slideIndex ?? 0;
        const el = document.getElementById('PrestamosModal');
        if (!_bsModal) _bsModal = new bootstrap.Modal(el);
        _bsModal.show();
        _applySlide(_slide, true);
    };

    window._prestamosGoTo  = function(i) { _slide = i; _applySlide(i); };
    window._prestamosPrev  = function()  { if (_slide > 0)         _prestamosGoTo(_slide - 1); };
    window._prestamosNext  = function()  { if (_slide < TOTAL - 1) _prestamosGoTo(_slide + 1); };

    /* ────────────────── navegación ────────────────── */
    function _applySlide(i, force) {
        // mover transform
        const container = document.getElementById('prestamos-slides-container');
        if (container) container.style.transform = `translateX(-${i * 25}%)`;

        // Ocultar slides inactivos para que no aporten altura al modal
        document.querySelectorAll('.prestamos-slide').forEach((slide, idx) => {
            if (idx === i) {
                slide.style.visibility  = 'visible';
                slide.style.pointerEvents = 'auto';
                slide.style.height      = 'auto';
                slide.style.overflow    = 'visible';
                slide.style.padding     = '1.5rem';
            } else {
                slide.style.visibility  = 'hidden';
                slide.style.pointerEvents = 'none';
                slide.style.height      = '0';
                slide.style.overflow    = 'hidden';
                slide.style.padding     = '0';
            }
        });

        // actualizar pills
        document.querySelectorAll('.prestamos-nav-pill').forEach((btn, idx) => {
            const active = idx === i;
            btn.style.background = active ? 'rgba(255,255,255,.22)' : 'rgba(255,255,255,.07)';
            btn.style.border     = active ? '1px solid rgba(255,255,255,.35)' : '1px solid rgba(255,255,255,.12)';
        });

        // actualizar dots
        document.querySelectorAll('.prestamos-dot').forEach((dot, idx) => {
            dot.style.background = idx === i ? '#3b82f6' : '#cbd5e1';
            dot.style.transform  = idx === i ? 'scale(1.3)' : 'scale(1)';
        });

        // flechas
        const prev = document.getElementById('prestamos-prev-btn');
        const next = document.getElementById('prestamos-next-btn');
        if (prev) prev.disabled = i === 0;
        if (next) next.disabled = i === TOTAL - 1;

        // PDF button
        const pdfBtn = document.getElementById('prestamos-pdf-btn');
        if (pdfBtn) pdfBtn.href = PDF_PATHS[i];

        // Cargar datos si no se han cargado
        if (!_loaded[i]) _loadSlide(i);
    }

    /* ────────────────── carga de datos ────────────────── */
    function _loadSlide(i) {
        const actions = ['tasa_uso', 'promedio_prestamos', 'tasa_reembolso', 'frecuencia_renovacion'];
        const loading = document.getElementById('prestamos-loading');
        if (loading) loading.style.display = 'flex';

        fetch(`${CTR_PATH}?action=${actions[i]}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (loading) loading.style.display = 'none';
            _loaded[i] = true;
            if (i === 0) _renderUso(data);
            if (i === 1) _renderPromedio(data);
            if (i === 2) _renderReembolso(data);
            if (i === 3) _renderFrecuencia(data);
        })
        .catch(() => {
            if (loading) loading.style.display = 'none';
            console.error(`Error al cargar slide ${i}`);
        });
    }

    /* ────────────────── helpers ────────────────── */
    function _fmt(n, dec = 2) {
        return parseFloat(n).toLocaleString('es-VE', { minimumFractionDigits: dec });
    }
    function _badge(value, thresholds) {
        // thresholds = {success: <=X, warning: <=Y, else: danger}
        if (value <= thresholds.success)          return ['success','bg-success'];
        else if (value <= thresholds.warning)     return ['warning','bg-warning text-dark'];
        else                                      return ['danger','bg-danger'];
    }
    function _kpiCard(label, value, sub, color) {
        return `<div class="col">
            <div class="card border-0 text-center p-2" style="background:${color}; border-radius:.75rem;">
                <small class="text-muted" style="font-size:.69rem; text-transform:uppercase; letter-spacing:.3px;">${label}</small>
                <strong style="font-size:1.1rem; color:#1e293b;">${value}</strong>
                ${sub ? `<span style="font-size:.72rem; color:#6b7280;">${sub}</span>` : ''}
            </div>
        </div>`;
    }
    function _destroyChart(id) {
        if (_charts[id]) { _charts[id].destroy(); delete _charts[id]; }
    }

    /* ────────────────── RENDER SLIDE 0: TASA DE USO ────────────────── */
    function _renderUso(d) {
        const [sLabel, sBadge] = _badge(d.promedio, {success:40, warning:60});

        // KPIs
        document.getElementById('uso-kpis').innerHTML =
            _kpiCard('Tasa de uso', d.promedio + '%', `${d.con_prestamo} de ${d.total_empleados} empleados`, '#f0f9ff') +
            _kpiCard('Con préstamo activo', d.con_prestamo, 'empleados', '#f0fdf4') +
            _kpiCard('Sin préstamo', d.sin_prestamo, 'empleados', '#fff7ed') +
            `<div class="col">
                <div class="card border-0 text-center p-2" style="background:#fdf4ff; border-radius:.75rem;">
                    <small class="text-muted" style="font-size:.69rem; text-transform:uppercase; letter-spacing:.3px;">Estado</small>
                    <span class="badge ${sBadge} mt-1" style="font-size:.8rem;">${sLabel.toUpperCase()}</span>
                </div>
            </div>`;

        // Departamentos
        const depList = document.getElementById('uso-deptos-list');
        if (depList && d.por_departamento) {
            depList.innerHTML = d.por_departamento.map(dep => `
                <div class="mb-2">
                    <div class="d-flex justify-content-between mb-1">
                        <small style="font-size:.76rem; font-weight:600;">${dep.departamento}</small>
                        <small style="font-size:.76rem; color:#6b7280;">${dep.con_prestamo}/${dep.total} — ${dep.porcentaje}%</small>
                    </div>
                    <div style="background:#e2e8f0; border-radius:4px; height:6px; overflow:hidden;">
                        <div style="width:${dep.porcentaje}%; height:100%;
                             background:${dep.porcentaje>60?'#ef4444':dep.porcentaje>40?'#f59e0b':'#22c55e'};
                             border-radius:4px; transition:width .5s ease;"></div>
                    </div>
                </div>`).join('');
        }

        // Top 5
        const top5 = document.getElementById('uso-top5');
        if (top5 && d.top5_deuda) {
            top5.innerHTML = `<table class="table table-sm mb-0" style="font-size:.79rem;">
                <thead><tr><th>Empleado</th><th class="text-end">Pendiente $</th></tr></thead>
                <tbody>` +
                d.top5_deuda.map((p, i) => `
                <tr>
                    <td style="font-weight:600;">${p.nombre} ${p.apellido}</td>
                    <td class="text-end text-danger fw-bold">${_fmt(p.monto_desc)}</td>
                </tr>`).join('') +
                `</tbody></table>`;
        }

        // Gráfico donut
        _destroyChart('uso-chart');
        const ctx = document.getElementById('uso-chart');
        if (ctx) {
            _charts['uso-chart'] = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Con préstamo', 'Sin préstamo'],
                    datasets: [{ data: [d.con_prestamo, d.sin_prestamo],
                        backgroundColor: ['#3b82f6','#e2e8f0'],
                        borderWidth: 0 }]
                },
                options: { responsive: true, plugins: { legend: { position: 'right' } } }
            });
        }
    }

    /* ────────────────── RENDER SLIDE 1: PROMEDIOS ────────────────── */
    function _renderPromedio(d) {
        document.getElementById('prom-kpis').innerHTML =
            _kpiCard('Promedio mensual actual', _fmt(d.actual_mensual) + ' $', 'este mes', '#f0f9ff') +
            _kpiCard('Promedio semanal actual', _fmt(d.actual_semanal) + ' $', 'esta semana', '#f0fdf4') +
            _kpiCard('Prom. histórico mensual', _fmt(d.promedio) + ' $', 'todos los períodos', '#fff7ed') +
            _kpiCard('Máximo mensual', _fmt(d.max) + ' $', 'pico histórico', '#fdf4ff');

        // Gráfico de línea
        _destroyChart('prom-chart');
        const ctx = document.getElementById('prom-chart');
        if (ctx && d.historial) {
            _charts['prom-chart'] = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: d.historial.map(h => h.mes),
                    datasets: [
                        { label: 'Promedio Mensual $', data: d.historial.map(h => h.mensual),
                          borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,.1)',
                          tension: .4, fill: true, pointRadius: 4 },
                        { label: 'Promedio Semanal $', data: d.historial.map(h => h.semanal),
                          borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,.08)',
                          tension: .4, fill: false, borderDash: [5,3], pointRadius: 3 }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top', labels: { font: { size: 11 } } } },
                    scales: { y: { ticks: { callback: v => '$' + _fmt(v) } } }
                }
            });
        }

        // Tabla
        const tbody = document.getElementById('prom-tabla');
        if (tbody && d.prestamos_activos) {
            tbody.innerHTML = d.prestamos_activos.map(p => `
                <tr>
                    <td>${p.nombre} ${p.apellido}</td>
                    <td class="text-end">${_fmt(p.monto)}</td>
                    <td class="text-end text-warning fw-bold">${_fmt(p.monto_desc)}</td>
                    <td class="text-end">${_fmt(p.descuento)}</td>
                </tr>`).join('');
        }
    }

    /* ────────────────── RENDER SLIDE 2: REEMBOLSO ────────────────── */
    function _renderReembolso(d) {
        const [sLabel, sBadge] = _badge(d.global >= 50 ? 0 : d.global >= 31 ? 50 : 100, {success:40, warning:60});
        const stateBadge = d.global > 50 ? 'bg-success' : d.global >= 31 ? 'bg-warning text-dark' : 'bg-danger';
        const stateLabel = d.global > 50 ? 'SALUDABLE' : d.global >= 31 ? 'ATENCIÓN' : 'CRÍTICO';

        document.getElementById('rem-kpis').innerHTML =
            _kpiCard('Tasa global de reembolso', d.global + '%', 'del monto total prestado', '#f0f9ff') +
            _kpiCard('Préstamos vencidos', d.vencidos_cnt, `${_fmt(d.vencidos_monto)} $ pendiente`, '#fff1f2') +
            `<div class="col">
                <div class="card border-0 text-center p-2" style="background:#fdf4ff; border-radius:.75rem;">
                    <small class="text-muted" style="font-size:.69rem; text-transform:uppercase; letter-spacing:.3px;">Estado</small>
                    <span class="badge ${stateBadge} mt-1" style="font-size:.8rem;">${stateLabel}</span>
                </div>
            </div>`;

        // Donut global
        _destroyChart('rem-chart-doughnut');
        const ctx1 = document.getElementById('rem-chart-doughnut');
        if (ctx1) {
            _charts['rem-chart-doughnut'] = new Chart(ctx1, {
                type: 'doughnut',
                data: {
                    labels: ['Reembolsado', 'Pendiente'],
                    datasets: [{ data: [d.global, 100 - d.global],
                        backgroundColor: ['#22c55e','#fca5a5'],
                        borderWidth: 0 }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { size: 10 } } },
                        tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.raw + '%' } }
                    }
                }
            });
        }

        // Bar por año
        _destroyChart('rem-chart-bar');
        const ctx2 = document.getElementById('rem-chart-bar');
        if (ctx2 && d.por_anio) {
            const anios = d.por_anio.slice(0, 5).reverse();
            _charts['rem-chart-bar'] = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: anios.map(a => a.anio),
                    datasets: [
                        { label: 'Prestado $', data: anios.map(a => a.total_prestado),
                          backgroundColor: 'rgba(99,102,241,.7)', borderRadius: 4 },
                        { label: 'Reembolsado $', data: anios.map(a => a.total_reembolsado),
                          backgroundColor: 'rgba(34,197,94,.7)', borderRadius: 4 }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top', labels: { font: { size: 10 } } } },
                    scales: { y: { ticks: { callback: v => '$' + _fmt(v, 0) } } }
                }
            });
        }

        // Tabla detalle
        const tbody = document.getElementById('rem-tabla');
        if (tbody && d.detalle) {
            tbody.innerHTML = d.detalle.map(p => `
                <tr>
                    <td style="white-space:nowrap;">${p.nombre}</td>
                    <td class="text-end">${_fmt(p.monto)}</td>
                    <td class="text-end text-success fw-bold">${_fmt(p.pagado)}</td>
                    <td class="text-end text-danger fw-bold">${_fmt(p.pendiente)}</td>
                    <td class="text-center" style="min-width:80px;">
                        <div style="background:#e2e8f0; border-radius:4px; height:6px; overflow:hidden;">
                            <div style="width:${p.progreso}%; height:100%;
                                 background:${p.progreso>=70?'#22c55e':p.progreso>=40?'#f59e0b':'#ef4444'};
                                 border-radius:4px;"></div>
                        </div>
                        <span style="font-size:.7rem; color:#6b7280;">${p.progreso}%</span>
                    </td>
                    <td class="text-center">
                        <span class="badge ${p.vencido ? 'bg-danger' : 'bg-success'}" style="font-size:.68rem;">
                            ${p.vencido ? 'Vencido' : 'Al día'}
                        </span>
                    </td>
                </tr>`).join('');
        }
    }

    /* ────────────────── RENDER SLIDE 3: FRECUENCIA ────────────────── */
    function _renderFrecuencia(d) {
        const fPct = d.frecuency;
        const stateBadge = fPct < 41 ? 'bg-success' : fPct <= 60 ? 'bg-warning text-dark' : 'bg-danger';
        const stateLabel = fPct < 41 ? 'ÓPTIMO' : fPct <= 60 ? 'MODERADO' : 'ALTO';

        document.getElementById('frec-kpis').innerHTML =
            _kpiCard('Tasa de renovación', fPct + '%', 'índice calculado', '#f0f9ff') +
            _kpiCard('Préstamos totales', d.prestamos_totales, 'histórico acumulado', '#f0fdf4') +
            _kpiCard('Renovaciones', d.renovaciones, 'préstamos adicionales', '#fff7ed') +
            `<div class="col">
                <div class="card border-0 text-center p-2" style="background:#fdf4ff; border-radius:.75rem;">
                    <small class="text-muted" style="font-size:.69rem; text-transform:uppercase; letter-spacing:.3px;">Riesgo</small>
                    <span class="badge ${stateBadge} mt-1" style="font-size:.8rem;">${stateLabel}</span>
                </div>
            </div>`;

        // Gráfico de barras tendencia mensual
        _destroyChart('frec-chart');
        const ctx = document.getElementById('frec-chart');
        if (ctx && d.tendencia_mensual) {
            _charts['frec-chart'] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: d.tendencia_mensual.map(t => t.mes),
                    datasets: [{
                        label: 'Préstamos otorgados',
                        data: d.tendencia_mensual.map(t => t.cantidad),
                        backgroundColor: d.tendencia_mensual.map((_, i) =>
                            i === d.tendencia_mensual.length - 1
                                ? 'rgba(99,102,241,1)'
                                : 'rgba(99,102,241,.5)'),
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        title: { display: true, text: 'Préstamos otorgados — Últimos 12 meses',
                                 font: { size: 11 }, color: '#374151' }
                    },
                    scales: { y: { ticks: { stepSize: 1 }, beginAtZero: true } }
                }
            });
        }

        // Lista multi-préstamo (placeholder)
        const multiList = document.getElementById('frec-multi-list');
        if (multiList) {
            multiList.innerHTML = `
                <div class="p-3 text-center" style="background:#f8fafc; border-radius:.6rem;">
                    <strong style="font-size:1.1rem; color:#6366f1;">${d.empleados_multi}</strong>
                    <small class="d-block text-muted" style="font-size:.78rem;">
                        empleado(s) han solicitado más de un préstamo
                    </small>
                    <small class="d-block text-muted mt-1" style="font-size:.75rem;">
                        ${d.renovaciones} préstamo(s) adicionales acumulados
                    </small>
                </div>`;
        }
    }

    /* ────────────────── inicializar modal ────────────────── */
    document.addEventListener('shown.bs.modal', function(e) {
        if (e.target.id === 'PrestamosModal') {
            if (!_loaded[_slide]) _loadSlide(_slide);
        }
    });

})();
</script>