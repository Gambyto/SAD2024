<?php 
/*session_start(); 

	if (!isset($_SESSION['user'])) {
		header('Location:index.php');}*/

include '../../PHP/CLASS/conexion_Original.php';
include '../../PHP/CLASS/user_Original.php'; 

ob_start();
?>
<!DOCTYPE html>	
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Nómina General</title>
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
            text-align: left;
            color: #374151;
        }
        .main-table td.right { text-align: right; }
        .main-table td.center { text-align: center; }
        .main-table tbody tr:nth-child(even) td { background-color: #fafafa; }

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
                        Relación de Personal &mdash; Nómina General
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

    <!-- ══ TABLA DE PERSONAL ══ -->
    <div class="section-title">Listado de empleados</div>

    <table class="main-table">
        <thead>
            <tr>
                <th>Nombre y Apellido</th>
                <th>Cédula</th>
                <th>Teléfono</th>
                <th>Teléfono 2</th>
                <th>Correo</th>
                <th>Dirección</th>
                <th>F. Ingreso</th>
                <th>Cargo</th>
                <th>Departamento</th>
                <th>Sueldo $</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $datos = $Empleado->View();
            foreach ($datos as $dato):
            ?>
            <tr>
                <td><?php echo htmlspecialchars($dato['nombre'] . ' ' . $dato['apellido']); ?></td>
                <td class="center"><?php echo htmlspecialchars($dato['cedula']); ?></td>
                <td class="center"><?php echo htmlspecialchars($dato['tlf']); ?></td>
                <td class="center"><?php echo htmlspecialchars($dato['second_tlf']); ?></td>
                <td><?php echo htmlspecialchars($dato['correo']); ?></td>
                <td><?php echo htmlspecialchars($dato['direccion']); ?></td>
                <td class="center"><?php echo htmlspecialchars($dato['f_ingreso']); ?></td>
                <td><?php echo htmlspecialchars($dato['cargo']); ?></td>
                <td><?php echo htmlspecialchars($dato['departamento']); ?></td>
                <td class="right"><?php echo htmlspecialchars($dato['sueldo']); ?> $</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

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
$dompdf->stream("Nomin_General.pdf", ['Attachment' => false]);
