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
			}

			table#tablados, th, td { border: 1px solid black;
				margin-top: -0.25%;
				text-align: center;



			}
			td{
				padding: 5px
			}
			td {text-align: left;}

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
	  	margin-bottom: -2px;
	  	text-align: left;
	}

	.encabezado {
		text-align: center;
		margin: auto;
		padding-right: 83px;
	}
	
	.recibo {
		text-align: center;
		margin-bottom: -30px;
		margin-top: -5px;
	}

	.datosSolicitados{
		margin-left: 0.1%; 
		margin-top: -0.10%; 		 
		width: 720px;
		height: 120px;
		justify-content: space-between;
		display: flex;
	}

		</style>
	</head>
	<body>
		<table style="width: 100%; margin-top: 1%">
			<tr>
				<th id="dir">
					<img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/PIUT_V1/IMG/Logo_Comple_Black.png" style="height: 73px; margin-left: 10px;" class="left-image">
					<p class="encabezado">DISORIENT, C.A.<br>
					Dir: Av. Cancamure, N°69, Cumaná-Edo.Sucre<br>
					Correo Electronico: disorientca@hotmail.com <br>
					Tlf: 0293-4315813</p> <br>
					<br>
					<br>
					<div style="overflow: auto;">
						<p class="RIF">J-080199936</p>
						<p class="recibo">Recibo de pago del trabajador<p>
					</div>
		</table>
		<?php if ($_GET['id']) {
			$id = $_GET['id'];
			$dato = $Nomina->GetID_Vacation($id);
		} ?>
		<table style="width: 100%; border: solid black 1px;">
			<tr>
				<th>
					<div class="datosSolicitados";>
						<p style="text-align: left; padding-top: -35px;">Nombres y apellidos: <?=$dato['nombre']?> <?=$dato['apellido']?><br>	
							Cédula: <?=$dato['cedula']?> <br>
							Fecha de ingreso: <?=$dato['f_ingreso']?> <br>
							Tiempo de servicio: <?=$dato['t_servicio']?> años<br>
							Sueldo mensual: <?=$dato['sueldo']?> $<br>
							Inicio de vacaciones: <?=$dato['ini_vacaciones']?> <br>
							Culminación de vacaciones: <?=$dato['fin_vacaciones']?> <br>
							Inicio de labores: <?=$dato['ini_laboral']?> 
						</p>

						<p style="text-align: right; margin-right: 80px;"> Fecha:<br>
							Nº:
						</p> 							 

						<p style="text-align: right;"> <?=date('d-m-Y')?><br>
							0000000<?=$dato['vacaciones_id']?>
						</p>
					</div>
				</th>	
			</tr>
		</table>
		
		<table id="tablados" style="width: 100%; margin-top: -0.20%">
			
			<tr>
				
				<th>Concepto</th>
				<th>Días</th>
				<th>Salario Diario</th>
				<th>Monto</th>
				<th>Deducciones</th>
		



			</tr>

			<tr>
				
				<td>Vacaciones</td>
				<td style="text-align: right;"><?=$dato['dia_correspondido']?></td>
				<td style="text-align: right;"><?=$dato['sueldo_diario']?> $</td>
				<td style="text-align: right;"><?=$dato['sueldo_diario'] * $dato['dia_correspondido']?> $</td>			
				<td></td>		
		

			</tr>

			<tr>
				
				<td>Bono Vacacional</td>
				<td style="text-align: right;"><?=$dato['dia_correspondido']?></td>
				<td style="text-align: right;"><?=$dato['sueldo_diario']?> $</td>
				<td style="text-align: right;"><?=$dato['sueldo_diario'] * $dato['dia_correspondido']?> $</td>		
				<td></td>		

			</tr>


	<tr>
				
				<td>Días descanso</td>
				<td style="text-align: right;"><?=$dato['dia_descanso']?></td>
				<td style="text-align: right;"><?=$dato['sueldo_diario']?> $</td>
				<td style="text-align: right;"><?=$dato['sueldo_diario'] * $dato['dia_descanso']?> $</td>			
				<td></td>		

			</tr>


	<tr>
				
				<td>Días feriados</td>
				<td style="text-align: right;"><?=$dato['dia_feriado']?></td>
				<td style="text-align: right;"><?=$dato['sueldo_diario']?> $</td>
				<td style="text-align: right;"><?=$dato['sueldo_diario'] * $dato['dia_feriado']?> $</td>		
				<td></td>		

			</tr>


	<tr>
				
				<td>Días pendientes</td>
				<td style="text-align: right;"><?=$dato['dia_otorgado']?></td>
				<td style="text-align: right;"><?=$dato['sueldo_diario']?> $</td>
				<td style="text-align: right;"><?=$dato['sueldo_diario'] * $dato['dia_otorgado']?> $</td>			
				<td></td>		

			</tr>
			<tr>
				
				<th>Utilidades</th>
				<td></td>
				<td></td>
				<td></td>			
				<td></td>

			</tr>

	<tr>
				
				<td>Días utilidades</td>
				<td style="text-align: right;"><?=$dato['utilidades']?></td>
				<td style="text-align: right;"><?=$dato['sueldo_diario']?> $</td>
				<td style="text-align: right;"><?=$dato['sueldo_diario'] * $dato['utilidades']?> $</td>		
				<td></td>		

			</tr>

	<tr>
				
				<td></td>
				<td></td>
				<td></td>
				<td></td>		
				<td style="text-align: right;"> - <?=$dato['ince']?></td>			

			</tr>


			<tr>
			    <th id="TT" colspan="3">Totales</th>
				<td style="text-align: right;"> <?=$dato['sueldo_diario'] * ($dato['utilidades'] + ( 2 * $dato['dia_correspondido']) +  $dato['dia_descanso'] + $dato['dia_feriado'] + $dato['dia_otorgado'])?> $</td>
				<td style="text-align: right;">  <?=-$dato['ince']?> $</td>
			</tr>
			<tr>
				<th id="NP" colspan="5">NETO PAGAR:  <?=$dato['monto']?>$</th>
		
			</tr>
			<tr>
				<th id="Bs" colspan="5">Bs: <?=number_format($dato['monto'] * $dato['tasa'],2)?> Bs.</th>


			</tr>

		</table>

		<p>Recibí conforme: <?=number_format($dato['monto'] * $dato['tasa'],2)?> Bs.</p>

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