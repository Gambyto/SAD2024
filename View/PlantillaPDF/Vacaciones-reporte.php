<?php
/**
 * Vacaciones-reporte.php
 * Reporte de pagos por vacaciones filtrado por año.
 * Recibe: ?anio=YYYY
 * Ubicación: View/PlantillaPDF/Vacaciones-reporte.php
 */
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

include '../../PHP/CLASS/conexion_Original.php';
include '../../PHP/CLASS/user_Original.php';

// ── Parámetro ──────────────────────────────────────────────────────────────────
$anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');

// ── Datos ──────────────────────────────────────────────────────────────────────
$detalle        = $Nomina->Vacation_Detail_By_Year($anio);
$historico      = $Nomina->Vacation_Pay_Indicator();   // todos los años

$totalAnio      = array_sum(array_column($detalle, 'monto'));
$totalAcum      = array_sum(array_column($historico, 'monto'));
$promedioAnual  = count($historico) > 0 ? $totalAcum / count($historico) : 0;
$maxRow         = !empty($historico)
    ? array_reduce($historico, fn($c, $r) => (!$c || $r['monto'] > $c['monto']) ? $r : $c)
    : null;
$anioMaximo     = $maxRow['anio']  ?? '—';
$montoMaximo    = $maxRow['monto'] ?? 0;

// ── Variación respecto al año anterior ────────────────────────────────────────
$montoAnioAnterior = 0;
foreach ($historico as $h) {
    if ((int)$h['anio'] === $anio - 1) {
        $montoAnioAnterior = (float)$h['monto'];
        break;
    }
}
if ($montoAnioAnterior > 0) {
    $variacion     = (($totalAnio - $montoAnioAnterior) / $montoAnioAnterior) * 100;
    $variacionSign = $variacion >= 0 ? '+' : '';
    $variacionStr  = $variacionSign . number_format($variacion, 2) . '%';
    $variacionColor = $variacion > 0 ? '#dc2626' : '#16a34a';
} else {
    $variacionStr   = '—';
    $variacionColor = '#6b7280';
}

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Vacaciones <?= $anio ?></title>
    <style>
        /* ── Reset base ── */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1a1a1a; }

        /* ── Encabezado corporativo ── */
        .header-wrap {
            width: 100%;
            border-bottom: 3px solid #1a1a2e;
            padding-bottom: 8px;
            margin-bottom: 16px;
        }
        .header-table { width: 100%; border: none; border-collapse: collapse; }
        .header-table td { border: none; vertical-align: middle; }
        .logo { width: 70px; height: 70px; }
        .company-name {
            font-size: 15px;
            font-weight: bold;
            color: #1a1a2e;
            letter-spacing: 0.5px;
        }
        .doc-title {
            font-size: 11px;
            color: #4b5563;
            margin-top: 3px;
        }
        .header-right {
            text-align: right;
            font-size: 10px;
            color: #6b7280;
        }

        /* ── Sección título ── */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #1a1a2e;
            border-left: 4px solid #1a1a2e;
            padding-left: 8px;
            margin: 14px 0 8px 0;
        }

        /* ── KPI cards en tabla ── */
        .kpi-table { width: 100%; border-collapse: separate; border-spacing: 6px; }
        .kpi-cell {
            width: 25%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 8px;
            text-align: center;
            vertical-align: middle;
        }
        .kpi-label { font-size: 9px; color: #6b7280; display: block; margin-bottom: 4px; }
        .kpi-value { font-size: 14px; font-weight: bold; color: #1a1a2e; }
        .kpi-sub   { font-size: 9px; color: #9ca3af; display: block; margin-top: 2px; }

        /* ── Tabla de detalle ── */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }
        .detail-table thead tr {
            background-color: #1a1a2e;
            color: #ffffff;
        }
        .detail-table th {
            padding: 7px 6px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            border: none;
        }
        .detail-table td {
            padding: 6px 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
            color: #374151;
        }
        .detail-table tbody tr:nth-child(even) td {
            background-color: #f9fafb;
        }
        .detail-table .text-right { text-align: right; }
        .detail-table tfoot td {
            background-color: #f1f5f9;
            font-weight: bold;
            font-size: 10px;
            padding: 7px 6px;
            border-top: 2px solid #1a1a2e;
        }

        /* ── Sin datos ── */
        .no-data {
            text-align: center;
            color: #9ca3af;
            padding: 20px;
            font-style: italic;
        }

        /* ── Footer ── */
        .footer {
            margin-top: 20px;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            font-size: 9px;
            color: #9ca3af;
            text-align: right;
        }
    </style>
</head>
<body>

    <!-- ══ ENCABEZADO CORPORATIVO ══ -->
    <div class="header-wrap">
        <table class="header-table">
            <tr>
                <td style="width: 80px;">
                    <img src="http://<?= $_SERVER['HTTP_HOST'] ?>/PIUT_V1/IMG/Logo_Comple_Black.png"
                         class="logo">
                </td>
                <td>
                    <span class="company-name">DISORIENT, C.A.</span>
                    <div class="doc-title">
                        Reporte de Pagos por Vacaciones &mdash; Año <?= $anio ?>
                    </div>
                </td>
                <td class="header-right">
                    Fecha de emisión:<br>
                    <strong><?= date('d-m-Y') ?></strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- ══ KPIs ══ -->
    <div class="section-title">Resumen del año <?= $anio ?></div>
    <table class="kpi-table">
        <tr>
            <td class="kpi-cell">
                <span class="kpi-label">Total pagado en <?= $anio ?></span>
                <span class="kpi-value">$ <?= number_format($totalAnio, 2) ?></span>
                <span class="kpi-sub"><?= count($detalle) ?> empleado(s)</span>
            </td>
            <td class="kpi-cell">
                <span class="kpi-label">Variación vs <?= $anio - 1 ?></span>
                <span class="kpi-value" style="color:<?= $variacionColor ?>">
                    <?= $variacionStr ?>
                </span>
                <span class="kpi-sub">$ <?= number_format($montoAnioAnterior, 2) ?> año anterior</span>
            </td>
            <td class="kpi-cell">
                <span class="kpi-label">Promedio anual histórico</span>
                <span class="kpi-value">$ <?= number_format($promedioAnual, 2) ?></span>
                <span class="kpi-sub"><?= count($historico) ?> año(s) registrados</span>
            </td>
            <td class="kpi-cell">
                <span class="kpi-label">Año con mayor pago</span>
                <span class="kpi-value"><?= $anioMaximo ?></span>
                <span class="kpi-sub">$ <?= number_format($montoMaximo, 2) ?></span>
            </td>
        </tr>
    </table>

    <!-- ══ TABLA DE DETALLE ══ -->
    <div class="section-title" style="margin-top: 18px;">
        Detalle por empleado &mdash; <?= $anio ?>
    </div>

    <?php if (empty($detalle)): ?>
        <p class="no-data">No hay registros de vacaciones para el año <?= $anio ?>.</p>
    <?php else: ?>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Cédula</th>
                    <th>Inicio vacaciones</th>
                    <th>Fin vacaciones</th>
                    <th>Días hábiles</th>
                    <th class="text-right">Monto ($)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalle as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?></td>
                        <td><?= htmlspecialchars($row['cedula']) ?></td>
                        <td><?= htmlspecialchars($row['ini_vacaciones']) ?></td>
                        <td><?= htmlspecialchars($row['fin_vacaciones']) ?></td>
                        <td><?= htmlspecialchars($row['dias_habiles']) ?></td>
                        <td class="text-right">$ <?= number_format((float)$row['monto'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align: right;">TOTAL <?= $anio ?></td>
                    <td class="text-right">$ <?= number_format($totalAnio, 2) ?></td>
                </tr>
            </tfoot>
        </table>
    <?php endif; ?>

    <!-- ══ FOOTER ══ -->
    <div class="footer">
        Generado el <?= date('d/m/Y') ?> a las <?= date('H:i') ?> &nbsp;|&nbsp; DISORIENT, C.A. &nbsp;|&nbsp; Sistema de Nómina
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
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('Vacaciones-' . $anio . '.pdf', ['Attachment' => false]);