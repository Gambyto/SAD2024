<?php
/**
 * Prestamos-renovacion.php  — PDF Frecuencia de Renovación
 * Parámetro: ?mes=YYYY-MM  (default: mes actual)
 *
 * Secciones:
 *   1. KPIs: tasa renovación, totales, empleados multi-préstamo, riesgo
 *   2. Préstamos otorgados en el mes seleccionado (resumen + detalle)
 *   3. Tendencia mensual — últimos 12 meses
 *   4. Trabajadores con múltiples préstamos activos
 *
 * Ubicación: View/PlantillaPDF/Prestamos-renovacion.php
 */
session_start();
if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }

include '../../PHP/CLASS/conexion_Original.php';
include '../../PHP/CLASS/user_Original.php';

$db = $Nomina->connect_db();

/* ── Parámetro mes ──────────────────────────────────── */
$mesParam = $_GET['mes'] ?? date('Y-m');
if (!preg_match('/^\d{4}-\d{2}$/', $mesParam)) $mesParam = date('Y-m');
[$anioSel, $mesSel] = explode('-', $mesParam);

$mesesNom = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio',
             'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
$mesNombre = $mesesNom[(int)$mesSel] . ' ' . $anioSel;
$mesEsc    = $db->real_escape_string($mesParam);

/* ── Total préstamos activos ────────────────────────── */
$rTotal = $db->query("SELECT COUNT(*) AS total FROM prestamos WHERE estado = 1");
$totalActivos = (int)$rTotal->fetch_assoc()['total'];
$frecuency    = round(($totalActivos * 0.033) * 100, 2);

/* ── Estado de riesgo ───────────────────────────────── */
$riesgoLabel = $frecuency < 41 ? 'ÓPTIMO'   : ($frecuency <= 60 ? 'MODERADO' : 'ALTO');
$riesgoBg    = $frecuency < 41 ? '#dcfce7'  : ($frecuency <= 60 ? '#fef9c3'  : '#fee2e2');
$riesgoColor = $frecuency < 41 ? '#166534'  : ($frecuency <= 60 ? '#854d0e'  : '#991b1b');

/* ── Préstamos del mes seleccionado ─────────────────── */
$rMes = $db->query("
    SELECT p.id_prestamos, e.nombre, e.apellido, e.cedula,
           e.departamento, e.cargo,
           p.monto, p.descuento, p.monto_desc, p.cuotas,
           p.concepto, p.fecha, p.date_limit
    FROM prestamos p
    INNER JOIN empleados e ON p.cedula_FK = e.cedula
    WHERE p.estado = 1
      AND DATE_FORMAT(p.fecha,'%Y-%m') = '$mesEsc'
    ORDER BY p.fecha DESC
");
$prestamosMes  = [];
$montoTotalMes = 0;
while ($row = $rMes->fetch_assoc()) {
    $prestamosMes[] = $row;
    $montoTotalMes += (float)$row['monto'];
}
$cantMes = count($prestamosMes);
$promMes = $cantMes > 0 ? round($montoTotalMes / $cantMes, 2) : 0;

/* ── Tendencia mensual (últimos 12 meses) ───────────── */
$meses_n = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
$todos   = $Nomina->Prestamos_View_report();

$porMes = [];
foreach ($todos as $p) {
    $key = date('Y-m', strtotime($p['fecha']));
    if (!isset($porMes[$key])) $porMes[$key] = ['cantidad' => 0, 'monto' => 0];
    $porMes[$key]['cantidad']++;
    $porMes[$key]['monto'] += (float)$p['monto'];
}
ksort($porMes);
$tendencia = [];
foreach ($porMes as $mes => $v) {
    [$y, $m] = explode('-', $mes);
    $tendencia[] = [
        'mes'      => $meses_n[(int)$m] . ' ' . $y,
        'clave'    => $mes,
        'cantidad' => $v['cantidad'],
        'monto'    => $v['monto'],
    ];
}
$tendencia = array_reverse(array_slice(array_reverse($tendencia), 0, 12));

/* ── Empleados con múltiples préstamos ──────────────── */
$cedulaCount  = array_count_values(array_column($todos, 'cedula'));
$multiCedulas = array_keys(array_filter($cedulaCount, fn($c) => $c > 1));
$empleadosMulti = [];
$Empleado_todos = $Empleado->View();
$empIdx = [];
foreach ($Empleado_todos as $e) $empIdx[$e['cedula']] = $e;

foreach ($multiCedulas as $ced) {
    $prests = array_filter($todos, fn($p) => $p['cedula'] === $ced);
    $prests = array_values($prests);
    $deuda  = array_sum(array_column($prests, 'monto_desc'));
    $empleadosMulti[] = [
        'cedula'       => $ced,
        'nombre'       => $prests[0]['nombre'] ?? '',
        'apellido'     => $prests[0]['apellido'] ?? '',
        'departamento' => $empIdx[$ced]['departamento'] ?? '—',
        'cargo'        => $empIdx[$ced]['cargo'] ?? '—',
        'cantidad'     => $cedulaCount[$ced],
        'deuda_total'  => $deuda,
    ];
}
usort($empleadosMulti, fn($a, $b) => $b['cantidad'] <=> $a['cantidad']);

$totalRenovaciones = array_sum($cedulaCount) - count($cedulaCount);
$totalMulti        = count($empleadosMulti);

/* ── Helper ─────────────────────────────────────────── */
function fmtN4($n, $d = 2) { return number_format((float)$n, $d, ',', '.'); }
function fmtF4($f)          { return $f ? date('d/m/Y', strtotime($f)) : '—'; }

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte — Frecuencia de Renovación</title>
<style>
*    { box-sizing:border-box; margin:0; padding:0; }
body { font-family:Arial,sans-serif; font-size:10px; color:#1a1a1a; }

.hdr        { border-bottom:3px solid #0f2027; padding-bottom:7px; margin-bottom:12px; }
.hdr table  { width:100%; border-collapse:collapse; }
.hdr td     { border:none; vertical-align:middle; }
.logo       { width:60px; height:60px; }
.co-name    { font-size:13px; font-weight:bold; color:#0f2027; }
.co-sub     { font-size:10px; color:#4b5563; margin-top:2px; }
.hdr-right  { text-align:right; font-size:9px; color:#6b7280; }

.stitle     { font-size:10.5px; font-weight:bold; color:#0f2027;
              border-left:4px solid #3b82f6; padding-left:6px; margin:11px 0 6px 0; }

.kpi-wrap   { width:100%; border-collapse:separate; border-spacing:4px; }
.kpi-cell   { background:#f8fafc; border:1px solid #e2e8f0; border-radius:5px;
              padding:8px 5px; text-align:center; vertical-align:middle; }
.kpi-lbl    { font-size:8px; color:#6b7280; display:block; text-transform:uppercase;
              letter-spacing:.3px; margin-bottom:2px; }
.kpi-val    { font-size:14px; font-weight:bold; color:#0f2027; }
.kpi-sub    { font-size:8px; color:#9ca3af; display:block; margin-top:1px; }

.dt         { width:100%; border-collapse:collapse; font-size:9px; }
.dt thead tr{ background:#0f2027; color:#fff; }
.dt th      { padding:4px; font-size:8.5px; text-align:left; }
.dt th.r    { text-align:right; }
.dt th.c    { text-align:center; }
.dt td      { padding:4px; border-bottom:1px solid #e5e7eb; color:#374151; }
.dt td.r    { text-align:right; }
.dt td.c    { text-align:center; }
.dt tbody tr:nth-child(even) td { background:#f9fafb; }
.dt tfoot td{ background:#f1f5f9; font-weight:bold;
              border-top:2px solid #0f2027; padding:4px; }

.bdg        { display:inline-block; border-radius:3px; padding:2px 5px;
              font-size:8px; font-weight:bold; }

.nota       { background:#f0f9ff; border-left:4px solid #6366f1;
              padding:8px 10px; border-radius:4px; margin-top:10px; }
.nota p     { font-size:9px; color:#374151; line-height:1.6; }

.footer     { margin-top:16px; border-top:1px solid #e5e7eb; padding-top:6px;
              font-size:8.5px; color:#9ca3af; text-align:right; }
</style>
</head>
<body>

<!-- ENCABEZADO -->
<div class="hdr">
    <table>
        <tr>
            <td style="width:68px;">
                <img src="http://<?= $_SERVER['HTTP_HOST'] ?>/PIUT_V1/IMG/Logo_Comple_Black.png" class="logo">
            </td>
            <td>
                <span class="co-name">DISORIENT, C.A.</span>
                <div class="co-sub">
                    Reporte de Frecuencia de Renovación de Préstamos &mdash;
                    Mes: <strong><?= $mesNombre ?></strong>
                </div>
            </td>
            <td class="hdr-right">
                Emisión: <strong><?= date('d/m/Y H:i') ?></strong><br>
                Período: <strong><?= $mesNombre ?></strong>
            </td>
        </tr>
    </table>
</div>

<!-- KPIs -->
<div class="stitle">Indicadores de Renovación</div>
<table class="kpi-wrap">
    <tr>
        <td class="kpi-cell">
            <span class="kpi-lbl">Tasa de Renovación</span>
            <span class="kpi-val" style="color:<?= $riesgoColor ?>;"><?= $frecuency ?>%</span>
            <span class="kpi-sub">índice calculado</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Préstamos Activos</span>
            <span class="kpi-val"><?= $totalActivos ?></span>
            <span class="kpi-sub">estado = 1</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Otorgados — <?= $mesesNom[(int)$mesSel] ?></span>
            <span class="kpi-val"><?= $cantMes ?></span>
            <span class="kpi-sub"><?= fmtN4($montoTotalMes) ?> $ en el mes</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Empleados con múltiples</span>
            <span class="kpi-val"><?= $totalMulti ?></span>
            <span class="kpi-sub">han pedido &gt;1 préstamo</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Renovaciones</span>
            <span class="kpi-val"><?= $totalRenovaciones ?></span>
            <span class="kpi-sub">préstamos adicionales</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Riesgo</span>
            <span class="kpi-val">
                <span class="bdg" style="background:<?= $riesgoBg ?>; color:<?= $riesgoColor ?>;">
                    <?= $riesgoLabel ?>
                </span>
            </span>
        </td>
    </tr>
</table>

<!-- PRÉSTAMOS DEL MES — RESUMEN -->
<div class="stitle" style="border-left-color:#10b981;">
    Préstamos Otorgados — <?= $mesNombre ?>
</div>
<table class="kpi-wrap" style="margin-bottom:6px;">
    <tr>
        <td class="kpi-cell">
            <span class="kpi-lbl">Cantidad</span>
            <span class="kpi-val"><?= $cantMes ?></span>
            <span class="kpi-sub">préstamos en el mes</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Monto Total</span>
            <span class="kpi-val"><?= fmtN4($montoTotalMes) ?> $</span>
            <span class="kpi-sub">suma del mes</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Promedio por Préstamo</span>
            <span class="kpi-val"><?= fmtN4($promMes) ?> $</span>
            <span class="kpi-sub">este mes</span>
        </td>
    </tr>
</table>

<!-- PRÉSTAMOS DEL MES — DETALLE -->
<?php if (!empty($prestamosMes)): ?>
<table class="dt">
    <thead>
        <tr>
            <th>Empleado</th>
            <th>Cédula</th>
            <th>Departamento</th>
            <th>Cargo</th>
            <th class="r">Monto $</th>
            <th class="r">Cuota Sem. $</th>
            <th class="r">Pendiente $</th>
            <th class="c">Cuotas</th>
            <th>Concepto</th>
            <th class="c">Fecha</th>
            <th class="c">Vence</th>
        </tr>
    </thead>
    <tbody>
    <?php $totMes = 0; foreach ($prestamosMes as $p): $totMes += (float)$p['monto']; ?>
        <tr>
            <td style="font-weight:600;"><?= htmlspecialchars($p['nombre'].' '.$p['apellido']) ?></td>
            <td><?= $p['cedula'] ?></td>
            <td><?= htmlspecialchars($p['departamento'] ?? '—') ?></td>
            <td><?= htmlspecialchars($p['cargo'] ?? '—') ?></td>
            <td class="r" style="font-weight:bold;"><?= fmtN4($p['monto']) ?></td>
            <td class="r"><?= fmtN4($p['descuento']) ?></td>
            <td class="r" style="color:<?= (float)$p['monto_desc']>0?'#dc2626':'#166534' ?>;">
                <?= fmtN4($p['monto_desc']) ?>
            </td>
            <td class="c"><?= $p['cuotas'] ?></td>
            <td><?= htmlspecialchars($p['concepto'] ?? '—') ?></td>
            <td class="c"><?= fmtF4($p['fecha']) ?></td>
            <td class="c"><?= fmtF4($p['date_limit']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" style="text-align:right; padding-right:5px;">TOTAL MES</td>
            <td class="r"><?= fmtN4($totMes) ?></td>
            <td colspan="6"></td>
        </tr>
    </tfoot>
</table>
<?php else: ?>
<p style="color:#9ca3af; text-align:center; padding:12px; font-style:italic; font-size:9px;">
    No se registraron préstamos en <?= $mesNombre ?>.
</p>
<?php endif; ?>

<!-- TENDENCIA MENSUAL -->
<div class="stitle" style="margin-top:12px; border-left-color:#6366f1;">
    Tendencia Mensual — Últimos 12 Meses
</div>
<table class="dt">
    <thead>
        <tr>
            <th>Mes</th>
            <th class="c">Cantidad</th>
            <th class="r">Monto Total $</th>
            <th class="r">Promedio $</th>
            <th class="c">Participación</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $maxCant  = max(array_column($tendencia, 'cantidad') ?: [1]);
    $totTend  = array_sum(array_column($tendencia, 'cantidad'));
    foreach ($tendencia as $t):
        $pctTend = $totTend > 0 ? round(($t['cantidad'] / $totTend) * 100, 1) : 0;
        $prom    = $t['cantidad'] > 0 ? round($t['monto'] / $t['cantidad'], 2) : 0;
        $esMes   = $t['clave'] === $mesParam;
    ?>
        <tr <?= $esMes ? 'style="background:#f0fdf4;"' : '' ?>>
            <td style="font-weight:<?= $esMes?'bold':'normal' ?>;">
                <?= $t['mes'] ?><?= $esMes ? ' ◀' : '' ?>
            </td>
            <td class="c" style="font-weight:bold;"><?= $t['cantidad'] ?></td>
            <td class="r"><?= fmtN4($t['monto']) ?></td>
            <td class="r"><?= fmtN4($prom) ?></td>
            <td class="c" style="color:#6366f1; font-weight:bold;"><?= $pctTend ?>%</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td>TOTAL 12 MESES</td>
            <td class="c"><?= $totTend ?></td>
            <td class="r"><?= fmtN4(array_sum(array_column($tendencia,'monto'))) ?></td>
            <td class="r">
                <?= $totTend > 0 ? fmtN4(array_sum(array_column($tendencia,'monto')) / $totTend) : '—' ?>
            </td>
            <td class="c">100%</td>
        </tr>
    </tfoot>
</table>

<!-- EMPLEADOS CON MÚLTIPLES PRÉSTAMOS -->
<div class="stitle" style="margin-top:12px; border-left-color:#f59e0b;">
    Trabajadores con Múltiples Préstamos Activos
    <span class="bdg" style="background:#fef9c3; color:#854d0e; margin-left:5px;">
        <?= $totalMulti ?> trabajadores
    </span>
</div>
<?php if (!empty($empleadosMulti)): ?>
<table class="dt">
    <thead>
        <tr>
            <th class="c">#</th>
            <th>Empleado</th>
            <th>Cédula</th>
            <th>Departamento</th>
            <th>Cargo</th>
            <th class="c">Cant. Prést.</th>
            <th class="r">Deuda Pendiente $</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($empleadosMulti as $i => $e):
        $pos   = $i + 1;
        $rowBg = $i === 0 ? 'background:#fffbeb;' : '';
    ?>
        <tr style="<?= $rowBg ?>">
            <td class="c" style="font-weight:bold;">
                <?= $pos===1?'1°':($pos===2?'2°':($pos===3?'3°':$pos.'.')) ?>
            </td>
            <td style="font-weight:<?= $i===0?'bold':'600' ?>;">
                <?= htmlspecialchars($e['nombre'].' '.$e['apellido']) ?>
            </td>
            <td><?= $e['cedula'] ?></td>
            <td><?= htmlspecialchars($e['departamento']) ?></td>
            <td><?= htmlspecialchars($e['cargo']) ?></td>
            <td class="c">
                <span class="bdg" style="background:#dbeafe; color:#1e40af;">
                    <?= $e['cantidad'] ?>
                </span>
            </td>
            <td class="r" style="color:#dc2626; font-weight:bold;">
                <?= fmtN4($e['deuda_total']) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align:right; padding-right:5px;">TOTAL DEUDA MULTI-PRÉSTAMO</td>
            <td class="c"><?= array_sum(array_column($empleadosMulti,'cantidad')) ?></td>
            <td class="r" style="color:#dc2626;">
                <?= fmtN4(array_sum(array_column($empleadosMulti,'deuda_total'))) ?>
            </td>
        </tr>
    </tfoot>
</table>
<?php else: ?>
<p style="color:#16a34a; text-align:center; padding:12px; font-style:italic; font-size:9px;">
    Ningún empleado tiene más de un préstamo activo actualmente.
</p>
<?php endif; ?>

<!-- NOTA METODOLÓGICA -->
<div class="nota">
    <p>
        <strong>Cálculo de Frecuencia:</strong>
        Frecuencia = (total préstamos activos × 0.033) × 100.
        &nbsp;&nbsp;
        Menor a 40% → Óptimo &nbsp;|&nbsp; 40%–60% → Moderado &nbsp;|&nbsp; Mayor a 60% → Alto riesgo de endeudamiento.
    </p>
</div>

<!-- FOOTER -->
<div class="footer">
    Generado el <?= date('d/m/Y') ?> a las <?= date('H:i') ?>
    &nbsp;|&nbsp; DISORIENT, C.A. &nbsp;|&nbsp; Sistema de Nómina
</div>

</body>
</html>
<?php
$html = ob_get_clean();

require_once '../../PHP/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$dompdf  = new Dompdf();
$options = $dompdf->getOptions();
$options->set(['isRemoteEnabled' => true]);
$dompdf->setOptions($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('Prestamos-renovacion-' . $mesParam . '.pdf', ['Attachment' => false]);
