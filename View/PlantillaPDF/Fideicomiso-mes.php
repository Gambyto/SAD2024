<?php
/**
 * Fideicomiso-mes.php
 * PDF de fideicomiso filtrado por mes.
 * Recibe: ?mes=YYYY-MM
 */
session_start();
include '../../PHP/CLASS/conexion_Original.php';
include '../../PHP/CLASS/user_Original.php';

$mes = isset($_GET['mes']) ? trim($_GET['mes']) : date('Y-m');

if (!preg_match('/^\d{4}-\d{2}$/', $mes)) {
    die('Mes inválido.');
}

$datos = $Nomina->Search_Fide($mes);

[$anio, $numMes] = explode('-', $mes);
$meses = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio',
               'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
$nombreMes = $meses[(int)$numMes] . ' ' . $anio;

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Fideicomiso — <?php echo $nombreMes; ?></title>
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

        /* ── Título de sección ── */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #1a1a2e;
            border-left: 4px solid #c0392b;
            padding-left: 8px;
            margin: 14px 0 8px 0;
        }

        /* ── Tabla principal ── */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        .main-table thead tr {
            background-color: #c0392b;
            color: #ffffff;
        }
        .main-table th {
            padding: 6px 4px;
            text-align: center;
            font-size: 8.5px;
            font-weight: bold;
            border: none;
        }
        .main-table th.name-col { text-align: left; padding-left: 6px; }
        .main-table td {
            padding: 5px 4px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
            color: #374151;
        }
        .main-table td.name-col { text-align: left; padding-left: 6px; font-weight: 600; color: #16213e; }
        .main-table td.right { text-align: right; }
        .main-table tbody tr:nth-child(even) td { background-color: #fafafa; }
        .main-table tfoot td {
            background-color: #fff5f5;
            font-weight: bold;
            font-size: 9px;
            padding: 6px 4px;
            border-top: 2px solid #c0392b;
            text-align: center;
        }
        .main-table tfoot td.label { text-align: right; padding-right: 6px; color: #c0392b; }

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
                    <img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/PIUT_V1/IMG/Logo_Comple_Black.png"
                         class="logo">
                </td>
                <td>
                    <span class="company-name">DISORIENT, C.A.</span>
                    <div class="doc-title">
                        Reporte de Fideicomiso &mdash; <?php echo $nombreMes; ?>
                    </div>
                    <div style="font-size:9px; color:#9ca3af; margin-top:2px;">RIF: J-080199936</div>
                </td>
                <td class="header-right">
                    Fecha de emisión:<br>
                    <strong><?php echo date('d-m-Y'); ?></strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- ══ TABLA DE FIDEICOMISO ══ -->
    <div class="section-title">Detalle de fideicomiso — <?php echo $nombreMes; ?></div>

    <?php if (empty($datos)): ?>
        <p class="no-data">No hay registros de fideicomiso para <?php echo $nombreMes; ?>.</p>
    <?php else: ?>

    <table class="main-table">
        <thead>
            <tr>
                <th>Cédula</th>
                <th class="name-col">Nombre</th>
                <th>F. Ingreso</th>
                <th>Sueldo $</th>
                <th>T. Utilidad</th>
                <th>T. B. Vac.</th>
                <th>Alic. Util.</th>
                <th>Alic. B. Vac.</th>
                <th>S. Integral</th>
                <th>S. D. Integral</th>
                <th>Días Antig.</th>
                <th>Días Acum.</th>
                <th>Tot. Días</th>
                <th>Fideicomiso $</th>
                <th>Anticipo $</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sumaMonto    = 0;
            $sumaAnticipo = 0;
            foreach ($datos as $d):
                $sumaMonto    += (float)$d['monto'];
                $sumaAnticipo += (float)$d['anticipo'];
            ?>
            <tr>
                <td><?php echo htmlspecialchars($d['cedula']); ?></td>
                <td class="name-col"><?php echo htmlspecialchars($d['nombre'] . ' ' . $d['apellido']); ?></td>
                <td><?php echo htmlspecialchars($d['f_ingreso']); ?></td>
                <td><?php echo number_format((float)$d['sueldo'], 2); ?></td>
                <td><?php echo htmlspecialchars($d['tasa_utilidad']); ?></td>
                <td><?php echo htmlspecialchars($d['t_bonovacacional']); ?></td>
                <td><?php echo htmlspecialchars($d['a_utilidad']); ?></td>
                <td><?php echo htmlspecialchars($d['a_bonovacional']); ?></td>
                <td><?php echo number_format((float)$d['sueldo_integral'], 2); ?></td>
                <td><?php echo number_format((float)$d['sueldod_integral'], 4); ?></td>
                <td><?php echo htmlspecialchars($d['dias_antiguedad']); ?></td>
                <td><?php echo htmlspecialchars($d['dias_acumulados']); ?></td>
                <td><?php echo htmlspecialchars($d['total_dias']); ?></td>
                <td><?php echo number_format((float)$d['monto'], 2); ?></td>
                <td><?php echo number_format((float)$d['anticipo'], 2); ?></td>
                <td><?php echo htmlspecialchars($d['fecha']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="13" class="label">TOTALES</td>
                <td>$ <?php echo number_format($sumaMonto, 2); ?></td>
                <td>$ <?php echo number_format($sumaAnticipo, 2); ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <?php endif; ?>

    <!-- ══ FOOTER ══ -->
    <div class="footer">
        Generado el <?php echo date('d/m/Y'); ?> a las <?php echo date('H:i'); ?> &nbsp;|&nbsp; DISORIENT, C.A. &nbsp;|&nbsp; Sistema de Nómina
    </div>

</body>
</html>
<?php
$html = ob_get_clean();

require_once '../../PHP/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$dompdf = new Dompdf();
$options = $dompdf->getOptions();
$options->set(['isRemoteEnabled' => true]);
$dompdf->setOptions($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('Fideicomiso-' . $mes . '.pdf', ['Attachment' => false]);
