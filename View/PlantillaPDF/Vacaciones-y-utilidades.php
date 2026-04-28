<?php 
session_start(); 

	include '../../PHP/CLASS/conexion_Original.php';
	include '../../PHP/CLASS/user_Original.php'; 

ob_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Recibo de Pago — Vacaciones y Utilidades</title>
	<style>
		* { box-sizing: border-box; margin: 0; padding: 0; }
		body { font-family: Arial, sans-serif; font-size: 11px; color: #1a1a1a; }

		/* ── Encabezado corporativo ── */
		.header-wrap {
			width: 100%;
			border-bottom: 2px solid #1a1a2e;
			padding-bottom: 8px;
			margin-bottom: 1px;
		}
		.header-table { width: 100%; border: none; border-collapse: collapse; }
		.header-table td { border: none; vertical-align: middle; }
		.logo { width: 70px; height: 70px; }
		.company-name { font-size: 15px; font-weight: bold; color: #1a1a2e; letter-spacing: 0.5px; }
		.doc-title { font-size: 11px; color: #4b5563; margin-top: 3px; }
		.header-right { text-align: right; font-size: 10px; color: #6b7280; }

		/* ── Sección título ── */
		.section-title {
			font-size: 12px;
			font-weight: bold;
			color: #1a1a2e;
			border-left: 4px solid #1a1a2e;
			padding-left: 8px;
			margin: 14px 0 8px 0;
		}

		/* ── Strip de recibo ── */
		.recibo-strip {
			background: #f1f5f9;
			border: 1px solid #e2e8f0;
			padding: 6px 10px;
			margin-bottom: 14px;
		}
		.recibo-strip-inner { width: 100%; border-collapse: collapse; }
		.recibo-strip-inner td { border: none; vertical-align: middle; font-size: 10px; color: #374151; }
		.badge {
			color: #000000;
			padding: 2px 8px;
			font-size: 9px;
			font-weight: bold;
			letter-spacing: 0.5px;
		}

		/* ── Tabla empleado ── */
		.emp-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
		.emp-table td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; font-size: 10px; color: #374151; }
		.emp-table td.lbl { color: #6b7280; width: 160px; }
		.emp-table tr.even td { background-color: #f9fafb; }

		/* ── Tabla detalle ── */
		.detail-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
		.detail-table thead tr { background-color: #1a1a2e; color: #ffffff; }
		.detail-table th {
			padding: 7px 6px;
			text-align: left;
			font-size: 10px;
			font-weight: bold;
			border: none;
			color: #ffffff;
		}
		.detail-table td {
			padding: 6px 6px;
			border-bottom: 1px solid #e5e7eb;
			font-size: 10px;
			color: #374151;
		}
		.detail-table tr.even td { background-color: #f9fafb; }
		.text-right { text-align: right; }
		.muted { color: #9ca3af; }

		/* sub-sección dentro del tbody */
		.sub-header td {
			background-color: #e8edf4;
			font-weight: bold;
			font-size: 9px;
			color: #1a1a2e;
			padding: 5px 6px;
			border-bottom: 1px solid #c8d0dc;
		}

		.detail-table tfoot td {
			background-color: #f1f5f9;
			font-weight: bold;
			font-size: 10px;
			padding: 7px 6px;
			border-top: 2px solid #1a1a2e;
		}

		/* ── Neto ── */
		.neto-table { width: 100%; border-collapse: collapse; background-color: #1a1a2e; }
		.neto-table td { padding: 10px 14px; border: none; vertical-align: middle; }
		.neto-label { font-size: 9px; color: #9ca3af; display: block; }
		.neto-value { font-size: 16px; font-weight: bold; color: #ffffff; display: block; }

		/* ── Firmas ── */
		.firmas-table { width: 100%; border-collapse: collapse; margin-top: 30px; }
		.firmas-table td { width: 50%; text-align: center; padding: 0 20px; border: none; }
		.firma-line { border-top: 1px solid #1a1a2e; padding-top: 6px; font-size: 10px; color: #374151; }

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

<?php
if ($_GET['id']) {
	$id   = $_GET['id'];
	$dato = $Nomina->GetID_Vacation($id);

	$sueldo_parse        = $dato['sueldo'] * 0.33;
	$dato['sueldo_diario'] = $sueldo_parse / 30;
}

$sd         = $dato['sueldo_diario'];
$tasa       = $dato['tasa'];
$totalDias  = $dato['utilidades']
            + (2 * $dato['dia_correspondido'])
            + $dato['dia_descanso']
            + $dato['dia_feriado']
            + $dato['dia_otorgado'];
$totalAsig  = $sd * $totalDias * $tasa;
$totalDed   = $dato['ince'] * $tasa;
?>

<!-- ══ ENCABEZADO CORPORATIVO ══ -->
<div class="header-wrap">
	<table class="header-table">
		<tr>
			<td style="width:80px;">
				<img src="http://<?= $_SERVER['HTTP_HOST'] ?>/PIUT_V1/IMG/Logo_Comple_Black.png" class="logo">
			</td>
			<td>
				<span class="company-name">DISORIENT, C.A.</span>
				<div class="doc-title">Recibo de pago de Vacaciones y Utilidades <br> J-080199936 </div>
				
			</td>
			<td class="header-right">
				Fecha de emisión:<br>
				<strong><?= date('d-m-Y') ?></strong>
			</td>
		</tr>
	</table>
</div>

<!-- ══ STRIP RECIBO ══ -->
<div class="recibo-strip">
	<table class="recibo-strip-inner">
		<tr>
			<td>
				<span class="badge section-title">RECIBO DE VACACIONES</span>
				
			</td>
			<td style="text-align:right;">&nbsp;&nbsp;N&ordm; <strong>0000000<?= $dato['vacaciones_id'] ?></strong></td>
		</tr>
	</table>
</div>

<!-- ══ DATOS DEL EMPLEADO ══ -->
<div class="section-title" style="margin-top:18px;">Datos del trabajador</div>
<table class="emp-table">
	<tr>
		<td class="lbl">Nombres y apellidos</td>
		<td><strong><?= htmlspecialchars($dato['nombre'] . ' ' . $dato['apellido']) ?></strong></td>
		<td class="lbl" style="width:140px;">C&eacute;dula</td>
		<td><?= htmlspecialchars($dato['cedula']) ?></td>
	</tr>
	<tr class="even">
		<td class="lbl">Fecha de ingreso</td>
		<td><?= htmlspecialchars($dato['f_ingreso']) ?></td>
		<td class="lbl">Tiempo de servicio</td>
		<td><?= htmlspecialchars($dato['t_servicio']) ?> a&ntilde;o(s)</td>
	</tr>
	<tr>
		<td class="lbl">Inicio de vacaciones</td>
		<td><?= htmlspecialchars($dato['ini_vacaciones']) ?></td>
		<td class="lbl">Culminaci&oacute;n</td>
		<td><?= htmlspecialchars($dato['fin_vacaciones']) ?></td>
	</tr>
	<tr class="even">
		<td class="lbl">Inicio de labores</td>
		<td><?= htmlspecialchars($dato['ini_laboral']) ?></td>
		<td class="lbl"></td>
		<td></td>
	</tr>
</table>

<!-- ══ TABLA DETALLE ══ -->
<div class="section-title" style="margin-top:18px;">Detalle de pago</div>
<table class="detail-table">
	<thead>
		<tr>
			<th>Concepto</th>
			<th class="text-right">D&iacute;as</th>
			<th class="text-right">Salario Diario (Bs.)</th>
			<th class="text-right">Monto (Bs.)</th>
			<th class="text-right">Deducciones (Bs.)</th>
		</tr>
	</thead>
	<tbody>
		<tr class="sub-header"><td colspan="5">VACACIONES</td></tr>
		<tr>
			<td>Vacaciones</td>
			<td class="text-right"><?= $dato['dia_correspondido'] ?></td>
			<td class="text-right"><?= number_format($sd * $tasa, 2, '.', ',') ?></td>
			<td class="text-right"><?= number_format($sd * $dato['dia_correspondido'] * $tasa, 2, '.', ',') ?></td>
			<td class="text-right muted">&mdash;</td>
		</tr>
		<tr class="even">
			<td>Bono Vacacional</td>
			<td class="text-right"><?= $dato['dia_correspondido'] ?></td>
			<td class="text-right"><?= number_format($sd * $tasa, 2, '.', ',') ?></td>
			<td class="text-right"><?= number_format($sd * $dato['dia_correspondido'] * $tasa, 2, '.', ',') ?></td>
			<td class="text-right muted">&mdash;</td>
		</tr>
		<tr>
			<td>D&iacute;as de descanso</td>
			<td class="text-right"><?= $dato['dia_descanso'] ?></td>
			<td class="text-right"><?= number_format($sd * $tasa, 2, '.', ',') ?></td>
			<td class="text-right"><?= number_format($sd * $dato['dia_descanso'] * $tasa, 2, '.', ',') ?></td>
			<td class="text-right muted">&mdash;</td>
		</tr>
		<tr class="even">
			<td>D&iacute;as feriados</td>
			<td class="text-right"><?= $dato['dia_feriado'] ?></td>
			<td class="text-right"><?= number_format($sd * $tasa, 2, '.', ',') ?></td>
			<td class="text-right"><?= number_format($sd * $dato['dia_feriado'] * $tasa, 2, '.', ',') ?></td>
			<td class="text-right muted">&mdash;</td>
		</tr>
		<tr>
			<td>D&iacute;as pendientes</td>
			<td class="text-right"><?= $dato['dia_otorgado'] ?></td>
			<td class="text-right"><?= number_format($sd * $tasa, 2, '.', ',') ?></td>
			<td class="text-right"><?= number_format($sd * $dato['dia_otorgado'] * $tasa, 2, '.', ',') ?></td>
			<td class="text-right muted">&mdash;</td>
		</tr>
		<tr class="sub-header"><td colspan="5">UTILIDADES</td></tr>
		<tr class="even">
			<td>D&iacute;as utilidades</td>
			<td class="text-right"><?= $dato['utilidades'] ?></td>
			<td class="text-right"><?= number_format($sd * $tasa, 2, '.', ',') ?></td>
			<td class="text-right"><?= number_format($sd * $dato['utilidades'] * $tasa, 2, '.', ',') ?></td>
			<td class="text-right muted">&mdash;</td>
		</tr>
		<tr>
			<td>INCE</td>
			<td class="text-right muted">&mdash;</td>
			<td class="text-right muted">&mdash;</td>
			<td class="text-right muted">&mdash;</td>
			<td class="text-right" style="color:#dc2626;"><?= number_format(-$dato['ince'] * $tasa, 2, '.', ',') ?></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3" class="text-right">TOTALES</td>
			<td class="text-right"><?= number_format($totalAsig, 2, '.', ',') ?></td>
			<td class="text-right"><?= number_format(-$totalDed, 2, '.', ',') ?></td>
		</tr>
	</tfoot>
</table>

<!-- ══ NETO ══ -->
<table class="neto-table">
	<tr>
		<td>
			<span class="neto-label">NETO A PAGAR</span>
			<span class="neto-value"><?= number_format($dato['monto'] * $tasa, 2, '.', ',') ?> Bs.</span>
		</td>
		<td style="text-align:right;">
			<span class="neto-label">NETO EN DIVISAS</span>
			<span class="neto-value"><?= number_format($dato['monto'], 2, '.', ',') ?> $</span>
		</td>
	</tr>
</table>

<!-- ══ DECLARACIÓN ══ -->
<p style="font-size:10px; color:#374151; margin-top:14px; line-height:1.6;">
	Recib&iacute; conforme: <strong><?= number_format($dato['monto'] * $tasa, 2, '.', ',') ?> Bs.</strong>
	Calculado a la tasa del Banco Central de Venezuela del <?= date('d-m-Y', strtotime($dato['ini_vacaciones'])) ?>
	establecida en <?= $tasa ?> Bs.
</p>

<!-- ══ FIRMAS ══ -->
<table class="firmas-table">
	<tr>
		<td><div style="height:44px; margin-bottom:11px;"></div><div class="firma-line">Firma Gerente General<br><strong>DISORIENT, C.A.</strong></div></td>
		<td><div style="height:44px;"></div><div class="firma-line">Firma del Trabajador</div></td>
	</tr>
</table>

<!-- ══ FOOTER ══ -->
<div class="footer">
	Generado el <?= date('d/m/Y') ?> a las <?= date('H:i') ?> &nbsp;|&nbsp; DISORIENT, C.A. &nbsp;|&nbsp; Sistema de N&oacute;mina
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
$dompdf->setPaper('letter');
$dompdf->render();
$dompdf->stream('Nomin_General.pdf', ['Attachment' => false]);
?>