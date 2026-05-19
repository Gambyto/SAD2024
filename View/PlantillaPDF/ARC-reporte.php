<?php
/**
 * ARC-reporte.php
 * Comprobante de Retención de Impuesto Sobre la Renta (Planilla AR-C)
 * Parámetros: ?cedula=XXXXXXXX&anio=YYYY
 *             ?anio=YYYY  (sin cédula → genera un PDF por empleado, uno tras otro)
 * Ubicación:  View/PlantillaPDF/ARC-reporte.php
 */
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

include '../../PHP/CLASS/conexion_Original.php';
include '../../PHP/CLASS/user_Original.php';

// ── Parámetros ────────────────────────────────────────────────────────────────
$anio   = isset($_GET['anio'])   ? (int)$_GET['anio']   : (int)date('Y');
$cedula = isset($_GET['cedula']) && $_GET['cedula'] !== '' ? trim($_GET['cedula']) : null;

// ── Lista de empleados a generar ──────────────────────────────────────────────
if ($cedula !== null) {
    // Un solo empleado — usamos directamente la cédula del GET
    $empleados = [['cedula' => $cedula]];
} else {
    // Todos los empleados con pagos en ese año
    $empleados = $Nomina->ARC_GetEmpleados($anio);
}

$nombres_meses = [
    1 => 'ENERO', 2 => 'FEBRERO',  3 => 'MARZO',    4 => 'ABRIL',
    5 => 'MAYO',  6 => 'JUNIO',    7 => 'JULIO',     8 => 'AGOSTO',
    9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE',
];

// ═══════════════════════════════════════════════════════════════════════════════
// HELPERS
// ═══════════════════════════════════════════════════════════════════════════════
function fmt($val): string {
    return number_format((float)$val, 2, '.', ',');
}
function pct($val): string {
    $v = (float)$val;
    return ($v == 0) ? '0,00%' : number_format($v, 2, '.', ',') . '%';
}

// ═══════════════════════════════════════════════════════════════════════════════
// GENERACIÓN DE HTML (una página por empleado)
// ═══════════════════════════════════════════════════════════════════════════════
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>AR-C <?= $anio ?></title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: Arial, sans-serif;
        font-size: 10px;
        color: #1a1a1a;
        background: #fff;
        padding: 20px;
    }

    /* ── Separador de página para dompdf ── */
    .page-break { page-break-after: always; }

    /* ══ CABECERA INSTITUCIONAL ══ */
    .header-wrap {
        width: 100%;
        border-bottom: 3px solid #1a1a2e;
        padding-bottom: 8px;
        margin-bottom: 10px;
    }
    .header-table { width: 100%; border-collapse: collapse; }
    .header-table td { border: none; vertical-align: middle; }

    .logo { width: 65px; height: 65px; }

    .company-name {
        font-size: 14px;
        font-weight: bold;
        color: #1a1a2e;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .doc-subtitle {
        font-size: 10px;
        color: #374151;
        margin-top: 2px;
        font-weight: bold;
        text-transform: uppercase;
    }
    .doc-period {
        font-size: 9px;
        color: #6b7280;
        margin-top: 2px;
    }
    .header-right {
        text-align: right;
        font-size: 9px;
        color: #6b7280;
        line-height: 1.5;
    }

    /* ══ BLOQUES DE DATOS (Agente / Contribuyente) ══ */
    .info-block {
        width: 100%;
        border: 1.5px solid #1a1a2e;
        border-collapse: collapse;
        margin-bottom: 8px;
    }
    .info-block th {
        background: #1a1a2e;
        color: #fff;
        font-size: 9.5px;
        padding: 5px 8px;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border: none;
    }
    .info-block td {
        padding: 5px 8px;
        font-size: 9.5px;
        border: 1px solid #cbd5e1;
        vertical-align: middle;
    }
    .info-label {
        font-weight: bold;
        color: #374151;
        white-space: nowrap;
        width: 120px;
    }
    .info-value { color: #1a1a2e; }

    /* ══ TABLA PRINCIPAL DE PAGOS ══ */
    .arc-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 6px;
        font-size: 9px;
    }

    /* Encabezado de grupos */
    .arc-table .group-header td {
        background: #1a1a2e;
        color: #fff;
        font-weight: bold;
        font-size: 9px;
        padding: 5px 6px;
        text-align: center;
        border: 1px solid #1a1a2e;
    }
    .arc-table .group-header td.left { text-align: left; padding-left: 8px; }

    /* Fila de cabecera de columnas */
    .arc-table thead tr.col-header td {
        background: #c0392b;
        color: #fff;
        font-weight: bold;
        font-size: 8.5px;
        padding: 5px 4px;
        text-align: center;
        border: 1px solid #e5e7eb;
    }
    .arc-table thead tr.col-header td.left { text-align: left; padding-left: 6px; }

    /* Filas de datos */
    .arc-table tbody tr td {
        padding: 5px 4px;
        text-align: right;
        border: 1px solid #e5e7eb;
        color: #374151;
        vertical-align: middle;
    }
    .arc-table tbody tr td.mes-label {
        text-align: left;
        padding-left: 8px;
        font-weight: bold;
        color: #1a1a2e;
        white-space: nowrap;
    }
    .arc-table tbody tr td.zero { color: #9ca3af; }

    /* Fila de alternancia */
    .arc-table tbody tr:nth-child(even) td { background: #f8fafc; }

    /* Fila de bonos anuales */
    .arc-table tr.bono-row td {
        background: #f0f4ff;
        font-style: italic;
    }
    .arc-table tr.bono-row td.mes-label { font-weight: bold; font-style: normal; }

    /* Separador visual antes de bonos */
    .arc-table tr.sep-bono td {
        background: #e2e8f0;
        height: 2px;
        padding: 0;
        border: none;
    }

    /* Fila de totales */
    .arc-table tfoot tr td {
        background: #1a1a2e;
        color: #fff;
        font-weight: bold;
        font-size: 9.5px;
        padding: 6px 4px;
        text-align: right;
        border: 1px solid #1a1a2e;
    }
    .arc-table tfoot tr td.left {
        text-align: left;
        padding-left: 8px;
    }
    .arc-table tfoot tr td.highlight {
        background: #c0392b;
        font-size: 10px;
    }

    /* ══ NOTA TIPO VENDEDOR ══ */
    .vendedor-badge {
        display: inline-block;
        background: #fef9c3;
        border: 1px solid #eab308;
        color: #78350f;
        border-radius: 4px;
        padding: 2px 6px;
        font-size: 8px;
        font-weight: bold;
        margin-left: 6px;
        vertical-align: middle;
    }

    /* ══ TOTAL A DECLARAR ══ */
    .declarar-wrap {
        margin-top: 10px;
        text-align: right;
    }
    .declarar-box {
        display: inline-block;
        border: 2px solid #1a1a2e;
        padding: 6px 16px;
        background: #f8fafc;
    }
    .declarar-label {
        font-size: 10px;
        font-weight: bold;
        color: #1a1a2e;
        text-transform: uppercase;
    }
    .declarar-value {
        font-size: 15px;
        font-weight: bold;
        color: #c0392b;
        margin-left: 8px;
    }

    /* ══ FIRMA ══ */
    .firma-section {
        margin-top: 20px;
    }
    .firma-table { width: 100%; border-collapse: collapse; }
    .firma-table td {
        width: 33%;
        text-align: center;
        padding: 6px;
        vertical-align: bottom;
    }
    .firma-line {
        border-top: 1px solid #374151;
        margin: 0 20px;
        padding-top: 4px;
        font-size: 9px;
        color: #374151;
    }
    .firma-cargo {
        font-size: 8px;
        color: #6b7280;
        margin-top: 2px;
    }

    /* ══ FOOTER ══ */
    .footer {
        margin-top: 12px;
        border-top: 1px solid #e5e7eb;
        padding-top: 6px;
        font-size: 8.5px;
        color: #9ca3af;
        text-align: right;
    }
</style>
</head>
<body>
<?php
// ─────────────────────────────────────────────────────────────────────────────
// Iterar sobre cada empleado
// ─────────────────────────────────────────────────────────────────────────────
$totalEmpleados = count($empleados);
foreach ($empleados as $idx => $empBase):

    $arc = $Nomina->ARC_GetDetalle($empBase['cedula'], $anio);
    if (empty($arc)) continue;

    $emp    = $arc['empleado'];
    $meses  = $arc['meses'];   // [1..12]
    $bonos  = $arc['bonos'];
    $total  = $arc['total'];

    $esVendedor   = $emp['es_vendedor'];
    $nombreComp   = strtoupper($emp['nombre'] . ' ' . $emp['apellido']);
    $cargoComp    = strtoupper($emp['cargo']);
    $fIngreso     = date('d/m/Y', strtotime($emp['f_ingreso']));
    $cedulaFmt    = $emp['cedula'];
    $rifFmt       = 'V-' . $emp['cedula'] . '-' . (substr($emp['cedula'], -1));  // RIF básico

    // Acumulados finales (mes 12)
    $acumDevFinal = $meses[12]['acumulado_devengado'] ?? 0;
    $acumRetFinal = $meses[12]['acumulado_retencion'] ?? 0;
?>

    <!-- ══════════════════════════════════════════════════════════════════
         PÁGINA DEL EMPLEADO
    ═══════════════════════════════════════════════════════════════════ -->

    <!-- CABECERA INSTITUCIONAL -->
    <div class="header-wrap">
        <table class="header-table">
            <tr>
                <td style="width:75px;">
                    <img src="http://<?= $_SERVER['HTTP_HOST'] ?>/PIUT_V1/IMG/Logo_Comple_Black.png"
                         class="logo" alt="Logo">
                </td>
                <td>
                    <div class="company-name">DISORIENT, C.A.</div>
                    <div class="doc-subtitle">
                        Comprobante de Retención de Impuesto Sobre la Renta
                    </div>
                    <div class="doc-subtitle" style="color:#c0392b; margin-top:2px;">
                        PLANILLA AR-C
                    </div>
                    <div class="doc-period">
                        Período del 01/01/<?= $anio ?> al 31/12/<?= $anio ?>
                    </div>
                </td>
                <td class="header-right">
                    Fecha de emisión:<br>
                    <strong><?= date('d/m/Y') ?></strong><br><br>
                    RIF Empresa:<br>
                    <strong>J-080199936</strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- AGENTE DE RETENCIÓN -->
    <table class="info-block">
        <tr>
            <th colspan="4">Agente de Retención</th>
        </tr>
        <tr>
            <td class="info-label">Organismo:</td>
            <td class="info-value" colspan="3"><strong>DISORIENT, C.A.</strong></td>
        </tr>
        <tr>
            <td class="info-label">RIF:</td>
            <td class="info-value">J-080199936</td>
            <td class="info-label" style="width:100px;">Dirección:</td>
            <td class="info-value">Av. Cancamure No. 69 &ndash; Edif. Disorient &ndash; Planta Baja<br>
                    Cumaná &ndash; Estado Sucre</td>
        </tr>
    </table>

    <!-- CONTRIBUYENTE -->
    <table class="info-block">
        <tr>
            <th colspan="4">
                Contribuyente
            </th>
        </tr>
        <tr>
            <td class="info-label">Nombre:</td>
            <td class="info-value"><strong><?= htmlspecialchars($nombreComp) ?></strong></td>
            <td class="info-label" style="width:80px;">Cédula:</td>
            <td class="info-value"><strong><?= htmlspecialchars($cedulaFmt) ?></strong></td>
        </tr>
        <tr>
            <td class="info-label">Cargo:</td>
            <td class="info-value"><?= htmlspecialchars($cargoComp) ?></td>
            <td class="info-label">F. Ingreso:</td>
            <td class="info-value"><?= $fIngreso ?></td>
        </tr>
    </table>

    <!-- TABLA PRINCIPAL AR-C -->
    <table class="arc-table">
        <thead>
            <!-- Encabezado de grupos de columnas -->
            <tr class="group-header">
                <td class="left" style="width:20%;">
                    Asignaciones<?php if ($esVendedor): ?> / Comisiones<?php endif; ?>
                </td>
                <td style="width:16%;">Remuneración (Bs.)</td>
                <td style="width:10%;">% ISLR</td>
                <td style="width:18%;">Retención Mensual</td>
                <td style="width:18%;">Acum. Devengado</td>
                <td style="width:18%;">Acum. Retención</td>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($nombres_meses as $numMes => $nombreMes):
                $datos = $meses[$numMes];
                $isZeroRem = ($datos['remuneracion']      == 0);
                $isZeroRet = ($datos['retencion_mensual'] == 0);
            ?>
            <tr>
                <td class="mes-label"><?= $nombreMes ?></td>
                <td class="<?= $isZeroRem ? 'zero' : '' ?>">
                    <?= fmt($datos['remuneracion']) ?>
                </td>
                <td class="<?= $isZeroRet ? 'zero' : '' ?>">
                    <?= pct($datos['porcentaje_islr']) ?>
                </td>
                <td class="<?= $isZeroRet ? 'zero' : '' ?>">
                    <?= fmt($datos['retencion_mensual']) ?>
                </td>
                <td><?= fmt($datos['acumulado_devengado']) ?></td>
                <td><?= fmt($datos['acumulado_retencion']) ?></td>
            </tr>
            <?php endforeach; ?>

            <!-- Separador visual -->
            <tr class="sep-bono"><td colspan="6"></td></tr>

            <!-- BONO VACACIONAL 
            <tr class="bono-row">
                <td class="mes-label">BONO VACACIONAL</td>
                <td class="<?= $bonos['bono_vacacional'] == 0 ? 'zero' : '' ?>">
                    <?= fmt($bonos['bono_vacacional']) ?>
                </td>
                <td class="zero">0,00%</td>
                <td class="zero">0,00</td>
                <td>—</td>
                <td>—</td>
            </tr>

            <!-- BONO FIN DE AÑO 
            <tr class="bono-row">
                <td class="mes-label">BONO FIN DE AÑO</td>
                <td class="<?= $bonos['bono_fin_anio'] == 0 ? 'zero' : '' ?>">
                    <?= fmt($bonos['bono_fin_anio']) ?>
                </td>
                <td class="zero">0,00%</td>
                <td class="zero">0,00</td>
                <td>—</td>
                <td>—</td>
            </tr>

            <?php if ($esVendedor): ?>
            <!-- BONO ESPECIAL (comisiones anuales si es vendedor) 
            <tr class="bono-row">
                <td class="mes-label">BONO ESPECIAL / COMISIONES ANUALES</td>
                <td class="<?= $bonos['bono_especial'] == 0 ? 'zero' : '' ?>">
                    <?= fmt($bonos['bono_especial']) ?>
                </td>
                <td class="zero">0,00%</td>
                <td class="zero">0,00</td>
                <td>—</td>
                <td>—</td>
            </tr> -->
            <?php endif; ?>
        </tbody>

        <tfoot>
            <tr>
                <td class="left">TOTAL A DECLARAR</td>
                <td class="highlight"><?= fmt($total) ?> Bs.</td>
                <td>—</td>
                <td><?= fmt($acumRetFinal) ?> Bs.</td>
                <td><?= fmt($acumDevFinal) ?> Bs.</td>
                <td><?= fmt($acumRetFinal) ?> Bs.</td>
            </tr>
        </tfoot>
    </table>

    <!-- TOTAL A DECLARAR (cuadro resaltado igual que la referencia) -->
    <div class="declarar-wrap">
        <div class="declarar-box">
            <span class="declarar-label">Total a Declarar Bs.</span>
            <span class="declarar-value"><?= fmt($total) ?></span>
        </div>
    </div>

    <!-- SECCIÓN DE FIRMAS -->
    <div class="firma-section" style="margin-top: 5rem;">
        <table class="firma-table">
            <tr>
                <td>
                    <div class="firma-line">
                        Jefe de Nómina<br>
                        <span class="firma-cargo">Dirección de Talento Humano</span>
                    </div>
                </td>
                <td>
                    <div class="firma-line">
                        Director(a) de Talento Humano
                    </div>
                </td>
                <td>
                    <div class="firma-line">
                        Empleado / Contribuyente<br>
                        <span class="firma-cargo"><?= htmlspecialchars($nombreComp) ?></span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Generado el <?= date('d/m/Y') ?> a las <?= date('H:i') ?>
        &nbsp;|&nbsp; DISORIENT, C.A. &nbsp;|&nbsp; Sistema de Nómina
        &nbsp;|&nbsp; AR-C <?= $anio ?> — <?= htmlspecialchars($nombreComp) ?>
    </div>

    <?php
    // Salto de página entre empleados (excepto el último)
    if ($idx < $totalEmpleados - 1):
    ?>
    <div class="page-break"></div>
    <?php endif; ?>

<?php endforeach; ?>
</body>
</html>
<?php

// ═══════════════════════════════════════════════════════════════════════════════
// RENDERIZADO CON DOMPDF
// ═══════════════════════════════════════════════════════════════════════════════
$html = ob_get_clean();

require_once '../../PHP/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('Letter', 'portrait');   // Carta vertical, igual que la planilla oficial

$dompdf->render();

// Nombre de archivo: si es un solo empleado lo incluye en el nombre
$sufijo   = ($cedula !== null) ? '-' . $cedula : '-todos';
$filename = 'ARC-' . $anio . $sufijo . '.pdf';

$dompdf->stream($filename, ['Attachment' => false]);
