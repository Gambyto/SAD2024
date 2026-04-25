<?php
/**
 * ISLR-reporte.php
 * Reporte de aportes ISLR filtrado por año.
 * Recibe: ?anio=YYYY
 * Ubicación: View/PlantillaPDF/ISLR-reporte.php
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

$nombres_meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                  'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

// ── Datos ──────────────────────────────────────────────────────────────────────
$mensual   = $Nomina->ISLR_GrapByAnio($anio);       // 12 filas mes→monto
$detalle   = $Nomina->ISLR_Detail($anio);            // empleados con mes_1…mes_12
$indicator = $Nomina->ISLR_Indicator();              // [0]=mes actual, [1]=mes anterior

// Montos mensuales indexados (0=enero … 11=diciembre)
$values = array_column($mensual, 'monto');
while (count($values) < 12) $values[] = 0;          // garantizar 12 posiciones

$totalAnio      = array_sum($values);
$maxVal         = !empty($values) ? max($values) : 0;
$maxIdx         = $maxVal > 0 ? array_search($maxVal, $values) : -1;
$mesesConAporte = count(array_filter($values, fn($v) => $v > 0));

// ── Variación respecto al año anterior ────────────────────────────────────────
$aniosDisponibles = $Nomina->ISLR_GetAnios();
$montoAnioAnterior = 0;
foreach ($aniosDisponibles as $a) {
    if ((int)$a['anio'] === $anio - 1) {
        $rowAnterior = $Nomina->ISLR_GrapByAnio($anio - 1);
        $montoAnioAnterior = array_sum(array_column($rowAnterior, 'monto'));
        break;
    }
}

if ($montoAnioAnterior > 0) {
    $variacion      = (($totalAnio - $montoAnioAnterior) / $montoAnioAnterior) * 100;
    $variacionSign  = $variacion >= 0 ? '+' : '';
    $variacionStr   = $variacionSign . number_format($variacion, 2) . '%';
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
    <title>Reporte ISLR <?= $anio ?></title>
    <style>
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

        /* ── Títulos de sección ── */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #1a1a2e;
            border-left: 4px solid #c0392b;
            padding-left: 8px;
            margin: 14px 0 8px 0;
        }

        /* ── KPI cards ── */
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
        .kpi-label { font-size: 9px; color: #6b7280; display: block; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.3px; }
        .kpi-value { font-size: 14px; font-weight: bold; color: #1a1a2e; }
        .kpi-sub   { font-size: 9px; color: #9ca3af; display: block; margin-top: 2px; }

        /* ── Tabla mensual resumen ── */
        .monthly-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            font-size: 9.5px;
        }
        .monthly-table thead tr {
            background-color: #c0392b;
            color: #ffffff;
        }
        .monthly-table th {
            padding: 6px 4px;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            border: none;
        }
        .monthly-table th.label-col { text-align: left; padding-left: 6px; }
        .monthly-table td {
            padding: 6px 4px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
            color: #374151;
        }
        .monthly-table td.label-col { text-align: left; padding-left: 6px; font-weight: bold; }
        .monthly-table td.muted { color: #9ca3af; }
        .monthly-table tfoot td {
            background: #fff5f5;
            font-weight: bold;
            font-size: 9.5px;
            padding: 6px 4px;
            border-top: 2px solid #c0392b;
            text-align: center;
        }
        .monthly-table tfoot td.label-col { text-align: left; padding-left: 6px; }

        /* ── Tabla de detalle por empleado ── */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            font-size: 9px;
        }
        .detail-table thead tr {
            background-color: #1a1a2e;
            color: #ffffff;
        }
        .detail-table th {
            padding: 6px 4px;
            text-align: center;
            font-size: 8.5px;
            font-weight: bold;
            border: none;
        }
        .detail-table th.name-col { text-align: left; padding-left: 6px; }
        .detail-table td {
            padding: 5px 4px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
            color: #374151;
        }
        .detail-table td.name-col { text-align: left; padding-left: 6px; font-weight: 600; color: #16213e; white-space: nowrap; }
        .detail-table td.muted { color: #9ca3af; }
        .detail-table td.total-col { font-weight: bold; color: #c0392b; background: #fff5f5; }
        .detail-table tbody tr:nth-child(even) td { background-color: #fafafa; }
        .detail-table tbody tr:nth-child(even) td.total-col { background-color: #fff0f0; }
        .detail-table tfoot td {
            background-color: #f1f5f9;
            font-weight: bold;
            font-size: 9px;
            padding: 6px 4px;
            border-top: 2px solid #1a1a2e;
            text-align: center;
        }
        .detail-table tfoot td.name-col { text-align: right; padding-right: 6px; }

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
                        Reporte de Aportes ISLR &mdash; Año <?= $anio ?>
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
                <span class="kpi-label">Total aportado en <?= $anio ?></span>
                <span class="kpi-value"><?= number_format($totalAnio, 2) ?> Bs.</span>
                <span class="kpi-sub"><?= $mesesConAporte ?> mes(es) con aporte</span>
            </td>
            <td class="kpi-cell">
                <span class="kpi-label">Variación vs <?= $anio - 1 ?></span>
                <span class="kpi-value" style="color:<?= $variacionColor ?>">
                    <?= $variacionStr ?>
                </span>
                <span class="kpi-sub"><?= number_format($montoAnioAnterior, 2) ?> Bs. año anterior</span>
            </td>
            <td class="kpi-cell">
                <span class="kpi-label">Mayor aporte mensual</span>
                <span class="kpi-value"><?= $maxIdx >= 0 ? number_format($maxVal, 2) . ' Bs.' : '—' ?></span>
                <span class="kpi-sub"><?= $maxIdx >= 0 ? $nombres_meses[$maxIdx] : '' ?></span>
            </td>
            <td class="kpi-cell">
                <span class="kpi-label">Empleados con aporte</span>
                <span class="kpi-value"><?= count($detalle) ?></span>
                <span class="kpi-sub">en <?= $anio ?></span>
            </td>
        </tr>
    </table>

    <!-- ══ TABLA MENSUAL ══ -->
    <div class="section-title" style="margin-top: 18px;">
        Distribución mensual de aportes &mdash; <?= $anio ?>
    </div>
    <table class="monthly-table">
        <thead>
            <tr>
                <th class="label-col">Concepto</th>
                <?php foreach ($nombres_meses as $nm): ?>
                    <th><?= substr($nm, 0, 3) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="label-col">Monto Bs.</td>
                <?php foreach ($values as $v): ?>
                    <td class="<?= $v > 0 ? '' : 'muted' ?>">
                        <?= $v > 0 ? number_format($v, 2) : '—' ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td class="label-col">TOTAL</td>
                <?php
                    $acum = 0;
                    foreach ($values as $v):
                        $acum += $v;
                ?>
                    <td><?= $v > 0 ? number_format($v, 2) : '—' ?></td>
                <?php endforeach; ?>
            </tr>
        </tfoot>
    </table>

    <!-- ══ TABLA DE DETALLE POR EMPLEADO ══ -->
    <div class="section-title" style="margin-top: 18px;">
        Detalle por empleado &mdash; <?= $anio ?>
    </div>

    <?php if (empty($detalle)): ?>
        <p class="no-data">No hay registros de aportes ISLR para el año <?= $anio ?>.</p>
    <?php else: ?>
        <table class="detail-table">
            <thead>
                <tr>
                    <th class="name-col">Empleado</th>
                    <?php foreach ($nombres_meses as $nm): ?>
                        <th><?= substr($nm, 0, 3) ?></th>
                    <?php endforeach; ?>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalesMes = array_fill(1, 12, 0);
                $granTotal  = 0;
                foreach ($detalle as $emp):
                    $totalEmp = 0;
                    for ($m = 1; $m <= 12; $m++) {
                        $totalEmp        += (float)($emp['mes_' . $m] ?? 0);
                        $totalesMes[$m]  += (float)($emp['mes_' . $m] ?? 0);
                    }
                    $granTotal += $totalEmp;
                ?>
                <tr>
                    <td class="name-col"><?= htmlspecialchars($emp['nombre']) ?></td>
                    <?php for ($m = 1; $m <= 12; $m++):
                        $monto = (float)($emp['mes_' . $m] ?? 0);
                    ?>
                        <td class="<?= $monto > 0 ? '' : 'muted' ?>">
                            <?= $monto > 0 ? number_format($monto, 2) : '—' ?>
                        </td>
                    <?php endfor; ?>
                    <td class="total-col"><?= number_format($totalEmp, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="name-col" style="text-align:right; font-weight:bold;">TOTAL <?= $anio ?></td>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <td><?= $totalesMes[$m] > 0 ? number_format($totalesMes[$m], 2) : '—' ?></td>
                    <?php endfor; ?>
                    <td style="color:#c0392b;"><?= number_format($granTotal, 2) ?></td>
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
$dompdf->setPaper('A4', 'landscape');   // landscape por la tabla de 12 meses
$dompdf->render();
$dompdf->stream('ISLR-' . $anio . '.pdf', ['Attachment' => false]);
