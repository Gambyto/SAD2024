<?php
/**
 * Modal_Prestamos.php  — v2
 * Modal unificado para los 4 indicadores de préstamos.
 *
 * SLIDE 0 — Tasa de Uso
 *   · KPIs: tasa %, con/sin préstamo, estado
 *   · Uso por departamento (barras)
 *   · Top-5 mayor deuda pendiente
 *   · Donut con/sin préstamo
 *   · ► Tabla: TRABAJADORES SIN PRÉSTAMO  (nuevo)
 *   → PDF: Prestamos-uso.php
 *
 * SLIDE 1 — Promedios
 *   · KPIs: promedio mensual/semanal/histórico/máximo
 *   · Gráfico línea histórico
 *   · Tabla préstamos activos con filtro Pagados / Pendientes / Todos (nuevo)
 *   · Resumen por trabajador: total préstamos, pagados, pendientes (nuevo)
 *   → PDF: Prestamos-promedio.php  (acepta ?filtro=pagados|pendientes|todos)
 *
 * SLIDE 2 — Tasa de Reembolso
 *   · KPIs: tasa global, vencidos
 *   · Donut reembolsado/pendiente  +  barras por año
 *   · Tabla detalle con progreso/estado
 *   · ► Ranking: TRABAJADOR CON MÁS PRÉSTAMOS  (nuevo — top podio)
 *   → PDF: Prestamos-reembolso.php
 *
 * SLIDE 3 — Frecuencia de Renovación
 *   · KPIs: tasa renovación, totales, renovaciones, riesgo
 *   · Barras tendencia mensual — préstamos otorgados en el mes (nuevo detallado)
 *   · Resumen general del mes actual (nuevo)
 *   · Lista empleados multi-préstamo
 *   → PDF: Prestamos-renovacion.php  (acepta ?mes=YYYY-MM)
 */
?>

<!-- ══════════════════════════════════════════════════════════════════
     MODAL PRÉSTAMOS
══════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="PrestamosModal" tabindex="-1"
     aria-labelledby="PrestamosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width:980px;">
        <div class="modal-content" style="border-radius:1rem; overflow:hidden;">

            <!-- ══ HEADER ══ -->
            <div class="modal-header"
                 style="background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
                        color:#fff; border-bottom:none; padding:1rem 1.4rem;
                        flex-direction:column; gap:.6rem;">

                <!-- Fila 1: Título + PDF + Cerrar -->
                <div class="d-flex align-items-center w-100 gap-2">
                    <h5 class="modal-title mb-0 fw-bold" id="PrestamosModalLabel"
                        style="letter-spacing:.4px; flex:1;">
                        Análisis de Préstamos
                    </h5>

                    <!-- PDF dinámico según slide activo -->
                    <a id="prestamos-pdf-btn" href="#" target="_blank"
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

                <!-- Fila 2: Pills de navegación -->
                <div class="d-flex gap-2 flex-wrap w-100" id="prestamos-nav-pills">
                    <?php
                    $slides = [
                        ['icon'=>'📊', 'label'=>'Tasa de Uso'],
                        ['icon'=>'💵', 'label'=>'Promedios'],
                        ['icon'=>'📋', 'label'=>'Reembolso'],
                        ['icon'=>'🔄', 'label'=>'Renovación'],
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

            <!-- ══ BODY ══ -->
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

                <!-- Viewport -->
                <div style="overflow:hidden; width:100%;">

                <!-- Slides container -->
                <div id="prestamos-slides-container"
                     style="display:flex; transition:transform .35s cubic-bezier(.4,0,.2,1);
                            width:400%; align-items:flex-start;">

<!-- ═══════════════════════════════════════════════════════════════
     SLIDE 0 — TASA DE USO
     Nuevo: tabla de trabajadores SIN préstamo
═══════════════════════════════════════════════════════════════ -->
<div class="prestamos-slide p-4" style="width:25%; flex-shrink:0;">
    <div class="row g-3 mb-3" id="uso-kpis"></div>

    <div class="row g-3 mb-3">
        <!-- Uso por departamento -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100" style="border-radius:.8rem;">
                <div class="card-header border-0" style="background:#f1f5f9; border-radius:.8rem .8rem 0 0;">
                    <small class="fw-bold text-muted" style="font-size:.72rem; text-transform:uppercase; letter-spacing:.4px;">
                        Uso por Departamento
                    </small>
                </div>
                <div class="card-body p-2">
                    <div id="uso-deptos-list" style="max-height:200px; overflow-y:auto;"></div>
                </div>
            </div>
        </div>

        <!-- Top 5 mayor deuda -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm h-100" style="border-radius:.8rem;">
                <div class="card-header border-0" style="background:#f1f5f9; border-radius:.8rem .8rem 0 0;">
                    <small class="fw-bold text-muted" style="font-size:.72rem; text-transform:uppercase; letter-spacing:.4px;">
                        Top 5 — Mayor Deuda Pendiente
                    </small>
                </div>
                <div class="card-body p-2">
                    <div id="uso-top5" style="max-height:200px; overflow-y:auto;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Donut -->
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <canvas id="uso-chart" height="130"></canvas>
        </div>

        <!-- ★ NUEVO: Trabajadores sin préstamo -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius:.8rem;">
                <div class="card-header border-0 d-flex align-items-center justify-content-between"
                     style="background:#fff7ed; border-radius:.8rem .8rem 0 0;">
                    <small class="fw-bold text-muted" style="font-size:.72rem; text-transform:uppercase; letter-spacing:.4px;">
                        👤 Trabajadores sin préstamo activo
                    </small>
                    <!-- PDF solo de este sub-reporte -->
                    <a id="uso-sinprestamo-pdf" href="PlantillaPDF/Prestamos-sin-prestamo.php"
                       target="_blank"
                       class="btn btn-xs d-flex align-items-center gap-1"
                       style="font-size:.7rem; padding:2px 8px; border-radius:.4rem;
                              background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.3);
                              color:#dc2626; text-decoration:none;">
                        📄 PDF
                    </a>
                </div>
                <div class="card-body p-2" style="max-height:130px; overflow-y:auto;">
                    <div id="uso-sin-prestamo-list"></div>
                </div>
            </div>
        </div>
    </div>
</div><!-- /slide 0 -->


<!-- ═══════════════════════════════════════════════════════════════
     SLIDE 1 — PROMEDIOS
     Nuevo: filtro Todos / Pagados / Pendientes + resumen por trabajador
═══════════════════════════════════════════════════════════════ -->
<div class="prestamos-slide p-4" style="width:25%; flex-shrink:0;">
    <div class="row g-3 mb-3" id="prom-kpis"></div>

    <!-- Gráfico histórico -->
    <div class="row g-3 mb-3">
        <div class="col-12">
            <canvas id="prom-chart" height="100"></canvas>
        </div>
    </div>

    <!-- ★ NUEVO: Filtro de estado + tabla por trabajador -->
    <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
        <small class="fw-bold text-muted" style="font-size:.72rem; text-transform:uppercase; letter-spacing:.4px;">
            Préstamos por trabajador
        </small>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Selector de filtro -->
            <div class="btn-group btn-group-sm" role="group" id="prom-filtro-grupo">
                <button type="button" class="btn btn-outline-secondary active" data-filtro="todos"
                        onclick="_promFiltro('todos')">Todos</button>
                <button type="button" class="btn btn-outline-success" data-filtro="pagados"
                        onclick="_promFiltro('pagados')">Pagados</button>
                <button type="button" class="btn btn-outline-warning" data-filtro="pendientes"
                        onclick="_promFiltro('pendientes')">Pendientes</button>
            </div>
            <!-- PDF con filtro activo -->
            <a id="prom-pdf-filtro"
               href="PlantillaPDF/Prestamos-promedio.php?filtro=todos"
               target="_blank"
               class="btn btn-sm d-flex align-items-center gap-1"
               style="font-size:.72rem; padding:3px 10px; border-radius:.4rem;
                      background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.3);
                      color:#dc2626; text-decoration:none;">
                📄 PDF
            </a>
        </div>
    </div>

    <div style="overflow-x:auto; max-height:220px; overflow-y:auto;">
        <table class="table table-sm table-hover mb-0" style="font-size:.8rem;">
            <thead class="table-dark" style="position:sticky; top:0;">
                <tr>
                    <th>Empleado</th>
                    <th class="text-center"># Préstamos</th>
                    <th class="text-end">Monto Orig.</th>
                    <th class="text-end">Pagado</th>
                    <th class="text-end text-warning">Pendiente</th>
                    <th class="text-center">Estado</th>
                </tr>
            </thead>
            <tbody id="prom-tabla"></tbody>
        </table>
    </div>
</div><!-- /slide 1 -->


<!-- ═══════════════════════════════════════════════════════════════
     SLIDE 2 — TASA DE REEMBOLSO
     Nuevo: ranking / podio del trabajador con más préstamos
═══════════════════════════════════════════════════════════════ -->
<div class="prestamos-slide p-4" style="width:25%; flex-shrink:0;">
    <div class="row g-3 mb-3" id="rem-kpis"></div>

    <div class="row g-3 mb-3">
        <div class="col-md-5">
            <canvas id="rem-chart-doughnut" height="180"></canvas>
        </div>
        <div class="col-md-7">
            <canvas id="rem-chart-bar" height="180"></canvas>
        </div>
    </div>

    <!-- ★ NUEVO: Trabajador con más préstamos adquiridos -->
    <div class="mb-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <small class="fw-bold text-muted" style="font-size:.72rem; text-transform:uppercase; letter-spacing:.4px;">
                🏆 Trabajadores con más préstamos adquiridos
            </small>
            <a href="PlantillaPDF/Prestamos-top-trabajadores.php" target="_blank"
               class="btn btn-sm d-flex align-items-center gap-1"
               style="font-size:.72rem; padding:3px 10px; border-radius:.4rem;
                      background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.3);
                      color:#dc2626; text-decoration:none;">
                📄 PDF
            </a>
        </div>
        <div id="rem-top-trabajadores"></div>
    </div>

    <!-- Detalle individual por empleado -->
    <small class="fw-bold text-muted d-block mb-2"
           style="font-size:.72rem; text-transform:uppercase; letter-spacing:.4px;">
        Detalle de reembolso por empleado
    </small>
    <div style="overflow-x:auto; max-height:180px; overflow-y:auto;">
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
</div><!-- /slide 2 -->


<!-- ═══════════════════════════════════════════════════════════════
     SLIDE 3 — FRECUENCIA DE RENOVACIÓN
     Nuevo: resumen mensual detallado + selector de mes para PDF
═══════════════════════════════════════════════════════════════ -->
<div class="prestamos-slide p-4" style="width:25%; flex-shrink:0;">
    <div class="row g-3 mb-3" id="frec-kpis"></div>

    <!-- ★ NUEVO: resumen del mes actual + selector de mes -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius:.8rem;">
        <div class="card-header border-0 d-flex align-items-center justify-content-between flex-wrap gap-2"
             style="background:#f0f9ff; border-radius:.8rem .8rem 0 0;">
            <small class="fw-bold text-muted" style="font-size:.72rem; text-transform:uppercase; letter-spacing:.4px;">
                📅 Préstamos otorgados en el mes
            </small>
            <div class="d-flex align-items-center gap-2">
                <input type="month" id="frec-mes-sel"
                       class="form-control form-control-sm"
                       style="font-size:.78rem; max-width:150px;"
                       value="<?php echo date('Y-m'); ?>">
                <button class="btn btn-sm btn-outline-primary" onclick="_frecCargarMes()"
                        style="font-size:.75rem; padding:2px 10px; border-radius:.4rem;">
                    Ver
                </button>
                <a id="frec-mes-pdf"
                   href="PlantillaPDF/Prestamos-renovacion.php?mes=<?php echo date('Y-m'); ?>"
                   target="_blank"
                   class="btn btn-sm d-flex align-items-center gap-1"
                   style="font-size:.72rem; padding:3px 10px; border-radius:.4rem;
                          background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.3);
                          color:#dc2626; text-decoration:none;">
                    📄 PDF
                </a>
            </div>
        </div>
        <div class="card-body p-2">
            <!-- KPIs del mes seleccionado -->
            <div class="row g-2 mb-2" id="frec-mes-kpis"></div>
            <!-- Tabla detallada del mes -->
            <div style="overflow-x:auto; max-height:160px; overflow-y:auto;">
                <table class="table table-sm table-hover mb-0" style="font-size:.79rem;">
                    <thead class="table-light" style="position:sticky; top:0;">
                        <tr>
                            <th>Empleado</th>
                            <th class="text-end">Monto</th>
                            <th class="text-end">Cuota Sem.</th>
                            <th class="text-center">Cuotas</th>
                            <th>Concepto</th>
                            <th class="text-center">Fecha</th>
                        </tr>
                    </thead>
                    <tbody id="frec-mes-tabla"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Gráfico tendencia mensual -->
    <div class="row g-3 mb-3">
        <div class="col-12">
            <canvas id="frec-chart" height="100"></canvas>
        </div>
    </div>

    <!-- Explicación de cálculo -->
    <div class="p-3 mb-2" style="background:#fff; border-radius:.8rem; border-left:4px solid #6366f1;">
        <small class="fw-bold text-muted d-block mb-1"
               style="font-size:.72rem; text-transform:uppercase; letter-spacing:.4px;">
            ¿Cómo se calcula?
        </small>
        <p class="mb-0" style="font-size:.8rem; color:#374151; line-height:1.6;">
            Frecuencia = <strong>(total préstamos × 0.033) × 100</strong>.
            &lt; 40 % → favorable · 40–60 % → atención · &gt; 60 % → alta rotación.
        </p>
    </div>

    <!-- Empleados multi-préstamo -->
    <small class="fw-bold text-muted d-block mb-2"
           style="font-size:.72rem; text-transform:uppercase; letter-spacing:.4px;">
        Empleados con múltiples préstamos
    </small>
    <div id="frec-multi-list" style="max-height:130px; overflow-y:auto;"></div>
</div><!-- /slide 3 -->


                </div><!-- /slides-container -->
                </div><!-- /viewport -->
            </div><!-- /modal-body -->

            <!-- ══ FOOTER: flechas + dots ══ -->
            <div class="modal-footer"
                 style="background:#f1f5f9; border-top:1px solid #e2e8f0;
                        padding:.7rem 1.2rem; justify-content:space-between;">

                <button class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1"
                        id="prestamos-prev-btn" onclick="_prestamosPrev()"
                        style="border-radius:.6rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                    Anterior
                </button>

                <div class="d-flex gap-2 align-items-center" id="prestamos-dots">
                    <?php for ($i = 0; $i < 4; $i++): ?>
                    <span class="prestamos-dot" data-slide="<?php echo $i; ?>"
                          onclick="_prestamosGoTo(<?php echo $i; ?>)"
                          style="width:9px; height:9px; border-radius:50%; cursor:pointer;
                                 background:<?php echo $i===0?'#3b82f6':'#cbd5e1'; ?>;
                                 transition:all .2s ease; display:inline-block;"></span>
                    <?php endfor; ?>
                </div>

                <button class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1"
                        id="prestamos-next-btn" onclick="_prestamosNext()"
                        style="border-radius:.6rem;">
                    Siguiente
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round">
                        <polyline points="9 6 15 12 9 18"/>
                    </svg>
                </button>
            </div>

        </div><!-- /modal-content -->
    </div><!-- /modal-dialog -->
</div><!-- /modal -->


<!-- ══════════════════════════════════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════════════════════════════════ -->
<script>
(function () {

    /* ──── estado ──── */
    let _slide   = 0;
    let _charts  = {};
    let _loaded  = [false, false, false, false];
    let _bsModal = null;

    // Datos globales en memoria (para sub-filtros sin nueva petición)
    let _dataUso  = null;
    let _dataProm = null;
    let _dataRem  = null;
    let _dataFrec = null;
    let _promFiltroActivo = 'todos';

    const TOTAL     = 4;
    const CTR_PATH  = '../PHP/CTR/Prestamos_Controller.php';
    const PDF_PATHS = [
        'PlantillaPDF/Prestamos-uso.php',
        'PlantillaPDF/Prestamos-promedio.php',
        'PlantillaPDF/Prestamos-reembolso.php',
        'PlantillaPDF/Prestamos-renovacion.php',
    ];

    /* ──── API pública ──── */
    window.openPrestamosModal = function (slideIndex) {
        _slide = slideIndex ?? 0;
        const el = document.getElementById('PrestamosModal');
        if (!_bsModal) _bsModal = new bootstrap.Modal(el);
        _bsModal.show();
        _applySlide(_slide, true);
    };
    window._prestamosGoTo = function (i) { _slide = i; _applySlide(i); };
    window._prestamosPrev = function ()  { if (_slide > 0)         _prestamosGoTo(_slide - 1); };
    window._prestamosNext = function ()  { if (_slide < TOTAL - 1) _prestamosGoTo(_slide + 1); };

    /* ──── filtro promedios ──── */
    window._promFiltro = function (filtro) {
        _promFiltroActivo = filtro;
        document.querySelectorAll('#prom-filtro-grupo button').forEach(b => {
            b.classList.toggle('active', b.dataset.filtro === filtro);
        });
        const pdfBtn = document.getElementById('prom-pdf-filtro');
        if (pdfBtn) pdfBtn.href = `PlantillaPDF/Prestamos-promedio.php?filtro=${filtro}`;
        if (_dataProm) _renderPromTabla(_dataProm, filtro);
    };

    /* ──── cargar mes en frecuencia ──── */
    window._frecCargarMes = function () {
        const sel = document.getElementById('frec-mes-sel');
        const mes = sel ? sel.value : '';
        if (!mes) return;
        const pdfBtn = document.getElementById('frec-mes-pdf');
        if (pdfBtn) pdfBtn.href = `PlantillaPDF/Prestamos-renovacion.php?mes=${mes}`;
        if (_dataFrec) _renderFrecMes(_dataFrec, mes);
    };

    /* ──── navegación ──── */
    function _applySlide(i, force) {
        const container = document.getElementById('prestamos-slides-container');
        if (container) container.style.transform = `translateX(-${i * 25}%)`;

        document.querySelectorAll('.prestamos-slide').forEach((slide, idx) => {
            if (idx === i) {
                slide.style.visibility   = 'visible';
                slide.style.pointerEvents = 'auto';
                slide.style.height       = 'auto';
                slide.style.overflow     = 'visible';
                slide.style.padding      = '1.5rem';
            } else {
                slide.style.visibility   = 'hidden';
                slide.style.pointerEvents = 'none';
                slide.style.height       = '0';
                slide.style.overflow     = 'hidden';
                slide.style.padding      = '0';
            }
        });

        document.querySelectorAll('.prestamos-nav-pill').forEach((btn, idx) => {
            const active = idx === i;
            btn.style.background = active ? 'rgba(255,255,255,.22)' : 'rgba(255,255,255,.07)';
            btn.style.border     = active ? '1px solid rgba(255,255,255,.35)' : '1px solid rgba(255,255,255,.12)';
        });

        document.querySelectorAll('.prestamos-dot').forEach((dot, idx) => {
            dot.style.background = idx === i ? '#3b82f6' : '#cbd5e1';
            dot.style.transform  = idx === i ? 'scale(1.3)' : 'scale(1)';
        });

        const prev = document.getElementById('prestamos-prev-btn');
        const next = document.getElementById('prestamos-next-btn');
        if (prev) prev.disabled = i === 0;
        if (next) next.disabled = i === TOTAL - 1;

        const pdfBtn = document.getElementById('prestamos-pdf-btn');
        if (pdfBtn) pdfBtn.href = PDF_PATHS[i];

        if (!_loaded[i]) _loadSlide(i);
    }

    /* ──── carga de datos ──── */
    const _actions = ['tasa_uso','promedio_prestamos','tasa_reembolso','frecuencia_renovacion'];

    function _loadSlide(i) {
        const loading = document.getElementById('prestamos-loading');
        if (loading) loading.style.display = 'flex';

        fetch(`${CTR_PATH}?action=${_actions[i]}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (loading) loading.style.display = 'none';
            _loaded[i] = true;
            if (i === 0) { _dataUso  = data; _renderUso(data); }
            if (i === 1) { _dataProm = data; _renderPromedio(data); }
            if (i === 2) { _dataRem  = data; _renderReembolso(data); }
            if (i === 3) { _dataFrec = data; _renderFrecuencia(data); }
        })
        .catch(() => {
            if (loading) loading.style.display = 'none';
            console.error(`Error al cargar slide ${i}`);
        });
    }

    /* ──── helpers ──── */
    function _fmt(n, dec = 2) {
        return parseFloat(n || 0).toLocaleString('es-VE', { minimumFractionDigits: dec });
    }
    function _kpiCard(label, value, sub, color) {
        return `<div class="col">
            <div class="card border-0 text-center p-2" style="background:${color}; border-radius:.75rem;">
                <small class="text-muted" style="font-size:.69rem; text-transform:uppercase; letter-spacing:.3px;">${label}</small>
                <strong style="font-size:1.05rem; color:#1e293b;">${value}</strong>
                ${sub ? `<span style="font-size:.72rem; color:#6b7280;">${sub}</span>` : ''}
            </div>
        </div>`;
    }
    function _kpiSmall(label, value, color) {
        return `<div class="col-auto">
            <div class="p-2 text-center" style="background:${color}; border-radius:.6rem; min-width:90px;">
                <small class="text-muted d-block" style="font-size:.66rem; text-transform:uppercase;">${label}</small>
                <strong style="font-size:.95rem; color:#1e293b;">${value}</strong>
            </div>
        </div>`;
    }
    function _destroyChart(id) {
        if (_charts[id]) { _charts[id].destroy(); delete _charts[id]; }
    }
    function _badge(val, s, w) {
        if (val <= s) return 'bg-success';
        if (val <= w) return 'bg-warning text-dark';
        return 'bg-danger';
    }


    /* ═══════════════════════════════════════════════════
       RENDER SLIDE 0 — TASA DE USO
       Nuevo: lista de trabajadores sin préstamo
    ══════════════════════════════════════════════════ */
    function _renderUso(d) {
        const pct   = d.promedio;
        const badge = _badge(pct, 40, 60);
        const label = pct <= 40 ? 'BAJO' : pct <= 60 ? 'MODERADO' : 'ALTO';

        document.getElementById('uso-kpis').innerHTML =
            _kpiCard('Tasa de uso', pct + '%', `${d.con_prestamo} de ${d.total_empleados} empleados`, '#f0f9ff') +
            _kpiCard('Con préstamo activo', d.con_prestamo, 'empleados', '#f0fdf4') +
            _kpiCard('Sin préstamo', d.sin_prestamo, 'empleados', '#fff7ed') +
            `<div class="col">
                <div class="card border-0 text-center p-2" style="background:#fdf4ff; border-radius:.75rem;">
                    <small class="text-muted" style="font-size:.69rem; text-transform:uppercase; letter-spacing:.3px;">Estado</small>
                    <span class="badge ${badge} mt-1" style="font-size:.8rem;">${label}</span>
                </div>
            </div>`;

        // Barras de departamento
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
                d.top5_deuda.map(p => `
                <tr>
                    <td style="font-weight:600;">${p.nombre} ${p.apellido}</td>
                    <td class="text-end text-danger fw-bold">${_fmt(p.monto_desc)}</td>
                </tr>`).join('') +
                `</tbody></table>`;
        }

        // Donut
        _destroyChart('uso-chart');
        const ctx = document.getElementById('uso-chart');
        if (ctx) {
            _charts['uso-chart'] = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Con préstamo','Sin préstamo'],
                    datasets: [{ data: [d.con_prestamo, d.sin_prestamo],
                        backgroundColor: ['#3b82f6','#e2e8f0'], borderWidth: 0 }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { font: { size: 10 } } } } }
            });
        }

        // ★ Lista sin préstamo
        const sinList = document.getElementById('uso-sin-prestamo-list');
        if (sinList && d.sin_prestamo_lista) {
            if (d.sin_prestamo_lista.length === 0) {
                sinList.innerHTML = `<p class="text-muted text-center my-2" style="font-size:.8rem;">Todos los empleados tienen préstamo activo.</p>`;
            } else {
                sinList.innerHTML = `<table class="table table-sm mb-0" style="font-size:.78rem;">
                    <thead><tr><th>Nombre</th><th>Departamento</th><th>Cargo</th></tr></thead>
                    <tbody>` +
                    d.sin_prestamo_lista.map(e => `
                    <tr>
                        <td>${e.nombre} ${e.apellido}</td>
                        <td class="text-muted">${e.departamento ?? '—'}</td>
                        <td class="text-muted">${e.cargo ?? '—'}</td>
                    </tr>`).join('') +
                    `</tbody></table>`;
            }
        }
    }


    /* ═══════════════════════════════════════════════════
       RENDER SLIDE 1 — PROMEDIOS
       Nuevo: tabla por trabajador con filtro
    ══════════════════════════════════════════════════ */
    function _renderPromedio(d) {
        document.getElementById('prom-kpis').innerHTML =
            _kpiCard('Promedio mensual actual', _fmt(d.actual_mensual) + ' $', 'este mes', '#f0f9ff') +
            _kpiCard('Promedio semanal actual', _fmt(d.actual_semanal) + ' $', 'esta semana', '#f0fdf4') +
            _kpiCard('Prom. histórico mensual', _fmt(d.promedio) + ' $', 'todos los períodos', '#fff7ed') +
            _kpiCard('Máximo mensual', _fmt(d.max) + ' $', 'pico histórico', '#fdf4ff');

        // Línea histórico
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
                          tension: .4, fill: true, pointRadius: 3 },
                        { label: 'Promedio Semanal $', data: d.historial.map(h => h.semanal),
                          borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,.07)',
                          tension: .4, fill: false, borderDash: [5,3], pointRadius: 2 }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top', labels: { font: { size: 10 } } } },
                    scales:  { y: { ticks: { callback: v => '$' + _fmt(v, 0) } } }
                }
            });
        }

        // Tabla con filtro activo
        _renderPromTabla(d, _promFiltroActivo);
    }

    function _renderPromTabla(d, filtro) {
        const tbody = document.getElementById('prom-tabla');
        if (!tbody || !d.por_trabajador) return;

        let lista = d.por_trabajador;
        if (filtro === 'pagados')    lista = lista.filter(p => p.pendiente <= 0);
        if (filtro === 'pendientes') lista = lista.filter(p => p.pendiente > 0);

        if (lista.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-3">Sin registros para este filtro.</td></tr>`;
            return;
        }

        tbody.innerHTML = lista.map(p => {
            const estBadge = p.pendiente <= 0 ? 'bg-success' : 'bg-warning text-dark';
            const estLabel = p.pendiente <= 0 ? 'Pagado' : 'Pendiente';
            return `<tr>
                <td style="white-space:nowrap;">${p.nombre} ${p.apellido}</td>
                <td class="text-center fw-bold">${p.cantidad}</td>
                <td class="text-end">${_fmt(p.monto_total)}</td>
                <td class="text-end text-success fw-bold">${_fmt(p.pagado_total)}</td>
                <td class="text-end text-warning fw-bold">${_fmt(p.pendiente)}</td>
                <td class="text-center"><span class="badge ${estBadge}" style="font-size:.67rem;">${estLabel}</span></td>
            </tr>`;
        }).join('');
    }


    /* ═══════════════════════════════════════════════════
       RENDER SLIDE 2 — TASA DE REEMBOLSO
       Nuevo: podio / ranking de trabajadores con más préstamos
    ══════════════════════════════════════════════════ */
    function _renderReembolso(d) {
        const stateBadge = d.global > 50 ? 'bg-success' : d.global >= 31 ? 'bg-warning text-dark' : 'bg-danger';
        const stateLabel = d.global > 50 ? 'SALUDABLE' : d.global >= 31 ? 'ATENCIÓN' : 'CRÍTICO';

        document.getElementById('rem-kpis').innerHTML =
            _kpiCard('Tasa global de reembolso', d.global + '%', 'del monto total prestado', '#f0f9ff') +
            _kpiCard('Préstamos vencidos', d.vencidos_cnt, `${_fmt(d.vencidos_monto)} $ pendiente`, '#fff1f2') +
            _kpiCard('Total reembolsado', _fmt(d.por_anio?.[0]?.total_reembolsado ?? 0) + ' $', 'año más reciente', '#f0fdf4') +
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
                    labels: ['Reembolsado','Pendiente'],
                    datasets: [{ data: [d.global, 100 - d.global],
                        backgroundColor: ['#22c55e','#fca5a5'], borderWidth: 0 }]
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

        // Barras por año
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

        // ★ Ranking trabajadores con más préstamos
        const topDiv = document.getElementById('rem-top-trabajadores');
        if (topDiv && d.top_trabajadores) {
            const medallas = ['🥇','🥈','🥉'];
            const colores  = ['#fef9c3','#f1f5f9','#fff7ed'];
            topDiv.innerHTML = `<div class="row g-2">` +
                d.top_trabajadores.slice(0, 5).map((t, i) => `
                <div class="col-auto">
                    <div class="p-2 text-center"
                         style="background:${colores[i] ?? '#f8fafc'}; border-radius:.7rem;
                                min-width:120px; border:1px solid #e2e8f0;">
                        <div style="font-size:1.2rem;">${medallas[i] ?? (i+1) + 'º'}</div>
                        <strong style="font-size:.78rem; display:block; color:#1e293b;">
                            ${t.nombre} ${t.apellido}
                        </strong>
                        <span style="font-size:.7rem; color:#6b7280;">${t.cantidad} préstamo(s)</span><br>
                        <span style="font-size:.7rem; color:#ef4444; font-weight:600;">
                            $${_fmt(t.monto_total)} total
                        </span>
                    </div>
                </div>`).join('') +
                `</div>`;
        }

        // Tabla detalle reembolso
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
                        <span class="badge ${p.vencido ? 'bg-danger' : 'bg-success'}"
                              style="font-size:.68rem;">
                            ${p.vencido ? 'Vencido' : 'Al día'}
                        </span>
                    </td>
                </tr>`).join('');
        }
    }


    /* ═══════════════════════════════════════════════════
       RENDER SLIDE 3 — FRECUENCIA DE RENOVACIÓN
       Nuevo: resumen mes + tabla detallada del mes
    ══════════════════════════════════════════════════ */
    function _renderFrecuencia(d) {
        const fPct      = d.frecuency;
        const stateBadge = _badge(fPct, 40, 60);
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

        // Mes actual por defecto
        const selEl = document.getElementById('frec-mes-sel');
        const mesDefault = selEl ? selEl.value : '';
        _renderFrecMes(d, mesDefault);

        // Gráfico tendencia mensual
        _destroyChart('frec-chart');
        const ctx = document.getElementById('frec-chart');
        if (ctx && d.tendencia_mensual) {
            _charts['frec-chart'] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: d.tendencia_mensual.map(t => t.mes),
                    datasets: [{
                        label: 'Préstamos otorgados',
                        data:  d.tendencia_mensual.map(t => t.cantidad),
                        backgroundColor: d.tendencia_mensual.map((_, i) =>
                            i === d.tendencia_mensual.length - 1
                                ? 'rgba(99,102,241,1)' : 'rgba(99,102,241,.5)'),
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

        // Multi-préstamo
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

    /* ★ Tabla del mes seleccionado en slide 3 */
    function _renderFrecMes(d, mes) {
        const kpisEl = document.getElementById('frec-mes-kpis');
        const tablaEl = document.getElementById('frec-mes-tabla');
        if (!kpisEl || !tablaEl || !d.detalle_mes) return;

        // Filtrar por mes seleccionado (YYYY-MM)
        const lista = mes
            ? d.detalle_mes.filter(p => p.fecha && p.fecha.substring(0, 7) === mes)
            : d.detalle_mes;

        // KPIs resumen
        const total       = lista.length;
        const montoTotal  = lista.reduce((s, p) => s + parseFloat(p.monto || 0), 0);
        const trabajadores = [...new Set(lista.map(p => p.cedula))].length;

        kpisEl.innerHTML =
            _kpiSmall('Préstamos', total, '#f0f9ff') +
            _kpiSmall('Monto total', '$' + _fmt(montoTotal, 0), '#f0fdf4') +
            _kpiSmall('Trabajadores', trabajadores, '#fff7ed');

        if (lista.length === 0) {
            tablaEl.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-2">Sin préstamos en este mes.</td></tr>`;
            return;
        }

        tablaEl.innerHTML = lista.map(p => `
            <tr>
                <td style="white-space:nowrap;">${p.nombre} ${p.apellido}</td>
                <td class="text-end fw-bold">$${_fmt(p.monto)}</td>
                <td class="text-end">$${_fmt(p.descuento)}</td>
                <td class="text-center">${p.cuotas}</td>
                <td style="max-width:120px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"
                    title="${p.concepto}">${p.concepto}</td>
                <td class="text-center">${p.fecha}</td>
            </tr>`).join('');
    }


    /* ──── inicializar al abrir modal ──── */
    document.addEventListener('shown.bs.modal', function (e) {
        if (e.target.id === 'PrestamosModal') {
            if (!_loaded[_slide]) _loadSlide(_slide);
        }
    });

})();
</script>