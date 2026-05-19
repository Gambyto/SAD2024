<?php
/**
 * Prestamos-reembolso.php  — PDF Tasa de Reembolso
 *
 * Secciones:
 *   1. KPIs: tasa global, vencidos, años con datos, estado
 *   2. Balance por año (prestado vs reembolsado vs pendiente)
 *   3. Ranking — trabajadores con más préstamos adquiridos
 *   4. Detalle de reembolso por empleado (progreso individual)
 *
 * Ubicación: View/PlantillaPDF/Prestamos-reembolso.php
 */
session_start();
if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }

include '../../PHP/CLASS/conexion_Original.php';
include '../../PHP/CLASS/user_Original.php';

$db = $Nomina->connect_db();

/* ── Balance anual ──────────────────────────────────── */
$rBal = $db->query("
    SELECT
        YEAR(fecha)                     AS anio,
        COUNT(*)                        AS cantidad,
        SUM(monto)                      AS total_prestado,
        SUM(monto - monto_desc)         AS total_reembolsado,
        SUM(monto_desc)                 AS total_pendiente
    FROM prestamos
    WHERE estado = 1
    GROUP BY YEAR(fecha)
    ORDER BY anio DESC
");
$porAnio  = [];
$balance0 = null;
while ($row = $rBal->fetch_assoc()) {
    if ($balance0 === null) $balance0 = $row;
    $pct = $row['total_prestado'] > 0
        ? round(($row['total_reembolsado'] / $row['total_prestado']) * 100, 2) : 0;
    $porAnio[] = [
        'anio'              => $row['anio'],
        'cantidad'          => (int)$row['cantidad'],
        'total_prestado'    => (float)$row['total_prestado'],
        'total_reembolsado' => (float)$row['total_reembolsado'],
        'total_pendiente'   => (float)$row['total_pendiente'],
        'porcentaje'        => $pct,
    ];
}

$global = ($balance0 && $balance0['total_prestado'] > 0)
    ? round(($balance0['total_reembolsado'] / $balance0['total_prestado']) * 100, 2)
    : 0;

/* ── Totales globales ───────────────────────────────── */
$grandPrestado    = array_sum(array_column($porAnio, 'total_prestado'));
$grandReembolsado = array_sum(array_column($porAnio, 'total_reembolsado'));
$grandPendiente   = array_sum(array_column($porAnio, 'total_pendiente'));
$grandCantidad    = array_sum(array_column($porAnio, 'cantidad'));
$globalTotal      = $grandPrestado > 0
    ? round(($grandReembolsado / $grandPrestado) * 100, 2) : 0;

/* ── Vencidos ───────────────────────────────────────── */
$vencidos = $Nomina->Prestamos_Vencidos();
$vencCnt  = (int)($vencidos['cantidad']     ?? 0);
$vencMont = (float)($vencidos['monto_total'] ?? 0);

/* ── Estado general ─────────────────────────────────── */
$estadoLabel = $global > 50 ? 'SALUDABLE' : ($global >= 31 ? 'ATENCIÓN' : 'CRÍTICO');
$estadoBg    = $global > 50 ? '#dcfce7'   : ($global >= 31 ? '#fef9c3'  : '#fee2e2');
$estadoColor = $global > 50 ? '#166534'   : ($global >= 31 ? '#854d0e'  : '#991b1b');

/* ── Ranking top trabajadores ───────────────────────── */
$rTop = $db->query("
    SELECT
        e.cedula, e.nombre, e.apellido, e.departamento,
        COUNT(p.id_prestamos)   AS cantidad,
        SUM(p.monto)            AS monto_total,
        SUM(p.monto_desc)       AS deuda_pendiente,
        SUM(p.monto-p.monto_desc) AS pagado_total
    FROM prestamos p
    INNER JOIN empleados e ON p.cedula_FK = e.cedula
    WHERE p.estado = 1
    GROUP BY e.cedula, e.nombre, e.apellido, e.departamento
    ORDER BY cantidad DESC, monto_total DESC
    LIMIT 10
");
$topTrabajadores = [];
while ($row = $rTop->fetch_assoc()) $topTrabajadores[] = $row;

/* ── Detalle por empleado ───────────────────────────── */
$todos   = $Nomina->Prestamos_View_report();
$detalle = [];
foreach ($todos as $p) {
    $progreso = $p['monto'] > 0
        ? round((1 - $p['monto_desc'] / $p['monto']) * 100, 1) : 100;
    $detalle[] = [
        'nombre'    => $p['nombre'] . ' ' . $p['apellido'],
        'cedula'    => $p['cedula'],
        'monto'     => (float)$p['monto'],
        'pendiente' => (float)$p['monto_desc'],
        'pagado'    => round((float)$p['monto'] - (float)$p['monto_desc'], 2),
        'progreso'  => $progreso,
        'vencido'   => ($p['date_limit'] && $p['date_limit'] < date('Y-m-d') && $p['monto_desc'] > 0) ? 1 : 0,
    ];
}

/* ── Helper ─────────────────────────────────────────── */
function fmtN3($n, $d = 2) { return number_format((float)$n, $d, ',', '.'); }

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte — Tasa de Reembolso</title>
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
                <div class="co-sub">Reporte de Tasa de Reembolso de Préstamos</div>
            </td>
            <td class="hdr-right">
                Emisión: <strong><?= date('d/m/Y H:i') ?></strong><br>
                Año: <strong><?= date('Y') ?></strong>
            </td>
        </tr>
    </table>
</div>

<!-- KPIs -->
<div class="stitle">Indicadores Generales de Reembolso</div>
<table class="kpi-wrap">
    <tr>
        <td class="kpi-cell">
            <span class="kpi-lbl">Tasa Global de Reembolso</span>
            <span class="kpi-val" style="color:<?= $estadoColor ?>;"><?= $globalTotal ?>%</span>
            <span class="kpi-sub">del total prestado</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Total Prestado</span>
            <span class="kpi-val"><?= fmtN3($grandPrestado) ?> $</span>
            <span class="kpi-sub">acumulado</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Total Reembolsado</span>
            <span class="kpi-val" style="color:#166534;"><?= fmtN3($grandReembolsado) ?> $</span>
            <span class="kpi-sub">cobrado</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Total Pendiente</span>
            <span class="kpi-val" style="color:#dc2626;"><?= fmtN3($grandPendiente) ?> $</span>
            <span class="kpi-sub">por cobrar</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Préstamos Vencidos</span>
            <span class="kpi-val" style="color:#dc2626;"><?= $vencCnt ?></span>
            <span class="kpi-sub"><?= fmtN3($vencMont) ?> $ pendiente</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Estado</span>
            <span class="kpi-val">
                <span class="bdg" style="background:<?= $estadoBg ?>; color:<?= $estadoColor ?>;">
                    <?= $estadoLabel ?>
                </span>
            </span>
        </td>
    </tr>
</table>

<!-- BALANCE POR AÑO -->
<div class="stitle" style="border-left-color:#6366f1;">Balance por Año</div>
<table class="dt">
    <thead>
        <tr>
            <th>Año</th>
            <th class="c">Cantidad</th>
            <th class="r">Total Prestado $</th>
            <th class="r">Reembolsado $</th>
            <th class="r">Pendiente $</th>
            <th class="c">% Reembolso</th>
            <th class="c">Estado</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($porAnio as $a):
        $aLbl = $a['porcentaje'] > 50 ? 'BIEN'     : ($a['porcentaje'] >= 31 ? 'REGULAR' : 'BAJO');
        $aBg  = $a['porcentaje'] > 50 ? '#dcfce7'  : ($a['porcentaje'] >= 31 ? '#fef9c3' : '#fee2e2');
        $aClr = $a['porcentaje'] > 50 ? '#166534'  : ($a['porcentaje'] >= 31 ? '#854d0e' : '#991b1b');
    ?>
        <tr>
            <td style="font-weight:bold;"><?= $a['anio'] ?></td>
            <td class="c"><?= $a['cantidad'] ?></td>
            <td class="r"><?= fmtN3($a['total_prestado']) ?></td>
            <td class="r" style="color:#166534;"><?= fmtN3($a['total_reembolsado']) ?></td>
            <td class="r" style="color:#dc2626; font-weight:bold;"><?= fmtN3($a['total_pendiente']) ?></td>
            <td class="c" style="font-weight:bold; color:<?= $aClr ?>;"><?= $a['porcentaje'] ?>%</td>
            <td class="c">
                <span class="bdg" style="background:<?= $aBg ?>; color:<?= $aClr ?>;"><?= $aLbl ?></span>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td>TOTAL GLOBAL</td>
            <td class="c"><?= $grandCantidad ?></td>
            <td class="r"><?= fmtN3($grandPrestado) ?></td>
            <td class="r" style="color:#166534;"><?= fmtN3($grandReembolsado) ?></td>
            <td class="r" style="color:#dc2626;"><?= fmtN3($grandPendiente) ?></td>
            <td class="c" style="color:<?= $estadoColor ?>;"><?= $globalTotal ?>%</td>
            <td class="c">
                <span class="bdg" style="background:<?= $estadoBg ?>; color:<?= $estadoColor ?>;">
                    <?= $estadoLabel ?>
                </span>
            </td>
        </tr>
    </tfoot>
</table>

<!-- RANKING TRABAJADORES CON MÁS PRÉSTAMOS -->
<div class="stitle" style="margin-top:12px; border-left-color:#f59e0b;">
    Trabajadores con Más Préstamos Adquiridos — Top 10
</div>
<table class="dt">
    <thead>
        <tr>
            <th class="c">#</th>
            <th>Empleado</th>
            <th>Cédula</th>
            <th>Departamento</th>
            <th class="c">Préstamos</th>
            <th class="r">Monto Total $</th>
            <th class="r">Pagado $</th>
            <th class="r">Deuda Pend. $</th>
            <th class="c">% Pagado</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($topTrabajadores as $i => $t):
        $pctPag = $t['monto_total'] > 0
            ? round(($t['pagado_total'] / $t['monto_total']) * 100, 1) : 0;
        $pctClr = $pctPag >= 70 ? '#166534' : ($pctPag >= 40 ? '#854d0e' : '#991b1b');
        $pos    = $i + 1;
        $rowBg  = $i === 0 ? 'background:#fffbeb;' : '';
    ?>
        <tr style="<?= $rowBg ?>">
            <td class="c" style="font-weight:bold;">
                <?= $pos === 1 ? '1°' : ($pos === 2 ? '2°' : ($pos === 3 ? '3°' : $pos.'.')) ?>
            </td>
            <td style="font-weight:<?= $i===0?'bold':'600' ?>;">
                <?= htmlspecialchars($t['nombre'].' '.$t['apellido']) ?>
            </td>
            <td><?= $t['cedula'] ?></td>
            <td><?= htmlspecialchars($t['departamento'] ?? '—') ?></td>
            <td class="c">
                <span class="bdg" style="background:#dbeafe; color:#1e40af;">
                    <?= $t['cantidad'] ?>
                </span>
            </td>
            <td class="r"><?= fmtN3($t['monto_total']) ?></td>
            <td class="r" style="color:#166534;"><?= fmtN3($t['pagado_total']) ?></td>
            <td class="r" style="color:#dc2626; font-weight:bold;"><?= fmtN3($t['deuda_pendiente']) ?></td>
            <td class="c" style="font-weight:bold; color:<?= $pctClr ?>;"><?= $pctPag ?>%</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<!-- DETALLE POR EMPLEADO -->
<div class="stitle" style="margin-top:12px; border-left-color:#10b981;">
    Detalle de Reembolso por Empleado
</div>
<table class="dt">
    <thead>
        <tr>
            <th>Empleado</th>
            <th>Cédula</th>
            <th class="r">Monto Orig. $</th>
            <th class="r">Pagado $</th>
            <th class="r">Pendiente $</th>
            <th class="c">% Progreso</th>
            <th class="c">Estado</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $dTotMonto = $dTotPagado = $dTotPend = 0;
    foreach ($detalle as $p):
        $dTotMonto  += $p['monto'];
        $dTotPagado += $p['pagado'];
        $dTotPend   += $p['pendiente'];
        $prgClr = $p['progreso'] >= 70 ? '#166534' : ($p['progreso'] >= 40 ? '#854d0e' : '#991b1b');
        $estLbl = $p['vencido'] ? 'Vencido' : ($p['pendiente'] == 0 ? 'Pagado' : 'Al día');
        $estBg  = $p['vencido'] ? '#fee2e2' : ($p['pendiente'] == 0 ? '#dcfce7' : '#dbeafe');
        $estClr = $p['vencido'] ? '#991b1b' : ($p['pendiente'] == 0 ? '#166534' : '#1e40af');
    ?>
        <tr>
            <td style="font-weight:600;"><?= htmlspecialchars($p['nombre']) ?></td>
            <td><?= $p['cedula'] ?></td>
            <td class="r"><?= fmtN3($p['monto']) ?></td>
            <td class="r" style="color:#166534;"><?= fmtN3($p['pagado']) ?></td>
            <td class="r" style="color:<?= $p['pendiente']>0?'#dc2626':'#374151' ?>;
                                   font-weight:<?= $p['pendiente']>0?'bold':'normal' ?>;">
                <?= fmtN3($p['pendiente']) ?>
            </td>
            <td class="c" style="font-weight:bold; color:<?= $prgClr ?>;"><?= $p['progreso'] ?>%</td>
            <td class="c">
                <span class="bdg" style="background:<?= $estBg ?>; color:<?= $estClr ?>;">
                    <?= $estLbl ?>
                </span>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style="text-align:right; padding-right:5px;">TOTAL</td>
            <td class="r"><?= fmtN3($dTotMonto) ?></td>
            <td class="r" style="color:#166534;"><?= fmtN3($dTotPagado) ?></td>
            <td class="r" style="color:#dc2626;"><?= fmtN3($dTotPend) ?></td>
            <td class="c" style="color:<?= $estadoColor ?>;">
                <?= $dTotMonto > 0 ? round(($dTotPagado/$dTotMonto)*100,1) : 0 ?>%
            </td>
            <td></td>
        </tr>
    </tfoot>
</table>

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
$dompdf->stream('Prestamos-reembolso-' . date('Y') . '.pdf', ['Attachment' => false]);
