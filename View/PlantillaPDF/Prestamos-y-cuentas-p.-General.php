<?php 
session_start(); 

	if (!isset($_SESSION['user'])) {
		header('Location:index.php');}

include '../CLASS/conexion.php';
include '../CLASS/user.php'; 

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
					<img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/PIUT_V1/IMG/Logo_Comple_Black.png" style="height: 82px; margin-left: 4px; width: 80px;" class="left-image">
						<p class="disorient">DISORIENT, C.A.</p>
						<br>
						<br>
					<div class="encabezado">
						<p style="text-align: left;">J-080199936</p>
						<p style="text-align: center;">Relación de Prestamos y Cuentas por Pagar</p>
						<p style="text-align: right; padding-right: 75px;">Fecha:</p>
						<p style="text-align: right;"><?=date('d-m-Y')?></p>
					</div>

					
		


		</table>

		<table style="width: 100%; margin-top: -0.30%;">

			<tr style="background-color: lightGray;">

			<th>Nombres</th>
			<th>Apellidos</th>
			<th>Cedula</th>			
			<th>Fecha de solicitud</th>
			<th>Monto a descontar</th>
			<th>Cuotas</th>
			<th>Tipos</th>
			<th>Total</th>

			</tr>

			<tbody>
				<?php 
				$datos1 = $Nomina->Prestamos_View();
				foreach ($datos1 as $dato) {
					echo '<tr>';
					echo '<th scope="col">'	.$dato['nombre']. '</th>';
					echo '<th scope="col">'	.$dato['apellido']. '</th>';
					echo '<th scope="col">'	.$dato['cedula']. '</th>';
					echo '<th scope="col">' .$dato['fecha']. '</th>';
					echo '<th scope="col">'	.$dato['monto_desc']. ' $</th>';
					echo '<th scope="col">'	.$dato['cuotas']. '</th>';
					echo '<th scope="col">' .$dato['concepto']. '</th>';
					echo '<th scope="col">'	.$dato['descuento']. ' $</th>';
					echo '</tr>';
					}

				$datos2 = $Nomina->cuentas_por_pagar_View();
				foreach ($datos2 as $dato) {
					echo '<tr>';
					echo '<th scope="col">'	.$dato['nombre']. '</th>';
					echo '<th scope="col">'	.$dato['apellido']. '</th>';
					echo '<th scope="col">'	.$dato['cedula']. '</th>';
					echo '<th scope="col">' .$dato['fecha']. '</th>';
					echo '<th scope="col">'	.$dato['monto_desc']. ' $</th>';
					echo '<th scope="col">'	.$dato['cuotas']. '</th>';
					echo '<th scope="col">' .$dato['concepto']. '</th>';
					echo '<th scope="col">'	.$dato['descuento']. ' $</th>';
					echo '</tr>';
					}
					
				 ?>
			</tbody>
			
			


	
		</table>
	</body>
	</html>
<?php /* Este es el pie de pagina que tienes que copiar */

$html = ob_get_clean();


require_once '../dompdf/autoload.inc.php';
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










