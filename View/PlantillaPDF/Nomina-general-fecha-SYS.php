<?php
session_start();

//if (!isset($_SESSION['user'])) {
//    header('Location:index.php'); }

include '../../PHP/CLASS/conexion_Original.php';
include '../../PHP/CLASS/user_Original.php';

$semana = isset($_GET['semana']) ? (int)$_GET['semana'] : null;
$anio   = isset($_GET['anio'])   ? (int)$_GET['anio']   : null;

if (!$semana || !$anio) {
    die('Semana o año no especificados.');
}

$lunes   = new DateTime();
$lunes->setISODate($anio, $semana, 1);
$domingo = clone $lunes;
$domingo->modify('+6 days');

$fechaMostrar  = $lunes->format('d/m/Y') . ' — ' . $domingo->format('d/m/Y');
$nombreArchivo = 'Nomina_Semana_' . $semana . '_' . $anio . '.pdf';

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nómina General — Semana <?= $semana ?>/<?= $anio ?></title>
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
            font-size: 9.5px;
        }
        .main-table thead tr {
            background-color: #c0392b;
            color: #ffffff;
        }
        .main-table th {
            padding: 6px 5px;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            border: none;
        }
        .main-table td {
            padding: 5px 5px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
            color: #374151;
        }
        .main-table td.name-col { text-align: left; font-weight: 600; color: #16213e; }
        .main-table tbody tr:nth-child(even) td { background-color: #fafafa; }
        .main-table tfoot td {
            background-color: #fff5f5;
            font-weight: bold;
            font-size: 9.5px;
            padding: 6px 5px;
            border-top: 2px solid #c0392b;
            text-align: center;
        }
        .main-table tfoot td.name-col { text-align: right; padding-right: 6px; }

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
                        Relación Nómina del Personal &mdash; Semana <?= $semana ?> / <?= $anio ?>
                    </div>
                    <div style="font-size:9px; color:#9ca3af; margin-top:2px;">
                        RIF: J-080199936 &nbsp;|&nbsp; <?= htmlspecialchars($fechaMostrar) ?>
                    </div>
                </td>
                <td class="header-right">
                    Fecha de emisión:<br>
                    <strong><?= date('d-m-Y') ?></strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- ══ TABLA DE NÓMINA ══ -->
    <div class="section-title">Detalle de nómina — Semana <?= $semana ?></div>

    <table class="main-table">
        <thead>
            <tr>
                <th colspan="2" style="text-align:left; padding-left:6px;">Nombre y Apellido</th>
                <th>Cédula</th>
                <th>Sueldo mensual $</th>
                <th>Sueldo semanal $</th>
                <th>Deducciones $</th>
                <th>Asignaciones $</th>
                <th>Neto a pagar $</th>
                <th>Salario a cobrar Bs</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $desc1 = 0; $desc2 = 0;
        $asig  = 0; $neto  = 0;
        $netobs = 0; $sueldo = 0; $sueldosem = 0;

        $datos = $Nomina->Search_Nomina_Semana($semana, $anio);

        if (empty($datos)) {
            echo '<tr><td colspan="9" style="text-align:center; padding:20px; color:#9ca3af; font-style:italic;">No se encontraron registros para esta semana.</td></tr>';
        } else {
            foreach ($datos as $dato):
                $desc1     += $dato['desc1'];
                $desc2     += $dato['desc2'];
                $asig      += $dato['asignaciones'];
                $neto      += $dato['neto'];
                $netobs    += $dato['netobs'];
                $sueldo    += $dato['sueldo'];
                $sueldosem += $dato['sueldosem'];
        ?>
            <tr>
                <td colspan="2" class="name-col"><?= htmlspecialchars($dato['nombre'] . '  ' . $dato['apellido']) ?></td>
                <td><?= htmlspecialchars($dato['cedula']) ?></td>
                <td><?= $dato['sueldo'] ?> $</td>
                <td><?= $dato['sueldosem'] ?> $</td>
                <td><?= number_format($dato['desc1'] + $dato['desc2'], 2) ?> $</td>
                <td><?= $dato['asignaciones'] ?> $</td>
                <td><?= $dato['neto'] ?> $</td>
                <td><?= $dato['netobs'] ?> Bs</td>
            </tr>
        <?php
            endforeach;
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="name-col">TOTALES</td>
                <td><?= $sueldo ?> $</td>
                <td><?= $sueldosem ?> $</td>
                <td><?= number_format($desc1 + $desc2, 2) ?> $</td>
                <td><?= $asig ?> $</td>
                <td><?= $neto ?> $</td>
                <td><?= $netobs ?> Bs.</td>
            </tr>
        </tfoot>
    </table>

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
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream($nombreArchivo, ['Attachment' => false]);
