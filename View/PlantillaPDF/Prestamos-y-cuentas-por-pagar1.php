<?php 
session_start(); 

	//if (!isset($_SESSION['user'])) {
	//	header('Location:index.php');}

	include '../../PHP/CLASS/conexion_Original.php';
	include '../../PHP/CLASS/user_Original.php'; 

ob_start();

/*   		Comentario para Sabastian

1- este es el encabezado qeu vas a a copiar en todos los diseños de pdf.

2- intenta mantener en código CSS lo más limpio posible, osea que lo que coloques en el CSS lo uses en realidad

3- intenta tener semantica HTML, ejmplo cuando uses una <table> hay etiquetas de semantica para esa etiqueta (<thead>, <tbody>)
*/
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

				text-align: center;



			}
			td{
				padding: 10px
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

	  div#RIF {

	  	text-align: left;
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

			.datosSolicitados{
				margin-left: 0.1%; 
				margin-top: -0.10%; 		 
				width: 720px;
				height: 45px;
				justify-content: space-between;
				display: flex;
		}

		</style>
	</head>
	<body>
		<?php if ($_GET['id']) {
			$id = $_GET['id'];
			$dato = $Nomina->GetID_Prestamos($id);
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
						<p class="recibo">Solicitud de Préstamo y/o Cuentas por Pagar</p>
						<br>
					</div>
				</th>
			</tr>

					
		

		</table>

		<table style="width: 100%; border: solid black 1px; margin-top: -0.15%;">
			<tr>
				<th>
					<div class="datosSolicitados";>
						<p style="text-align: left; padding-top: -35px;">Nombres y Apellidos: <?=$dato['nombre']?> <?=$dato['apellido']?><br>	
							Cédula: <?=$dato['cedula']?> <br>
							Cargo: <?=$dato['cargo']?><br>
							Fecha de solicitud: <?=date('d-m-Y',strtotime($dato['fecha']))?>
						</p>

						<p style="text-align: right; margin-right: 80px;"> Fecha: <br>
							Nº:
						</p> 							 

						<p style="text-align: right;"> <?=date('d-m-Y')?><br>
							0000000<?=$dato['id_prestamos']?>
						</p>
					</div>
				</th>	
			</tr>
		</table>


							





		</table>

		<table style="width: 100%; margin-top: -0.20%; ">
			
			<tr>
				
				<th style="width: 35%;">Concepto</th>
				<th style="width: 35%;">Cuotas</th>
				<th style="width: 35%;">Total</th>
		



			</tr>

			<tr>
				
				<td> Prestamo </td>
				<td> <?=$dato['cuotas']?> (semanal) </td>
				<td> <?=$dato['monto_desc']?> $</td>
		
		

			</tr>

			<tr>
				
				<td></td>
				<td></td>
				<td></td>
		

			</tr>


	<tr>
				
				<td></td>
				<td></td>
				<td></td>

			</tr>



			<tr>
				<th id="NP" colspan="3">Monto a descontar: <?=$dato['descuento']?> $</th>
		
			</tr>
			<tr>
				<th id="Bs" colspan="3">Bs: <?=$dato['descuento'] * $_SESSION['TasaBCV']?></th>


			</tr>



	  


		</table>
			<br>
			<p style="text-align: center; margin-right: 577px;">Firma Gerente General<br>
				DISORIENT, C.A.
			</p>
			<br>
			<br>
			<br>
			<p style="text-align: center; margin-left: 594px; margin-top: -114px;">Firma del trabajador</p>
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