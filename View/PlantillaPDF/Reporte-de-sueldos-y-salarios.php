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
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<title>Document</title>
		<style type="text/css">
			table,th{
				border:1px solid black;
				border-collapse: collapse; 
			}
			
			th#dir{
				padding: 0px;
				text-align: right;
			}
			
			div#texto{
				text-align: center;
			}
			
			th#datos{
				padding: 0px;
			}
			
			.row {
				display: flex;
				justify-content: space-between;
				margin-bottom: -140px;
			}

			table#tablados, th, td { 
				border: 1px solid black;
				text-align: center;
				margin-top: -0.25%;
			}
			
			td{
				padding: 5px
			}
			
			td {text-align: left;
			}

			th#NP{
				text-align: left;
			}

			th#Bs{
				text-align: left;
			}

			th#TT{
				text-align: left;
			}

			.left-image {
	    		float: left;
	    		margin-right: 10px; /* Añade un margen derecho para separar el texto de la imagen */
	  		}

			.fila-gruesa {
	    		height: 70px; 
	  		}

	 		.RIF {
	  			text-align: left;
	  			margin-bottom: -1px;
	  		}

			.encabezado {
				text-align: center;
				margin: auto;
				padding-right: 84px;
			}

			.recibo {
				text-align: center;
				margin: auto;
			}

		</style>
	</head>
	<body>
		<?php if ($_GET['id']) {
			$id = $_GET['id'];
			$dato = $Nomina->GetID_nomina($id);
		} ?>
		<table style="width: 100%">
			<tr>
				<th id="dir">
					<img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/PIUT_V1/IMG/Logo_Comple_Black.png" style="height: 70px; margin-left: 11px;" class="left-image">
					<p class="encabezado">DISORIENT, C.A.<br>
					Dir: Av. Cancamure, N°69, Cumaná-Edo.Sucre<br>
					Correo Electronico: disorientca@hotmail.com <br>
					Tlf: 0293-4315813</p> <br>
					<br>
					<br>
					<div style="overflow: auto;">
						<p class="RIF">J-080199936</p>
						<p class="recibo">Recibo de pago del trabajador</p>
						<br>
					</div>
				</th>
			</tr>
			<tr>
				<th>
						<div class="row">
							<p style="text-align: left;"> Nombres y Apellidos: <?=$dato['nombre']?> <?=$dato['apellido']?><br> 
							 Cédula: <?=$dato['cedula']?><br>
							 Fecha de ingreso: <?=date('d-m-Y',strtotime($dato['f_ingreso'])) ?><br>
							 Salario mensual: <?=$dato['sueldo'] * $dato['TasaBCV']?> Bs.<br>
							 Cargo: <?=$dato['cargo']?></p>
							 
							<p style="text-align: right; margin-right: 75px;"> Fecha: <br>
							 Nº:  <br> 							 

							 <p style="text-align: right;"> <?=date('d-m-Y', strtotime($dato['fecha']))?><br>
							 <?=str_pad($dato['id_nomina'], 9, '0', STR_PAD_LEFT)?>
							</p>
						</div>
				</th>
			</tr>

			

		

		</table>

		
		<table id="tablados" style="width: 100%">
			
			<tr>
				
				<th>Concepto</th>
				<th>%</th>
				<th>Asignaciones</th>
				<th>Deducciones</th>
		



			</tr>

			<tr>
				
				<td>Sueldo Semanal</td>
				<td></td>
				<td style="text-align: right;"><?=$dato['sueldosem'] * $dato['TasaBCV']?> $</td>
				<td></td>			
		

			</tr>

			<tr>
				
				<td>Bonificaciones</td>
				<td></td>
				<td style="text-align: right;"><?=$dato['bonificaciones'] * $dato['TasaBCV']?> $</td>
				<td></td>			
			

			</tr>


	<tr>
				
				<td> Comisiones </td>
				<td></td>
				<td style="text-align: right;"><?=$dato['comisiones'] *$dato['TasaBCV']?> $</td>
				<td></td>			
			

			</tr>

			<tr>
				
				<td>Prestamos y cuentas por pagar</td>
				<td></td>
				<td></td>			
				<td style="text-align: right;"> <?= number_format((-$dato['cpp'] - $dato['Ptm']) * $dato['TasaBCV'], 2, '.', '')?> $</td>
			

			</tr>
			<tr>
			    <th id="TT" colspan="2">Totales</th>
				<td style="text-align: right;"><?=($dato['sueldosem'] + $dato['bonificaciones'] + $dato['comisiones']) * $dato['TasaBCV']?> $</td>
				<td style="text-align: right;"> <?= number_format((-$dato['cpp'] - $dato['Ptm']) * $dato['TasaBCV'], 2, '.', '')?> $</td>
			</tr>
			<tr>
				<th id="NP" colspan="4">NETO PAGAR:  <?=$dato['netobs']?> Bs.</th>
		
			</tr>
			<tr>
				<th id="Bs" colspan="4">Monto en divisas: <?=$dato['neto']?> $</th>


			</tr>	

		</table>		


		<p>He recibido la cantidad de <?=$dato['netobs']?> Bs. Por la empresa DISORIENT, C.A.<br>
	   Correspondiente a mi semana de salario.</p>

				<p style="text-align: center; margin-right: 577px;">Firma Gerente General<br>
					DISORIENT, C.A.
				</p>
				<p style="text-align: center; margin-left: 594px; margin-top: -120px;">Firma del trabajador</p>
				<br>
				<br>

			
	</body>
	</html>
<?php /* Este es el pie de pagina que tienes que copiar */

$html = ob_get_clean();


require_once '../../PHP/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('letter');
// $dompdf->setPaper('A4','landscape');

$dompdf->render();
$dompdf->stream("Nomin_General.pdf", array("Attachment" => false));
 ?>