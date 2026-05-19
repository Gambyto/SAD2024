<?php
$data = [];
// ── Datos para el gráfico principal (todos los años) ──────────────────────────
$labels = array_reverse(array_column($data, 'anio'));
$values = array_reverse(array_column($data, 'monto'));

// ── Estadísticas generales ─────────────────────────────────────────────────────
$totalAcumulado  = array_sum(array_column($data, 'monto'));
$promedioAnual   = count($data) > 0 ? $totalAcumulado / count($data) : 0;
$maxRow          = !empty($data) ? array_reduce($data, fn($c, $r) => (!$c || $r['monto'] > $c['monto']) ? $r : $c) : null;
$anioMaximo      = $maxRow['anio']  ?? '—';
$montoMaximo     = $maxRow['monto'] ?? 0;

// ── Detalle por año (función nueva: Vacation_Detail_By_Year) ──────────────────
// Siempre usamos el año actual como default del filtro
$anioFiltro      = (int)date('Y');
$detalleVacaciones = method_exists($Nomina, 'Vacation_Detail_By_Year')
    ? $Nomina->Vacation_Detail_By_Year($anioFiltro)
    : [];

// Total del año del filtro activo (puede ser 0 si no hay datos)
$totalAnioFiltro = array_sum(array_column($detalleVacaciones, 'monto'));

// ── Años disponibles para el filtro ───────────────────────────────────────────
// Incluir el año actual aunque no tenga datos, para que siempre aparezca en el select
$aniosDisponibles = array_column($data, 'anio');
if (!in_array($anioFiltro, $aniosDisponibles)) {
    array_unshift($aniosDisponibles, $anioFiltro);
}
// Ordenar descendente para que el más reciente quede primero
rsort($aniosDisponibles);
?>

<!-- ══════════════════════════════════════════════════════════════════════════
     MODAL VACACIONES
═══════════════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="VacationModal" tabindex="-1" aria-labelledby="VacationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <!-- ── Header ── -->
            <div class="modal-header" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: #fff;">
                <div class="d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                         width="22" height="22" stroke-width="2">
                        <path d="M17.553 16.75a7.5 7.5 0 0 0 -10.606 0"/>
                        <path d="M18 3.804a6 6 0 0 0 -8.196 2.196l10.392 6a6 6 0 0 0 -2.196 -8.196z"/>
                        <path d="M15 9l-3 5.196"/>
                        <path d="M3 19.25a2.4 2.4 0 0 1 1-.25a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2-1a2.4 2.4 0 0 1 2-1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2-1a2.4 2.4 0 0 1 2-1a2.4 2.4 0 0 1 1 .25"/>
                    </svg>
                    <h5 class="modal-title mb-0" id="VacationModalLabel">Fluctuación de Pagos por Vacaciones</h5>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a id="vacPdfBtn"
                       href="PlantillaPDF/Vacaciones-reporte.php?anio=<?= date('Y') ?>"
                       target="_blank"
                       class="btn btn-sm btn-outline-light"
                       title="Exportar PDF">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="12" y1="18" x2="12" y2="12"/>
                            <line x1="9" y1="15" x2="15" y2="15"/>
                        </svg>
                        PDF
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
            </div>

            <!-- ── Body ── -->
            <div class="modal-body p-4" id="vacationModalBody">

                <!-- KPI Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100" style="background:#f0fdf4;">
                            <div class="card-body text-center">
                                <small class="text-muted d-block mb-1">Total del Año <span id="vacKpiAnioLabel"><?= $anioFiltro ?></span></small>
                                <h4 class="fw-bold text-success mb-0">
                                    $ <span id="vacKpiTotal"><?= number_format($totalAnioFiltro, 2) ?></span>
                                </h4>
                                <small class="text-muted"><span id="vacKpiEmpleados"><?= count($detalleVacaciones) ?></span> empleado(s)</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100" style="background:#eff6ff;">
                            <div class="card-body text-center">
                                <small class="text-muted d-block mb-1">Promedio Anual</small>
                                <h4 class="fw-bold text-primary mb-0">
                                    $ <?= number_format($promedioAnual, 2) ?>
                                </h4>
                                <small class="text-muted">por año</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100" style="background:#fff7ed;">
                            <div class="card-body text-center">
                                <small class="text-muted d-block mb-1">Año con Mayor Pago</small>
                                <h4 class="fw-bold text-warning mb-0"><?= $anioMaximo ?></h4>
                                <small class="text-muted">$ <?= number_format($montoMaximo, 2) ?></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico principal -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pb-0">
                        <h6 class="fw-semibold mb-0">Evolución histórica de pagos por vacaciones</h6>
                    </div>
                    <div class="card-body">
                        <div style="position:relative; height:280px;">
                            <canvas id="vacationPayChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Filtro + Tabla detalle -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h6 class="fw-semibold mb-0">Detalle por empleado</h6>
                        <div class="d-flex align-items-center gap-2">
                            <select id="vacFiltroAnio" class="form-select form-select-sm" style="width:auto;"
                                    onchange="filtrarVacaciones(this.value)">
                                <?php foreach ($aniosDisponibles as $a): ?>
                                    <option value="<?= $a ?>" <?= $a == $anioFiltro ? 'selected' : '' ?>>
                                        <?= $a ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span id="vacLoadingBadge" class="badge bg-secondary d-none">Cargando…</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0" id="vacDetailTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Cédula</th>
                                        <th>Inicio vacaciones</th>
                                        <th>Fin vacaciones</th>
                                        <th>Días hábiles</th>
                                        <th class="text-end">Monto ($)</th>
                                    </tr>
                                </thead>
                                <tbody id="vacDetailBody">
                                    <?php if (empty($detalleVacaciones)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3">
                                                Sin datos para <?= $anioFiltro ?>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($detalleVacaciones as $row): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?></td>
                                                <td><?= htmlspecialchars($row['cedula']) ?></td>
                                                <td><?= htmlspecialchars($row['ini_vacaciones']) ?></td>
                                                <td><?= htmlspecialchars($row['fin_vacaciones']) ?></td>
                                                <td><?= htmlspecialchars($row['dias_habiles']) ?></td>
                                                <td class="text-end">$ <?= number_format($row['monto'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Totales de la tabla filtrada -->
                    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <span id="vacTotalEmpleados"><?= count($detalleVacaciones) ?></span> empleado(s)
                        </small>
                        <strong class="text-success">
                            Total: $ <span id="vacTotalMonto">
                                <?= number_format(array_sum(array_column($detalleVacaciones, 'monto')), 2) ?>
                            </span>
                        </strong>
                    </div>
                </div>

            </div><!-- /modal-body -->

            <div class="modal-footer border-0">
                <small class="text-muted me-auto">Datos actualizados al <?= date('d/m/Y') ?></small>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>

<!-- ── Scripts ── -->
<script>
// ── Gráfico principal: se inicializa cuando el modal está visible ──────────────
(function () {
    let _vacChartInstance = null;

    const labels = <?= json_encode($labels) ?>;
    const values = <?= json_encode(array_map('floatval', $values)) ?>;

    function renderVacChart() {
        const canvas = document.getElementById('vacationPayChart');
        if (!canvas) return;

        if (_vacChartInstance) { _vacChartInstance.destroy(); _vacChartInstance = null; }

        const ctx = canvas.getContext('2d');

        const movingAvg = values.map((_, i) => {
            const win = values.slice(Math.max(0, i - 1), i + 2);
            return win.reduce((a, b) => a + b, 0) / win.length;
        });

        _vacChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        type: 'bar',
                        label: 'Monto pagado ($)',
                        data: values,
                        backgroundColor: 'rgba(59,130,246,0.3)',
                        borderColor: 'rgba(59,130,246,0.9)',
                        borderWidth: 1,
                        borderRadius: 4,
                        order: 2
                    },
                    {
                        type: 'line',
                        label: 'Media móvil (±1 año)',
                        data: movingAvg,
                        borderColor: 'rgba(234,88,12,1)',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 4],
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(234,88,12,1)',
                        tension: 0.4,
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.dataset.label}: $ ${Number(ctx.parsed.y).toLocaleString('es-ES', { minimumFractionDigits: 2 })}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => '$ ' + Number(v).toLocaleString('es-ES', { minimumFractionDigits: 0 })
                        }
                    }
                }
            }
        });
    }

    // Esperar a que el modal esté completamente visible antes de renderizar
    const modalEl = document.getElementById('VacationModal');
    if (modalEl) {
        modalEl.addEventListener('shown.bs.modal', renderVacChart);
    }
})();

// ── Actualizar enlace PDF cuando cambia el filtro de año ──────────────────────
function filtrarVacaciones(anio) {
    const pdfBtn  = document.getElementById('vacPdfBtn');
    const badge   = document.getElementById('vacLoadingBadge');
    const tbody   = document.getElementById('vacDetailBody');

    if (!badge || !tbody) return;   // Guardia: elementos no disponibles aún

    if (pdfBtn) {
        pdfBtn.href = `PlantillaPDF/Vacaciones-reporte.php?anio=${anio}`;
    }

    badge.classList.remove('d-none');

    fetch(`../PHP/CTR/Vacation_Detail_CTR.php?anio=${anio}`)
        .then(r => r.json())
        .then(rows => {
            badge.classList.add('d-none');
            if (!rows || rows.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-3">Sin datos para ${anio}</td></tr>`;
                document.getElementById('vacTotalEmpleados').textContent = '0';
                document.getElementById('vacTotalMonto').textContent = '0.00';
                // Actualizar KPI card superior a 0
                const kpiTotal = document.getElementById('vacKpiTotal');
                const kpiAnio  = document.getElementById('vacKpiAnioLabel');
                const kpiEmps  = document.getElementById('vacKpiEmpleados');
                if (kpiTotal) kpiTotal.textContent = '0.00';
                if (kpiAnio)  kpiAnio.textContent  = anio;
                if (kpiEmps)  kpiEmps.textContent  = '0';
                return;
            }

            let html = '';
            let total = 0;
            rows.forEach(r => {
                const monto = parseFloat(r.monto) || 0;
                total += monto;
                html += `<tr>
                    <td>${r.nombre} ${r.apellido}</td>
                    <td>${r.cedula}</td>
                    <td>${r.ini_vacaciones}</td>
                    <td>${r.fin_vacaciones}</td>
                    <td>${r.dias_habiles}</td>
                    <td class="text-end">$ ${monto.toLocaleString('es-ES', { minimumFractionDigits: 2 })}</td>
                </tr>`;
            });

            tbody.innerHTML = html;
            document.getElementById('vacTotalEmpleados').textContent = rows.length;
            document.getElementById('vacTotalMonto').textContent =
                total.toLocaleString('es-ES', { minimumFractionDigits: 2 });
            // Actualizar KPI card superior
            const kpiTotal = document.getElementById('vacKpiTotal');
            const kpiAnio  = document.getElementById('vacKpiAnioLabel');
            const kpiEmps  = document.getElementById('vacKpiEmpleados');
            if (kpiTotal) kpiTotal.textContent = total.toLocaleString('es-ES', { minimumFractionDigits: 2 });
            if (kpiAnio)  kpiAnio.textContent  = anio;
            if (kpiEmps)  kpiEmps.textContent  = rows.length;
        })
        .catch(() => {
            badge.classList.add('d-none');
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger py-3">Error al cargar los datos</td></tr>`;
        });
}
</script>