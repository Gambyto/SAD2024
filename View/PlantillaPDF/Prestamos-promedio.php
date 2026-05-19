<?php
/**
 * Prestamos-promedio.php  — PDF Promedios de Préstamos
 * Parámetro: ?filtro=todos|pagados|pendientes  (default: todos)
 *
 * Secciones:
 *   1. KPIs: promedio mensual actual, semanal, histórico, máximo
 *   2. Historial mensual de promedios (tabla)
 *   3. Préstamos por trabajador (filtrado según ?filtro)
 *
 * Ubicación: View/PlantillaPDF/Prestamos-promedio.php
 */
session_start();
if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }

include '../../PHP/CLASS/conexion_Original.php';
include '../../PHP/CLASS/user_Original.php';

$db = $Nomina->connect_db();

/* ── Parámetro filtro ───────────────────────────────── */
$filtroRaw = $_GET['filtro'] ?? 'todos';
$filtro    = in_array($filtroRaw, ['pagados','pendientes','todos']) ? $filtroRaw : 'todos';
$filtroLabel = ['todos' => 'Todos', 'pagados' => 'Pagados', 'pendientes' => 'Pendientes'][$filtro];

/* ── Historial mensual ──────────────────────────────── */
$meses_n = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

$rHist = $db->query("
    SELECT
        YEAR(fecha)                 AS anio,
        MONTH(fecha)                AS mes,
        ROUND(AVG(monto), 2)        AS prom_mensual,
        ROUND(AVG(monto)/4.33, 2)   AS prom_semanal,
        COUNT(*)                    AS cantidad,
        SUM(monto)                  AS total
    FROM prestamos
    WHERE estado = 1
    GROUP BY YEAR(fecha), MONTH(fecha)
    ORDER BY anio DESC, mes DESC
");
$rawHist = [];
while ($row = $rHist->fetch_assoc()) $rawHist[] = $row;

$actual_mens = isset($rawHist[0]) ? (float)$rawHist[0]['prom_mensual'] : 0;
$actual_sem  = isset($rawHist[0]) ? (float)$rawHist[0]['prom_semanal'] : 0;
$montos      = array_column($rawHist, 'prom_mensual');
$prom_hist   = !empty($montos) ? round(array_sum($montos) / count($montos), 2) : 0;
$prom_max    = !empty($montos) ? max($montos) : 0;

/* ── Préstamos por trabajador ───────────────────────── */
$rTrab = $db->query("
    SELECT
        e.cedula, e.nombre, e.apellido, e.departamento, e.cargo,
        COUNT(p.id_prestamos)           AS cantidad,
        SUM(p.monto)                    AS monto_total,
        SUM(p.monto - p.monto_desc)     AS pagado_total,
        SUM(p.monto_desc)               AS pendiente
    FROM prestamos p
    INNER JOIN empleados e ON p.cedula_FK = e.cedula
    WHERE p.estado = 1
    GROUP BY e.cedula, e.nombre, e.apellido, e.departamento, e.cargo
    ORDER BY pendiente DESC
");
$porTrabajador = [];
while ($row = $rTrab->fetch_assoc()) {
    $pend = (float)$row['pendiente'];
    $tot  = (float)$row['monto_total'];

    if ($filtro === 'pagados'    && $pend > 0)  continue;
    if ($filtro === 'pendientes' && $pend === 0) continue;

    $porTrabajador[] = [
        'cedula'       => $row['cedula'],
        'nombre'       => $row['nombre'],
        'apellido'     => $row['apellido'],
        'departamento' => $row['departamento'],
        'cargo'        => $row['cargo'],
        'cantidad'     => (int)$row['cantidad'],
        'monto_total'  => $tot,
        'pagado_total' => (float)$row['pagado_total'],
        'pendiente'    => $pend,
    ];
}

/* ── Totales generales ──────────────────────────────── */
$totMonto    = array_sum(array_column($porTrabajador, 'monto_total'));
$totPagado   = array_sum(array_column($porTrabajador, 'pagado_total'));
$totPendiente= array_sum(array_column($porTrabajador, 'pendiente'));
$totCant     = array_sum(array_column($porTrabajador, 'cantidad'));

/* ── Helper ─────────────────────────────────────────── */
function fmtN2($n, $d = 2) { return number_format((float)$n, $d, ',', '.'); }

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte — Promedios de Préstamos</title>
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
.dt tfoot td{ background:#f1f5f9; font-weight:bold; border-top:2px solid #0f2027; padding:4px; }

.bdg        { display:inline-block; border-radius:3px; padding:2px 5px;
              font-size:8px; font-weight:bold; }

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
                    Reporte de Promedios de Préstamos &mdash;
                    Filtro: <strong><?= $filtroLabel ?></strong>
                </div>
            </td>
            <td class="hdr-right">
                Emisión: <strong><?= date('d/m/Y H:i') ?></strong><br>
                Período: <strong><?= date('Y') ?></strong>
            </td>
        </tr>
    </table>
</div>

<!-- KPIs -->
<div class="stitle">Indicadores de Promedio</div>
<table class="kpi-wrap">
    <tr>
        <td class="kpi-cell">
            <span class="kpi-lbl">Promedio mensual actual</span>
            <span class="kpi-val"><?= fmtN2($actual_mens) ?> $</span>
            <span class="kpi-sub">este mes</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Promedio semanal actual</span>
            <span class="kpi-val"><?= fmtN2($actual_sem) ?> $</span>
            <span class="kpi-sub">esta semana</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Prom. histórico mensual</span>
            <span class="kpi-val"><?= fmtN2($prom_hist) ?> $</span>
            <span class="kpi-sub">todos los períodos</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Máximo mensual</span>
            <span class="kpi-val"><?= fmtN2($prom_max) ?> $</span>
            <span class="kpi-sub">pico histórico</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Trabajadores en reporte</span>
            <span class="kpi-val"><?= count($porTrabajador) ?></span>
            <span class="kpi-sub"><?= $filtroLabel ?></span>
        </td>
    </tr>
</table>

<!-- HISTORIAL MENSUAL -->
<div class="stitle" style="border-left-color:#6366f1;">Historial Mensual de Promedios</div>
<table class="dt">
    <thead>
        <tr>
            <th>Período</th>
            <th class="c">Cantidad</th>
            <th class="r">Total Prestado $</th>
            <th class="r">Promedio Mensual $</th>
            <th class="r">Promedio Semanal $</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($rawHist as $fila): ?>
        <tr>
            <td><?= $meses_n[(int)$fila['mes']] . ' ' . $fila['anio'] ?></td>
            <td class="c"><?= $fila['cantidad'] ?></td>
            <td class="r"><?= fmtN2($fila['total']) ?></td>
            <td class="r" style="font-weight:bold;"><?= fmtN2($fila['prom_mensual']) ?></td>
            <td class="r"><?= fmtN2($fila['prom_semanal']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td>PROMEDIO GENERAL</td>
            <td class="c"><?= array_sum(array_column($rawHist,'cantidad')) ?></td>
            <td class="r"><?= fmtN2(array_sum(array_column($rawHist,'total'))) ?></td>
            <td class="r" style="color:#0f2027;"><?= fmtN2($prom_hist) ?></td>
            <td class="r"><?= fmtN2(round($prom_hist / 4.33, 2)) ?></td>
        </tr>
    </tfoot>
</table>

<!-- PRÉSTAMOS POR TRABAJADOR -->
<div class="stitle" style="margin-top:12px; border-left-color:#10b981;">
    Préstamos por Trabajador —
    <span class="bdg" style="background:<?= $filtro==='pagados'?'#dcfce7':($filtro==='pendientes'?'#fef9c3':'#dbeafe') ?>;
                              color:<?= $filtro==='pagados'?'#166534':($filtro==='pendientes'?'#854d0e':'#1e40af') ?>;">
        <?= $filtroLabel ?>
    </span>
</div>

<?php if (!empty($porTrabajador)): ?>
<table class="dt">
    <thead>
        <tr>
            <th>Empleado</th>
            <th>Cédula</th>
            <th>Departamento</th>
            <th>Cargo</th>
            <th class="c"># Prést.</th>
            <th class="r">Monto Orig. $</th>
            <th class="r">Pagado $</th>
            <th class="r">Pendiente $</th>
            <th class="c">Estado</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($porTrabajador as $t):
        $pagado = $t['pendiente'] == 0;
        $estadoTxt   = $pagado ? 'Pagado'    : 'Pendiente';
        $estadoColor = $pagado ? '#166534'   : '#854d0e';
        $estadoBg    = $pagado ? '#dcfce7'   : '#fef9c3';
    ?>
        <tr>
            <td style="font-weight:600;"><?= htmlspecialchars($t['nombre'].' '.$t['apellido']) ?></td>
            <td><?= $t['cedula'] ?></td>
            <td><?= htmlspecialchars($t['departamento'] ?? '—') ?></td>
            <td><?= htmlspecialchars($t['cargo'] ?? '—') ?></td>
            <td class="c"><?= $t['cantidad'] ?></td>
            <td class="r"><?= fmtN2($t['monto_total']) ?></td>
            <td class="r" style="color:#166534;"><?= fmtN2($t['pagado_total']) ?></td>
            <td class="r" style="color:<?= $t['pendiente']>0?'#dc2626':'#374151' ?>;
                                   font-weight:<?= $t['pendiente']>0?'bold':'normal' ?>;">
                <?= fmtN2($t['pendiente']) ?>
            </td>
            <td class="c">
                <span class="bdg" style="background:<?= $estadoBg ?>; color:<?= $estadoColor ?>;">
                    <?= $estadoTxt ?>
                </span>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" style="text-align:right; padding-right:5px;">TOTAL</td>
            <td class="c"><?= $totCant ?></td>
            <td class="r"><?= fmtN2($totMonto) ?></td>
            <td class="r" style="color:#166534;"><?= fmtN2($totPagado) ?></td>
            <td class="r" style="color:#dc2626;"><?= fmtN2($totPendiente) ?></td>
            <td></td>
        </tr>
    </tfoot>
</table>
<?php else: ?>
<p style="color:#9ca3af; text-align:center; padding:14px; font-style:italic; font-size:9px;">
    No hay registros para el filtro seleccionado: <strong><?= $filtroLabel ?></strong>.
</p>
<?php endif; ?>

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
$dompdf->stream('Prestamos-promedio-' . $filtro . '-' . date('Y') . '.pdf', ['Attachment' => false]);
