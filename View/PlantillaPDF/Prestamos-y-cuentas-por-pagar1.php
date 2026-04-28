<?php 
session_start(); 

	//if (!isset($_SESSION['user'])) {
	//	header('Location:index.php');}

	include '../../PHP/CLASS/conexion_Original.php';
	include '../../PHP/CLASS/user_Original.php'; 

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Cuenta pagada — Pr&eacute;stamos y Cuentas por Pagar</title>
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
			background: #1a1a2e;
			color: #ffffff;
			padding: 2px 8px;
			font-size: 9px;
			font-weight: bold;
			letter-spacing: 0.5px;
		}
		.badge-pagado { background: #15803d; }

		/* ── KPI cards ── */
		.kpi-table { width: 100%; border-collapse: separate; border-spacing: 6px; }
		.kpi-cell {
			width: 25%;
			background: #f8fafc;
			border: 1px solid #e2e8f0;
			padding: 10px 8px;
			text-align: center;
			vertical-align: middle;
		}
		.kpi-label { font-size: 9px; color: #6b7280; display: block; margin-bottom: 4px; }
		.kpi-value { font-size: 14px; font-weight: bold; color: #1a1a2e; display: block; }
		.kpi-sub   { font-size: 9px; color: #9ca3af; display: block; margin-top: 2px; }

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
			padding: 8px 6px;
			border-bottom: 1px solid #e5e7eb;
			font-size: 10px;
			color: #374151;
		}
		.detail-table tr.even td { background-color: #f9fafb; }
		.text-right { text-align: right; }
		.muted { color: #9ca3af; }
		.detail-table tfoot td {
			background-color: #f1f5f9;
			font-weight: bold;
			font-size: 10px;
			padding: 7px 6px;
			border-top: 2px solid #1a1a2e;
		}

		/* ── Fila de descripción / concepto ── */
		.concept-row td {
			background-color: #f1f5f9;
			font-size: 9px;
			color: #4b5563;
			padding: 6px 8px;
			border-bottom: 1px solid #e2e8f0;
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
	$data = $Nomina->Recibo($id);
}

$pagado = $data['monto_prestamo'] - $data['deuda'];
$pct    = $data['monto_prestamo'] > 0
        ? round(($pagado / $data['monto_prestamo']) * 100)
        : 0;
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
				<div class="doc-title">Cuenta pagada &mdash; Pr&eacute;stamos y Cuentas por Pagar <br> 
				J-080199936</div>
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
				<span class="badge badge-pagado">PAGADO</span>
				&nbsp;&nbsp;&bull;&nbsp;&nbsp;Tipo: <strong><?= htmlspecialchars($data['tipo_pago']) ?></strong>
				&nbsp;&nbsp;&bull;&nbsp;&nbsp;Ref: <strong><?= htmlspecialchars($data['refe']) ?></strong>
			</td>
			<td style="text-align:right; color:#6b7280;"> &nbsp;&nbsp;N&ordm; <strong><?= str_pad($data['id'], 9, '0', STR_PAD_LEFT) ?></strong> </td>
		</tr>
	</table>
</div>

<!-- ══ DATOS DEL EMPLEADO ══ -->
<div class="section-title" style="margin-top:18px;">Datos del trabajador</div>
<table class="emp-table">
	<tr>
		<td class="lbl">Nombre</td>
		<td><strong><?= htmlspecialchars($data['nombre'] . ' ' . $data['apellido']) ?></strong></td>
		<td class="lbl" style="width:130px;">Cargo</td>
		<td><?= htmlspecialchars($data['cargo']) ?></td>
	</tr>
	<tr class="even">
		<td class="lbl">C&eacute;dula</td>
		<td><?= htmlspecialchars($data['cedula']) ?></td>
		<td class="lbl">Fecha de solicitud</td>
		<td><?= date('d-m-Y', strtotime($data['fecha_solicitud'])) ?></td>
	</tr>
	<tr>
		<td class="lbl">Fecha del pago</td>
		<td><?= date('d-m-Y', strtotime($data['fecha'])) ?></td>
		<td class="lbl">Tasa BCV aplicada</td>
		<td><?= number_format($data['tasa'], 2, '.', ',') ?> Bs/$</td>
	</tr>
</table>

<!-- ══ TABLA DETALLE ══ -->
<div class="section-title" style="margin-top:18px;">Detalle del pr&eacute;stamo</div>
<table class="detail-table">
	<thead>
		<tr>
			<th>Concepto</th>
			<th class="text-right">Cuotas</th>
			<th class="text-right">Deuda anterior</th>
			<th class="text-right">Aporte ($)</th>
			<th class="text-right">Aporte (Bs.)</th>
			<th class="text-right">Deuda restante</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Pr&eacute;stamo (<?= $data['monto_prestamo'] ?> $)</td>
			<td class="text-right"><?= htmlspecialchars($data['cuotas']) ?> (semanal)</td>
			<td class="text-right"><?= number_format($data['deuda'] + $data['aporte'], 2, '.', ',') ?> $</td>
			<td class="text-right"><?= number_format($data['aporte'], 2, '.', ',') ?> $</td>
			<td class="text-right"><?= number_format($data['aporte'] * $data['tasa'], 2, '.', ',') ?> Bs.</td>
			<td class="text-right"><?= number_format($data['deuda'], 2, '.', ',') ?> $</td>
		</tr>
		<tr class="concept-row">
			<td colspan="6"><strong>Descripci&oacute;n:</strong> <?= htmlspecialchars($data['concepto'] ?? 'Ninguna') ?></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5" class="text-right">TASA BCV APLICADA</td>
			<td class="text-right"><?= number_format($data['tasa'], 2, '.', ',') ?> Bs/$</td>
		</tr>
	</tfoot>
</table>

<!-- ══ NETO ══ -->
<table class="neto-table">
	<tr>
		<td>
			<span class="neto-label">APORTE DEL PER&Iacute;ODO</span>
			<span class="neto-value"><?= number_format($data['aporte'] * $data['tasa'], 2, '.', ',') ?> Bs.</span>
		</td>
		<td style="text-align:right;">
			<span class="neto-label">DEUDA PENDIENTE</span>
			<span class="neto-value"><?= number_format($data['deuda'], 2, '.', ',') ?> $</span>
		</td>
	</tr>
</table>

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