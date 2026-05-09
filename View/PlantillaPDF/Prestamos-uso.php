<?php
/**
 * Prestamos-uso.php — Reporte PDF: Tasa de Uso de Préstamos
 * Ubicación: View/PlantillaPDF/Prestamos-uso.php
 */
session_start();
if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }

include '../../PHP/CLASS/conexion_Original.php';
include '../../PHP/CLASS/user_Original.php';

$totalEmp    = count($Empleado->View());
$promedio    = $Empleado->PromedioPrestamos();
$activos     = $Nomina->Prestamos_View_report();
$conPrestamo = count(array_unique(array_column($activos, 'cedula')));
$sinPrestamo = $totalEmp - $conPrestamo;

// Agrupar por departamento
$todos   = $Empleado->View();
$deptos  = [];
foreach ($todos as $e) {
    $dep = $e['departamento'] ?? 'Sin depto.';
    if (!isset($deptos[$dep])) $deptos[$dep] = ['total' => 0, 'con_prestamo' => 0];
    $deptos[$dep]['total']++;
}
foreach ($activos as $p) {
    foreach ($todos as $e) {
        if ($e['cedula'] == $p['cedula']) {
            $dep = $e['departamento'] ?? 'Sin depto.';
            if (isset($deptos[$dep])) $deptos[$dep]['con_prestamo']++;
            break;
        }
    }
}
arsort($deptos);

$vencidos = $Nomina->Prestamos_Vencidos();
$estado   = $promedio < 41 ? ['ÓPTIMO','#16a34a'] : ($promedio <= 60 ? ['MODERADO','#d97706'] : ['CRÍTICO','#dc2626']);

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte Tasa de Uso — Préstamos</title>
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
    border-left:4px solid #3b82f6; padding-left:8px; margin:14px 0 8px 0; }
.kpi-table { width:100%; border-collapse:separate; border-spacing:6px; }
.kpi-cell { width:25%; background:#f8fafc; border:1px solid #e2e8f0;
    border-radius:6px; padding:10px 8px; text-align:center; vertical-align:middle; }
.kpi-label { font-size:9px; color:#6b7280; display:block; margin-bottom:4px;
    text-transform:uppercase; letter-spacing:.3px; }
.kpi-value { font-size:14px; font-weight:bold; color:#1a1a2e; }
.kpi-sub { font-size:9px; color:#9ca3af; display:block; margin-top:2px; }
.dep-table { width:100%; border-collapse:collapse; font-size:9.5px; margin-top:4px; }
.dep-table thead tr { background:#3b82f6; color:#fff; }
.dep-table th, .dep-table td { padding:6px 8px; border-bottom:1px solid #e5e7eb; }
.dep-table td.muted { color:#9ca3af; }
.bar-bg { background:#e5e7eb; border-radius:3px; height:8px; }
.bar-fill { height:8px; border-radius:3px; }
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
                <div class="doc-title">Reporte de Tasa de Uso de Préstamos</div>
            </td>
            <td class="header-right">Fecha de emisión:<br><strong><?= date('d-m-Y') ?></strong></td>
        </tr>
    </table>
</div>

<div class="section-title">Resumen ejecutivo</div>
<table class="kpi-table">
    <tr>
        <td class="kpi-cell">
            <span class="kpi-label">Tasa de uso</span>
            <span class="kpi-value"><?= number_format($promedio, 2) ?>%</span>
            <span class="kpi-sub" style="color:<?= $estado[1] ?>; font-weight:bold;"><?= $estado[0] ?></span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-label">Total empleados</span>
            <span class="kpi-value"><?= $totalEmp ?></span>
            <span class="kpi-sub">activos</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-label">Con préstamo activo</span>
            <span class="kpi-value"><?= $conPrestamo ?></span>
            <span class="kpi-sub">empleados</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-label">Sin préstamo</span>
            <span class="kpi-value"><?= $sinPrestamo ?></span>
            <span class="kpi-sub">empleados</span>
        </td>
    </tr>
</table>

<div class="section-title" style="margin-top:18px;">Uso por departamento</div>
<table class="dep-table">
    <thead>
        <tr>
            <th>Departamento</th>
            <th class="text-center">Total Emp.</th>
            <th class="text-center">Con Préstamo</th>
            <th class="text-center">Tasa %</th>
            <th style="width:30%;">Distribución</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($deptos as $nombre => $vals): 
        $pct = $vals['total'] > 0 ? round(($vals['con_prestamo'] / $vals['total']) * 100, 1) : 0;
        $color = $pct > 60 ? '#ef4444' : ($pct > 40 ? '#f59e0b' : '#22c55e');
    ?>
        <tr>
            <td style="font-weight:600;"><?= htmlspecialchars($nombre) ?></td>
            <td style="text-align:center;"><?= $vals['total'] ?></td>
            <td style="text-align:center;"><?= $vals['con_prestamo'] ?></td>
            <td style="text-align:center; font-weight:bold; color:<?= $color ?>;"><?= $pct ?>%</td>
            <td><div class="bar-bg"><div class="bar-fill" style="width:<?= $pct ?>%; background:<?= $color ?>;"></div></div></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="section-title" style="margin-top:18px;">Top 10 — Mayor deuda pendiente</div>
<table class="dep-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Empleado</th>
            <th>Cédula</th>
            <th class="text-end">Monto Original $</th>
            <th class="text-end">Deuda Pendiente $</th>
            <th>Progreso</th>
        </tr>
    </thead>
    <tbody>
    <?php
    usort($activos, fn($a, $b) => $b['monto_desc'] <=> $a['monto_desc']);
    $top10 = array_slice($activos, 0, 10);
    foreach ($top10 as $i => $p):
        $prog = $p['monto'] > 0 ? round((1 - $p['monto_desc'] / $p['monto']) * 100, 1) : 100;
        $col2 = $prog >= 70 ? '#22c55e' : ($prog >= 40 ? '#f59e0b' : '#ef4444');
    ?>
    <tr>
        <td><?= $i + 1 ?></td>
        <td style="font-weight:600;"><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido']) ?></td>
        <td><?= $p['cedula'] ?></td>
        <td style="text-align:right;"><?= number_format($p['monto'], 2) ?></td>
        <td style="text-align:right; font-weight:bold; color:#dc2626;"><?= number_format($p['monto_desc'], 2) ?></td>
        <td><div class="bar-bg"><div class="bar-fill" style="width:<?= $prog ?>%; background:<?= $col2 ?>;"></div></div>
        <span style="font-size:8px; color:#6b7280;"><?= $prog ?>%</span></td>
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
$dompdf->stream('Prestamos-uso-' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
