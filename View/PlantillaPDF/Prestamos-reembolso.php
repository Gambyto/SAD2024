<?php
/**
 * Prestamos-reembolso.php — Reporte PDF: Tasa de Reembolso
 * Ubicación: View/PlantillaPDF/Prestamos-reembolso.php
 */
session_start();
if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }

include '../../PHP/CLASS/conexion_Original.php';
include '../../PHP/CLASS/user_Original.php';

$balance  = $Nomina->Balance_Prestamos();
$vencidos = $Nomina->Prestamos_Vencidos();
$todos    = $Nomina->Prestamos_View_report();

$global = (isset($balance[0]) && $balance[0]['total_prestado'] > 0)
    ? round(($balance[0]['total_reembolsado'] / $balance[0]['total_prestado']) * 100, 2) : 0;

$estado = $global > 50 ? ['SALUDABLE','#16a34a'] : ($global >= 31 ? ['ATENCIÓN','#d97706'] : ['CRÍTICO','#dc2626']);

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte Tasa de Reembolso</title>
<style>
* { box-sizing:border-box; margin:0; padding:0; }
body { font-family:Arial, sans-serif; font-size:11px; color:#1a1a1a; }
.header-wrap { border-bottom:3px solid #0f2027; padding-bottom:8px; margin-bottom:16px; }
.header-table { width:100%; border-collapse:collapse; }
.header-table td { border:none; vertical-align:middle; }
.logo { width:65px; height:65px; }
.company-name { font-size:14px; font-weight:bold; color:#0f2027; }
.doc-title { font-size:11px; color:#4b5563; margin-top:3px; }
.header-right { text-align:right; font-size:9px; color:#6b7280; }
.section-title { font-size:12px; font-weight:bold; color:#0f2027;
    border-left:4px solid #ef4444; padding-left:8px; margin:14px 0 8px 0; }
.kpi-table { width:100%; border-collapse:separate; border-spacing:6px; }
.kpi-cell { width:25%; background:#f8fafc; border:1px solid #e2e8f0;
    border-radius:6px; padding:10px 8px; text-align:center; }
.kpi-label { font-size:9px; color:#6b7280; display:block; margin-bottom:4px;
    text-transform:uppercase; letter-spacing:.3px; }
.kpi-value { font-size:14px; font-weight:bold; color:#1a1a2e; }
.kpi-sub { font-size:9px; color:#9ca3af; display:block; margin-top:2px; }
.anio-table { width:100%; border-collapse:collapse; font-size:9.5px; margin-top:4px; }
.anio-table thead tr { background:#ef4444; color:#fff; }
.anio-table th, .anio-table td { padding:6px 8px; border-bottom:1px solid #e5e7eb; }
.det-table { width:100%; border-collapse:collapse; font-size:9px; margin-top:4px; }
.det-table thead tr { background:#1a1a2e; color:#fff; }
.det-table th, .det-table td { padding:5px 6px; border-bottom:1px solid #e5e7eb; }
.det-table tbody tr:nth-child(even) td { background:#f9fafb; }
.bar-bg { background:#e5e7eb; border-radius:3px; height:7px; display:inline-block; width:80px; vertical-align:middle; }
.bar-fill { height:7px; border-radius:3px; }
.footer { margin-top:20px; border-top:1px solid #e5e7eb; padding-top:8px;
    font-size:9px; color:#9ca3af; text-align:right; }
</style>
</head>
<body>
<div class="header-wrap">
    <table class="header-table">
        <tr>
            <td style="width:75px;">
                <img src="http://<?= $_SERVER['HTTP_HOST'] ?>/PIUT_V1/IMG/Logo_Comple_Black.png" class="logo">
            </td>
            <td>
                <span class="company-name">DISORIENT, C.A.</span>
                <div class="doc-title">Reporte de Tasa de Reembolso de Préstamos</div>
            </td>
            <td class="header-right">Fecha de emisión:<br><strong><?= date('d-m-Y') ?></strong></td>
        </tr>
    </table>
</div>

<div class="section-title">Resumen ejecutivo</div>
<table class="kpi-table">
    <tr>
        <td class="kpi-cell">
            <span class="kpi-label">Tasa global de reembolso</span>
            <span class="kpi-value"><?= $global ?>%</span>
            <span class="kpi-sub" style="color:<?= $estado[1] ?>; font-weight:bold;"><?= $estado[0] ?></span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-label">Total prestado (año actual)</span>
            <span class="kpi-value">$ <?= isset($balance[0]) ? number_format($balance[0]['total_prestado'], 2) : '—' ?></span>
            <span class="kpi-sub"><?= isset($balance[0]) ? $balance[0]['anio'] : '' ?></span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-label">Total reembolsado</span>
            <span class="kpi-value" style="color:#16a34a;">$ <?= isset($balance[0]) ? number_format($balance[0]['total_reembolsado'], 2) : '—' ?></span>
            <span class="kpi-sub">este año</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-label">Préstamos vencidos</span>
            <span class="kpi-value" style="color:#dc2626;"><?= $vencidos['cantidad'] ?? 0 ?></span>
            <span class="kpi-sub">$ <?= number_format($vencidos['monto_total'] ?? 0, 2) ?> pendiente</span>
        </td>
    </tr>
</table>

<div class="section-title" style="margin-top:18px;">Balance histórico por año</div>
<table class="anio-table">
    <thead>
        <tr>
            <th>Año</th>
            <th class="text-end">Total Prestado $</th>
            <th class="text-end">Total Reembolsado $</th>
            <th class="text-end">Pendiente $</th>
            <th class="text-center">Tasa Reembolso</th>
            <th>Progreso</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($balance as $b): 
        $pct = $b['total_prestado'] > 0 ? round(($b['total_reembolsado'] / $b['total_prestado']) * 100, 1) : 0;
        $col = $pct > 50 ? '#16a34a' : ($pct >= 31 ? '#d97706' : '#dc2626');
        $pend = $b['total_prestado'] - $b['total_reembolsado'];
    ?>
    <tr>
        <td style="font-weight:600;"><?= $b['anio'] ?></td>
        <td style="text-align:right;"><?= number_format($b['total_prestado'], 2) ?></td>
        <td style="text-align:right; color:#16a34a; font-weight:bold;"><?= number_format($b['total_reembolsado'], 2) ?></td>
        <td style="text-align:right; color:#dc2626;"><?= number_format($pend, 2) ?></td>
        <td style="text-align:center; font-weight:bold; color:<?= $col ?>;"><?= $pct ?>%</td>
        <td><div class="bar-bg"><div class="bar-fill" style="width:<?= $pct ?>%; background:<?= $col ?>;"></div></div></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="section-title" style="margin-top:18px;">Detalle por empleado — Préstamos activos</div>
<table class="det-table">
    <thead>
        <tr>
            <th>Empleado</th>
            <th>Cédula</th>
            <th class="text-end">Monto Orig. $</th>
            <th class="text-end">Pagado $</th>
            <th class="text-end">Pendiente $</th>
            <th class="text-center">Progreso</th>
            <th class="text-center">Estado</th>
            <th>Vence</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($todos as $p):
        $prog  = $p['monto'] > 0 ? round((1 - $p['monto_desc'] / $p['monto']) * 100, 1) : 100;
        $pagado = round($p['monto'] - $p['monto_desc'], 2);
        $venc  = ($p['date_limit'] < date('Y-m-d') && $p['monto_desc'] > 0);
        $colP  = $prog >= 70 ? '#16a34a' : ($prog >= 40 ? '#d97706' : '#dc2626');
    ?>
    <tr>
        <td style="font-weight:600; white-space:nowrap;"><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido']) ?></td>
        <td><?= $p['cedula'] ?></td>
        <td style="text-align:right;"><?= number_format($p['monto'], 2) ?></td>
        <td style="text-align:right; color:#16a34a;"><?= number_format($pagado, 2) ?></td>
        <td style="text-align:right; color:#dc2626; font-weight:bold;"><?= number_format($p['monto_desc'], 2) ?></td>
        <td style="text-align:center;">
            <div class="bar-bg"><div class="bar-fill" style="width:<?= $prog ?>%; background:<?= $colP ?>;"></div></div>
            <span style="font-size:8px; color:#6b7280;"> <?= $prog ?>%</span>
        </td>
        <td style="text-align:center; font-weight:bold; color:<?= $venc?'#dc2626':'#16a34a' ?>;">
            <?= $venc ? 'VENCIDO' : 'AL DÍA' ?>
        </td>
        <td style="font-size:8.5px;"><?= $p['date_limit'] ? date('d/m/Y', strtotime($p['date_limit'])) : '—' ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="footer">
    Generado el <?= date('d/m/Y') ?> a las <?= date('H:i') ?> &nbsp;|&nbsp; DISORIENT, C.A. &nbsp;|&nbsp; Sistema de Nómina
</div>
</body>
</html>
<?php
$html = ob_get_clean();
require_once '../../PHP/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$opts   = $dompdf->getOptions();
$opts->set(['isRemoteEnabled' => true]);
$dompdf->setOptions($opts);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('Prestamos-reembolso-' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
