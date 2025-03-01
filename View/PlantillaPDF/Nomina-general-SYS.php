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

			th[colspan="2"] {
			width: 200px;
			text-align: left;
			padding: 10px;
			white-space: nowrap;
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

	.disorient {
		text-align: left;
		margin-top: 8px;
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
					<img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/PIUT_V1/IMG/Logo_Comple_Black.png" style="height: 77px; margin-left: 4px; width: 80px;" class="left-image">
						<br>
						<br>
						<br>
						<br>
					<div class="encabezado">
						<p style="text-align: left;">J-080199936</p>
						<p style="text-align: center;">RELACIÓN NÓMINA DEL PERSONAL</p>
						<p style="text-align: right; padding-right: 75px;">Fecha:</p>
						<p style="text-align: right;"><?=date('d-m-Y')?></p>
					</div>

					
		


		</table>

		<table style="width: 100%; margin-top: -0.30%;">

			<tr style="background-color: lightGray;">

			<th colspan="2">Nombre y apellido</th>
			<th>Cedula</th>			
			<th>Sueldo mensual</th>
			<th>Sueldo semanal</th>
			<th>Deducciones</th>
			<th>Asignación</th>
			<th>Neto a pagar</th>
			<th>Salario cobrar bs</th>

			<?php 		$faov = 0;
						$ince = 0;
						$islr = 0;
						$ivss = 0;
						$desc1 = 0;
						$desc2 = 0;
						$asig = 0;
						$neto = 0;
						$netobs = 0;
						$sueldo = 0;
						$sueldosem = 0; ?>


			</tr>

			<tbody>
				<?php 
					$datos = $Nomina->View_Nomina();
					foreach ($datos as $dato) {
						echo '<tr>';
						echo '<th scope="col" colspan="2">'	.$dato['nombre']. '  '.$dato['apellido']. '</th>';
						echo '<th scope="col">'	.$dato['cedula']. '</th>';
						echo '<th scope="col">'	.$dato['sueldo']. ' $</th>';
						echo '<th scope="col">'	.$dato['sueldosem']. ' $</th>';
						echo '<th scope="col">' .number_format($dato['desc1'] + $dato['desc2'],2). ' $</th>';
						echo '<th scope="col">' .$dato['asignaciones'].' $</th>';
						echo '<th scope="col">' .$dato['neto']. ' $</th>';
						echo '<th scope="col">'	.$dato['netobs']. ' Bs</th>';
						echo '</tr>';

						$desc1 += $dato['desc1'];
						$desc2 += $dato['desc2'];
						$asig += $dato['asignaciones'];
						$neto += $dato['neto'];
						$netobs += $dato['netobs'];
						$sueldo += $dato['sueldo'];
						$sueldosem += $dato['sueldosem'];
						}
								
						 ?>
				</tbody>

			<tr>
				<td colspan="3" style="text-align: center;">Totales</td>				
				<td><?=$sueldo?> $</td>
				<td><?=$sueldosem?> $</td>
				<td><?=number_format($desc1 + $desc2, 2)?> $</td>
				<td><?=$asig?> $</td>
				<td><?=$neto?> $</td>
				<td><?=$netobs?> Bs.</td>

			</tr>


	
		</table>

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

// $dompdf->setPaper('letter');
$dompdf->setPaper('A4','landscape');

$dompdf->render();
$dompdf->stream("Nomin_General.pdf", array("Attachment" => false));
 ?>
