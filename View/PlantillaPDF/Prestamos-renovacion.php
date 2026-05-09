<?php
/**
 * Prestamos-renovacion.php — Reporte PDF: Frecuencia de Renovación
 * Ubicación: View/PlantillaPDF/Prestamos-renovacion.php
 */
session_start();
if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }

include '../../PHP/CLASS/conexion_Original.php';
include '../../PHP/CLASS/user_Original.php';

$totales   = $Nomina->Total_Prestamos();
$prestamos = (int)($totales[0]['prestamos_realizados'] ?? 0);
$frecuency = round(($prestamos * 0.033) * 100, 2);
$todos     = $Nomina->Prestamos_View_report();

$estado = $frecuency < 41 ? ['ÓPTIMO','#16a34a'] : ($frecuency <= 60 ? ['MODERADO','#d97706'] : ['ALTO RIESGO','#dc2626']);

// Tendencia mensual
$porMes = [];
foreach ($todos as $p) {
    $key = date('Y-m', strtotime($p['fecha']));
    if (!isset($porMes[$key])) $porMes[$key] = 0;
    $porMes[$key]++;
}
ksort($porMes);
$meses_n = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
$tendencia = [];
foreach ($porMes as $mes => $cnt) {
    list($y, $m) = explode('-', $mes);
    $tendencia[] = ['label' => $meses_n[(int)$m] . ' ' . $y, 'cantidad' => $cnt];
}

// Empleados con más de 1 préstamo
$cedulaCount = array_count_values(array_column($todos, 'cedula'));
arsort($cedulaCount);
$multiEmp = array_filter($cedulaCount, fn($c) => $c > 1);

// Mapa cedula → nombre
$nombresMap = [];
foreach ($todos as $p) { $nombresMap[$p['cedula']] = $p['nombre'] . ' ' . $p['apellido']; }

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte Frecuencia de Renovación</title>
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
    border-left:4px solid #6366f1; padding-left:8px; margin:14px 0 8px 0; }
.kpi-table { width:100%; border-collapse:separate; border-spacing:6px; }
.kpi-cell { width:25%; background:#f8fafc; border:1px solid #e2e8f0;
    border-radius:6px; padding:10px 8px; text-align:center; }
.kpi-label { font-size:9px; color:#6b7280; display:block; margin-bottom:4px;
    text-transform:uppercase; letter-spacing:.3px; }
.kpi-value { font-size:14px; font-weight:bold; color:#1a1a2e; }
.kpi-sub { font-size:9px; color:#9ca3af; display:block; margin-top:2px; }
.tend-table { width:100%; border-collapse:collapse; font-size:9.5px; margin-top:4px; }
.tend-table thead tr { background:#6366f1; color:#fff; }
.tend-table th, .tend-table td { padding:6px 8px; border-bottom:1px solid #e5e7eb; }
.tend-table tbody tr:nth-child(even) td { background:#f9fafb; }
.bar-bg { background:#e5e7eb; border-radius:3px; height:8px; display:inline-block; vertical-align:middle; }
.bar-fill { height:8px; border-radius:3px; }
.info-box { background:#eff6ff; border-left:4px solid #6366f1;
    padding:10px 12px; border-radius:4px; font-size:10px; color:#374151; line-height:1.6; margin:8px 0; }
.multi-table { width:100%; border-collapse:collapse; font-size:9.5px; margin-top:4px; }
.multi-table thead tr { background:#374151; color:#fff; }
.multi-table th, .multi-table td { padding:6px 8px; border-bottom:1px solid #e5e7eb; }
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
                <div class="doc-title">Reporte de Frecuencia de Renovación de Préstamos</div>
            </td>
            <td class="header-right">Fecha de emisión:<br><strong><?= date('d-m-Y') ?></strong></td>
        </tr>
    </table>
</div>

<div class="section-title">Resumen ejecutivo</div>
<table class="kpi-table">
    <tr>
        <td class="kpi-cell">
            <span class="kpi-label">Tasa de renovación</span>
            <span class="kpi-value"><?= $frecuency ?>%</span>
            <span class="kpi-sub" style="color:<?= $estado[1] ?>; font-weight:bold;"><?= $estado[0] ?></span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-label">Préstamos totales</span>
            <span class="kpi-value"><?= $prestamos ?></span>
            <span class="kpi-sub">histórico acumulado</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-label">Empleados multi-préstamo</span>
            <span class="kpi-value"><?= count($multiEmp) ?></span>
            <span class="kpi-sub">han renovado al menos 1 vez</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-label">Total renovaciones</span>
            <?php $totRen = array_sum($multiEmp) - count($multiEmp); ?>
            <span class="kpi-value"><?= $totRen ?></span>
            <span class="kpi-sub">préstamos adicionales</span>
        </td>
    </tr>
</table>

<div class="info-box">
    <strong>Metodología:</strong> La frecuencia de renovación se calcula como
    <strong>(total de préstamos realizados × 0.033) × 100</strong>.
    Un resultado inferior a 40% indica baja rotación (óptimo), entre 40–60% requiere seguimiento,
    y superior al 60% señala alta frecuencia de renovación (riesgo de sobre-endeudamiento).
</div>

<div class="section-title" style="margin-top:14px;">Tendencia mensual de préstamos otorgados</div>
<?php
$maxCant = !empty($tendencia) ? max(array_column($tendencia, 'cantidad')) : 1;
$tendRec = array_slice(array_reverse($tendencia), 0, 18);
$tendRec = array_reverse($tendRec);
?>
<table class="tend-table">
    <thead>
        <tr>
            <th>Período</th>
            <th class="text-center">Cantidad otorgados</th>
            <th style="width:50%;">Distribución</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($tendRec as $t): 
        $pct = $maxCant > 0 ? round(($t['cantidad'] / $maxCant) * 100, 0) : 0;
    ?>
    <tr>
        <td style="font-weight:600;"><?= $t['label'] ?></td>
        <td style="text-align:center;"><?= $t['cantidad'] ?></td>
        <td><div class="bar-bg" style="width:<?= max($pct, 4) ?>%;">
            <div class="bar-fill" style="width:100%; background:#6366f1;"></div></div>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php if (!empty($multiEmp)): ?>
<div class="section-title" style="margin-top:18px;">Empleados con múltiples préstamos</div>
<table class="multi-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Empleado</th>
            <th>Cédula</th>
            <th class="text-center">Total Préstamos</th>
            <th class="text-center">Renovaciones</th>
        </tr>
    </thead>
    <tbody>
    <?php $idx = 1; foreach ($multiEmp as $ced => $cnt): ?>
    <tr>
        <td><?= $idx++ ?></td>
        <td style="font-weight:600;"><?= htmlspecialchars($nombresMap[$ced] ?? $ced) ?></td>
        <td><?= $ced ?></td>
        <td style="text-align:center; font-weight:bold;"><?= $cnt ?></td>
        <td style="text-align:center; color:#6366f1; font-weight:bold;"><?= $cnt - 1 ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

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
$dompdf->stream('Prestamos-renovacion-' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
