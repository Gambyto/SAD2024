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
$lunes->setISODate($anio, $semana, 1); // 1 = lunes
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
    <title>Nómina General - <?= htmlspecialchars($fechaMostrar) ?></title>
    <style type="text/css">
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th#dir {
            padding: 0px;
            text-align: right;
        }
        .row {
            display: flex;
            justify-content: space-between;
        }
        td {
            padding: 10px;
            text-align: left;
        }
        th[colspan="2"] {
            width: 200px;
            text-align: left;
            padding: 10px;
            white-space: nowrap;
        }
        .left-image {
            float: left;
            margin-right: 10px;
        }
        .encabezado {
            display: flex;
            text-align: center;
            margin-top: 100px;
            margin-bottom: -220px;
        }
    </style>
</head>
<body>

    <table style="width: 100%">
        <tr>
            <th id="dir">
                <img src="http://<?= $_SERVER['HTTP_HOST'] ?>/PIUT_V1/IMG/Logo_Comple_Black.png"
                     style="height: 77px; margin-left: 4px; width: 80px;" class="left-image">
                <br><br><br><br>
                <div class="encabezado">
                    <p style="text-align: left;">J-080199936</p>
                    <p style="text-align: center;">RELACIÓN NÓMINA DEL PERSONAL</p>
                    <p style="text-align: right; padding-right: 180px;">Semana <?= $semana ?> / <?= $anio ?>:</p>
                    <p style="text-align: right; padding-right: 10px;"><?= htmlspecialchars($fechaMostrar) ?></p>
                </div>
            </th>
        </tr>
    </table>

    <table style="width: 100%; margin-top: -0.30%;">
        <tr style="background-color: lightGray;">
            <th colspan="2">Nombre y apellido</th>
            <th>Cédula</th>
            <th>Sueldo mensual</th>
            <th>Sueldo semanal</th>
            <th>Deducciones</th>
            <th>Asignación</th>
            <th>Neto a pagar</th>
            <th>Salario cobrar Bs</th>
        </tr>

        <?php
        $desc1 = 0; $desc2 = 0;
        $asig  = 0; $neto  = 0;
        $netobs = 0; $sueldo = 0; $sueldosem = 0;

        // Nuevo método que filtra por semana y año
        $datos = $Nomina->Search_Nomina_Semana($semana, $anio);

        if (empty($datos)) {
            echo '<tr><td colspan="9" style="text-align:center; padding:20px;">No se encontraron registros para esta semana.</td></tr>';
        } else {
            foreach ($datos as $dato) {
                echo '<tr>';
                echo '<th colspan="2">' . htmlspecialchars($dato['nombre'] . '  ' . $dato['apellido']) . '</th>';
                echo '<th>'             . htmlspecialchars($dato['cedula'])                             . '</th>';
                echo '<th>'             . $dato['sueldo']                                              . ' $</th>';
                echo '<th>'             . $dato['sueldosem']                                           . ' $</th>';
                echo '<th>'             . number_format($dato['desc1'] + $dato['desc2'], 2)            . ' $</th>';
                echo '<th>'             . $dato['asignaciones']                                        . ' $</th>';
                echo '<th>'             . $dato['neto']                                                . ' $</th>';
                echo '<th>'             . $dato['netobs']                                              . ' Bs</th>';
                echo '</tr>';

                $desc1     += $dato['desc1'];
                $desc2     += $dato['desc2'];
                $asig      += $dato['asignaciones'];
                $neto      += $dato['neto'];
                $netobs    += $dato['netobs'];
                $sueldo    += $dato['sueldo'];
                $sueldosem += $dato['sueldosem'];
            }
        }
        ?>

        <tr style="background-color: #f0f0f0; font-weight: bold;">
            <td colspan="3" style="text-align: center;">Totales</td>
            <td><?= $sueldo ?>    $</td>
            <td><?= $sueldosem ?> $</td>
            <td><?= number_format($desc1 + $desc2, 2) ?> $</td>
            <td><?= $asig ?>      $</td>
            <td><?= $neto ?>      $</td>
            <td><?= $netobs ?>    Bs.</td>
        </tr>
    </table>

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