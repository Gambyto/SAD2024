<?php
/**
 * Fideicomiso-mes.php
 * PDF de fideicomiso filtrado por mes.
 * Recibe: ?mes=YYYY-MM
 * Ubicación: View/PlantillaPDF/Fideicomiso-mes.php
 */
session_start();
include '../../PHP/CLASS/conexion_Original.php';
include '../../PHP/CLASS/user_Original.php';

$mes = isset($_GET['mes']) ? trim($_GET['mes']) : date('Y-m');

// Validar formato YYYY-MM
if (!preg_match('/^\d{4}-\d{2}$/', $mes)) {
    die('Mes inválido.');
}

$datos = $Nomina->Search_Fide($mes);

// Nombre legible del mes
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
    <title>Fideicomiso <?php echo $nombreMes; ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid black; padding: 4px; text-align: center; }
        thead tr { background-color: lightgray; }
        td { text-align: left; }
        .encabezado-wrapper { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .logo { height: 70px; }
        .empresa { text-align: center; }
        .fecha-doc { text-align: right; font-size: 10px; }
        h3 { text-align: center; margin: 8px 0; }
        .totales td { font-weight: bold; background-color: #f0f0f0; }
    </style>
</head>
<body>

    <!-- Encabezado (mismo estilo que Fideicomiso.php original) -->
    <table style="width:100%; border:none;">
        <tr>
            <td style="border:none; width:80px;">
                <img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/PIUT_V1/IMG/Logo_Comple_Black.png"
                     style="height:70px; width:70px;">
            </td>
            <td style="border:none; text-align:center;">
                <strong>DISORIENT, C.A.</strong><br>
                J-080199936<br>
                <strong>FIDEICOMISO — <?php echo strtoupper($nombreMes); ?></strong>
            </td>
            <td style="border:none; text-align:right; font-size:10px;">
                Fecha: <?php echo date('d-m-Y'); ?>
            </td>
        </tr>
    </table>

    <br>

    <?php if (empty($datos)): ?>
        <p style="text-align:center;">No hay registros de fideicomiso para <?php echo $nombreMes; ?>.</p>
    <?php else: ?>

    <table>
        <thead>
            <tr style="background-color: lightgray;">
                <th>Cédula</th>
                <th>Nombre</th>
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
            $sumaMonto   = 0;
            $sumaAnticipo= 0;
            foreach ($datos as $d):
                $sumaMonto    += (float)$d['monto'];
                $sumaAnticipo += (float)$d['anticipo'];
            ?>
            <tr>
                <td><?php echo htmlspecialchars($d['cedula']); ?></td>
                <td><?php echo htmlspecialchars($d['nombre'] . ' ' . $d['apellido']); ?></td>
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
            <tr class="totales">
                <td colspan="13" style="text-align:right;">TOTALES</td>
                <td>$ <?php echo number_format($sumaMonto, 2); ?></td>
                <td>$ <?php echo number_format($sumaAnticipo, 2); ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <?php endif; ?>

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
