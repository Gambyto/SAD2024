<?php
/**
 * Indicadores_Prestamos.php
 * Los 4 indicadores de préstamos con soporte para modal unificado.
 * Reemplaza: Tasadeuso.php, PromedioPrestamos.php, Tasa_de_reembolso.php, Frecuencia_Renovación.php
 * 
 * Uso: <?php include 'Indicadores_Prestamos.php'; ?>
 * El modal se abre con: openPrestamosModal(0|1|2|3)
 */

$_db = $Nomina->connect_db();
$meses_arr = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

// ── 1. Tasa de uso ──────────────────────────────────────────────
// CORRECCIÓN: PromedioPrestamos() tenía WHERE 1 (sin filtrar estado).
// Query directa: empleados distintos con préstamo activo / total empleados activos.
$_r1         = $_db->query("SELECT COUNT(DISTINCT cedula_FK) AS cnt FROM prestamos WHERE estado = 1");
$_con_prest  = (int)$_r1->fetch_assoc()['cnt'];
$_total_emp  = count($Empleado->View());   // View() ya filtra estado='1'
$promedio_uso     = $_total_emp > 0 ? round(($_con_prest / $_total_emp) * 100, 2) : 0;
$promedio_uso_fmt = number_format($promedio_uso, 2);
if ($promedio_uso < 41)        $ind_uso = 'success';
elseif ($promedio_uso <= 60)   $ind_uso = 'warning';
else                           $ind_uso = 'danger';

// ── 2. Promedio de préstamos ────────────────────────────────────
// CORRECCIÓN: View_Promedio_Prestamos() usa una vista cuyo filtro de estado
// no está garantizado. Query directa agrupada por mes/año con estado=1.
$_r2 = $_db->query("
    SELECT MONTH(fecha) AS mes, YEAR(fecha) AS anio,
           ROUND(AVG(monto), 2)        AS promedio_mensual,
           ROUND(AVG(monto) / 4.33, 2) AS promedio_semana
    FROM prestamos WHERE estado = 1
    GROUP BY YEAR(fecha), MONTH(fecha)
    ORDER BY anio DESC, mes DESC LIMIT 1
");
$_dato_prom  = $_r2->fetch_assoc();
$mes_prom        = $_dato_prom ? $meses_arr[(int)$_dato_prom['mes'] - 1] : '—';
$mes_promedio    = $_dato_prom ? number_format($_dato_prom['promedio_mensual'], 2) : '0.00';
$semana_promedio = $_dato_prom ? number_format($_dato_prom['promedio_semana'],  2) : '0.00';

// ── 3. Tasa de reembolso ────────────────────────────────────────
// CORRECCIÓN: Balance_Prestamos() usa vista_balance_prestamos sin garantía de filtro.
// Query directa: pagado = monto - monto_desc, solo estado=1.
$_r3 = $_db->query("
    SELECT SUM(monto) AS total_prestado, SUM(monto - monto_desc) AS total_reembolsado
    FROM prestamos WHERE estado = 1
");
$_bal        = $_r3->fetch_assoc();
$balance     = ($_bal['total_prestado'] > 0)
    ? number_format(($_bal['total_reembolsado'] / $_bal['total_prestado']) * 100, 2)
    : '0.00';
$bal_float   = (float) $balance;
if ($bal_float > 50)           $ind_bal = 'success';
elseif ($bal_float >= 31)      $ind_bal = 'warning';
else                           $ind_bal = 'danger';

// ── 4. Frecuencia de renovación ─────────────────────────────────
// CORRECCIÓN: Total_Prestamos() usa vista_total_prestamos sin garantía de filtro.
// COUNT directo con estado=1.
$_r4        = $_db->query("SELECT COUNT(*) AS total FROM prestamos WHERE estado = 1");
$_cnt_prest = (int)$_r4->fetch_assoc()['total'];
$frecuency  = number_format(($_cnt_prest * 0.033) * 100, 2);
$frec_float = (float) $frecuency;
if ($frec_float < 41)          $ind_frec = 'success';
elseif ($frec_float <= 60)     $ind_frec = 'warning';
else                           $ind_frec = 'danger';
?>

<!-- ══════════════════════════════════════════════════════════════
     INDICADORES  (click → abre modal en el slide correcto)
══════════════════════════════════════════════════════════════ -->

<!-- TASA DE USO -->
<div class="kpi-s kf3">
    <div class="indicator__content <?php echo $ind_uso; ?>"
    style="cursor:pointer; min-width:17rem;"
    onclick="openPrestamosModal(0)" title="Ver detalle de Tasa de Uso">
        <div class="indicator__header">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" width="24" height="24"
            stroke-width="2" stroke-linejoin="round" stroke-linecap="round" stroke="currentColor">
            <path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12"/>
            <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4"/>
        </svg>
        
        <span class="ms-auto" style="opacity:.55; font-size:.7rem;">↗ Ver más</span>
        </div>
        <div class="indicator__body">
            <small class="text-body-secondary">Tasa de uso de préstamos</small>
            <h5 class="text-body-primary"><?php echo $promedio_uso_fmt . '% han usado préstamos'; ?></h5>
        </div>
    </div>
</div>

<!-- PROMEDIO DE PRÉSTAMOS -->
<div class="kpi-s kf4">
<div class="indicator__content"
     style="cursor:pointer; min-width:17rem;"
     onclick="openPrestamosModal(1)" title="Ver detalle de Promedio de Préstamos">
    <div class="indicator__header">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
             width="24" height="24" stroke-width="2">
            <path d="M17.1 8.648a.568 .568 0 0 1 -.761 .011a5.682 5.682 0 0 0 -3.659 -1.34c-1.102 0 -2.205 .363 -2.205 1.374c0 1.023 1.182 1.364 2.546 1.875c2.386 .796 4.363 1.796 4.363 4.137c0 2.545 -1.977 4.295 -5.204 4.488l-.295 1.364a.557 .557 0 0 1 -.546 .443h-2.034l-.102 -.011a.568 .568 0 0 1 -.432 -.67l.318 -1.444a7.432 7.432 0 0 1 -3.273 -1.784v-.011a.545 .545 0 0 1 0 -.773l1.137 -1.102c.214 -.2 .547 -.2 .761 0a5.495 5.495 0 0 0 3.852 1.5c1.478 0 2.466 -.625 2.466 -1.614c0 -.989 -1 -1.25 -2.886 -1.954c-2 -.716 -3.898 -1.728 -3.898 -4.091c0 -2.75 2.284 -4.091 4.989 -4.216l.284 -1.398a.545 .545 0 0 1 .545 -.432h2.023l.114 .012a.544 .544 0 0 1 .42 .647l-.307 1.557a8.528 8.528 0 0 1 2.818 1.58l.023 .022c.216 .228 .216 .569 0 .773l-1.057 1.057z"/>
        </svg>
        <span class="badge bg-secondary"><?php echo $mes_prom; ?></span>
        <span class="ms-auto" style="opacity:.55; font-size:.7rem;">↗ Ver más</span>
    </div>
    <div class="indicator__body">
        <small class="text-body-secondary">Promedio de monto en préstamos</small>
        <h5 class="text-body-primary"><?php echo $mes_promedio . '$ Mes / ' . $semana_promedio . '$ Sem'; ?></h5>
    </div>
</div>
</div>

<!-- TASA DE REEMBOLSO -->
<div class=" kpi-s kf5">
<div class="indicator__content <?php echo $ind_bal; ?>"
     style="cursor:pointer; min-width:17rem;"
     onclick="openPrestamosModal(2)" title="Ver detalle de Tasa de Reembolso">
    <div class="indicator__header">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
            <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2"/>
            <path d="M14 8h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5m2 0v1.5m0 -9v1.5"/>
        </svg>
        
        <span class="ms-auto" style="opacity:.55; font-size:.7rem;">↗ Ver más</span>
    </div>
    <div class="indicator__body">
        <small class="text-body-secondary">Tasa de reembolso de préstamos</small>
        <h5 class="text-body-primary"><?php echo $balance . '% han sido pagados'; ?></h5>
    </div>
</div>
</div>

<!-- FRECUENCIA DE RENOVACIÓN -->
<div class="kpi-s kf6">
<div class="indicator__content <?php echo $ind_frec; ?>"
     style="cursor:pointer; min-width:17rem;"
     onclick="openPrestamosModal(3)" title="Ver detalle de Frecuencia de Renovación">
    <div class="indicator__header">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
            <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2"/>
            <path d="M14 8h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5m2 0v1.5m0 -9v1.5"/>
        </svg>
        <span class="ms-auto" style="opacity:.55; font-size:.7rem;">↗ Ver más</span>
    </div>
    <div class="indicator__body">
        <small class="text-body-secondary">Tasa de renovación de préstamos</small>
        <h5 class="text-body-primary"><?php echo $frecuency . '%'; ?></h5>
    </div>
</div>
</div>