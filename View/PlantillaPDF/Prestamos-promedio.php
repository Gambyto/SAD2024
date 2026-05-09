<?php
/**
 * Prestamos-promedio.php — Reporte PDF: Promedio de Préstamos
 * Ubicación: View/PlantillaPDF/Prestamos-promedio.php
 */
session_start();
if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }

include '../../PHP/CLASS/conexion_Original.php';
include '../../PHP/CLASS/user_Original.php';

$historial = $Nomina->View_Promedio_Prestamos();
$activos   = $Nomina->Prestamos_View_Modal();

$montos_men = array_column($historial, 'promedio_mensual');
$max_men    = !empty($montos_men) ? max($montos_men) : 0;
$min_men    = !empty($montos_men) ? min(array_filter($montos_men)) : 0;
$prom_hist  = !empty($montos_men) ? array_sum($montos_men) / count($montos_men) : 0;

$actual_mes = isset($historial[0]) ? (float)$historial[0]['promedio_mensual'] : 0;
$actual_sem = isset($historial[0]) ? (float)$historial[0]['promedio_semana']  : 0;

$meses_n = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte Promedio de Préstamos</title>
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
    border-left:4px solid #22c55e; padding-left:8px; margin:14px 0 8px 0; }
.kpi-table { width:100%; border-collapse:separate; border-spacing:6px; }
.kpi-cell { width:25%; background:#f8fafc; border:1px solid #e2e8f0;
    border-radius:6px; padding:10px 8px; text-align:center; }
.kpi-label { font-size:9px; color:#6b7280; display:block; margin-bottom:4px;
    text-transform:uppercase; letter-spacing:.3px; }
.kpi-value { font-size:14px; font-weight:bold; color:#1a1a2e; }
.kpi-sub { font-size:9px; color:#9ca3af; display:block; margin-top:2px; }
.data-table { width:100%; border-collapse:collapse; font-size:9.5px; margin-top:4px; }
.data-table thead tr { background:#22c55e; color:#fff; }
.data-table th, .data-table td { padding:6px 8px; border-bottom:1px solid #e5e7eb; }
.data-table tbody tr:nth-child(even) td { background:#f9fafb; }
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
                <div class="doc-title">Reporte de Promedio de Montos en Préstamos</div>
            </td>
            <td class="header-right">Fecha de emisión:<br><strong><?= date('d-m-Y') ?></strong></td>
        </tr>
    </table>
</div>

<div class="section-title">Resumen ejecutivo</div>
<table class="kpi-table">
    <tr>
        <td class="kpi-cell">
            <span class="kpi-label">Promedio mensual actual</span>
            <span class="kpi-value">$ <?= number_format($actual_mes, 2) ?></span>
            <span class="kpi-sub">este mes</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-label">Promedio semanal actual</span>
            <span class="kpi-value">$ <?= number_format($actual_sem, 2) ?></span>
            <span class="kpi-sub">esta semana</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-label">Máximo histórico mensual</span>
            <span class="kpi-value">$ <?= number_format($max_men, 2) ?></span>
            <span class="kpi-sub">pico registrado</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-label">Promedio histórico</span>
            <span class="kpi-value">$ <?= number_format($prom_hist, 2) ?></span>
            <span class="kpi-sub">todos los períodos</span>
        </td>
    </tr>
</table>

<div class="section-title" style="margin-top:18px;">Histórico de promedios por período</div>
<table class="data-table">
    <thead>
        <tr>
            <th>Período</th>
            <th class="text-center">Mes</th>
            <th class="text-center">Año</th>
            <th class="text-end">Promedio Mensual $</th>
            <th class="text-end">Promedio Semanal $</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($historial as $fila): ?>
    <tr>
        <td style="font-weight:600;"><?= $meses_n[(int)$fila['mes']] . ' ' . $fila['año'] ?></td>
        <td style="text-align:center;"><?= $fila['mes'] ?></td>
        <td style="text-align:center;"><?= $fila['año'] ?></td>
        <td style="text-align:right;"><?= number_format($fila['promedio_mensual'], 2) ?></td>
        <td style="text-align:right;"><?= number_format($fila['promedio_semana'], 2) ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="section-title" style="margin-top:18px;">Préstamos activos</div>
<table class="data-table">
    <thead>
        <tr>
            <th>Empleado</th>
            <th>Cédula</th>
            <th class="text-end">Monto Orig. $</th>
            <th class="text-end">Saldo Pend. $</th>
            <th class="text-end">Cuota Sem. $</th>
            <th class="text-center">Cuotas</th>
            <th>Fecha Límite</th>
            <th>Concepto</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach (array_slice($activos, 0, 25) as $p): ?>
    <tr>
        <td style="font-weight:600; white-space:nowrap;"><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido']) ?></td>
        <td><?= $p['cedula'] ?></td>
        <td style="text-align:right;"><?= number_format($p['monto'], 2) ?></td>
        <td style="text-align:right; color:#dc2626; font-weight:bold;"><?= number_format($p['monto_desc'], 2) ?></td>
        <td style="text-align:right;"><?= number_format($p['descuento'], 2) ?></td>
        <td style="text-align:center;"><?= $p['cuotas'] ?></td>
        <td><?= $p['date_limit'] ? date('d/m/Y', strtotime($p['date_limit'])) : '—' ?></td>
        <td style="color:#6b7280; font-size:8.5px;"><?= htmlspecialchars($p['concepto'] ?? '') ?></td>
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
$dompdf->stream('Prestamos-promedio-' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
