<?php 
/*session_start(); 

	if (!isset($_SESSION['user'])) {
		header('Location:index.php');}*/

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

			table#tablados, th, td { 
				border: 1px solid black;
				text-align: center;
			}
			td{
				text-align: left;
				padding: .7rem;
				font-size: .7rem;
			}
			td#sueldo{
				text-align: right;
			}

	.left-image {
	    float: left;
	    margin-right: 10px; 
	  }

	  div#RIF {
	  	text-align: left;
	  }





		</style>
	</head>
	<body>
		<table style="width: 100%;">
			<tr>
				<th id="dir">
					<img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/PIUT_V1/IMG/Logo_Comple_Black.png" style="width: 70px;" class="left-image">
						<br>
						<br>
						<br>
						<br>
					<div>
						<br>
						<div id="RIF" style="float: left;"> RIF: J-080199936 </div>
					<div id="texto">
						<div style="float: right;">Fecha: <?php echo date("Y/m/d"); ?></div> 
						<div > RELACIÓN NÓMINA DEL PERSONAL </div>
					</div>
					<br>
					</div>

					
		


		</table>

		<table style="width: 100%;" id="tablados">

			<tr>

			<th>Nombre</th>
			<th>Cedula</th>			
			<th colspan="2">Teléfono</th>
			<th>Correo</th>
			<th>Dirección</th>
			<th>Fecha de ingreso</th>
			<th>Cargo</th>
			<th>Departamento</th>
			<th>Sueldo</th>

			</tr>

			<tbody>
				<?php 
					$datos = $Empleado->View();
					foreach ($datos as $dato) {
							echo '<tr>';
							echo '<td scope="col">'	.$dato['nombre']. ' ' .$dato['apellido'].'</td>';
							echo '<td scope="col">'	.$dato['cedula']. '</td>';
							echo '<td scope="col">'	.$dato['tlf']. '</td>';
							echo '<td scope="col">'	.$dato['second_tlf']. '</td>';
							echo '<td scope="col">'	.$dato['correo']. '</td>';
							echo '<td scope="col">'	.$dato['direccion']. '</td>';
							echo '<td scope="col">'	.$dato['f_ingreso']. '</td>';
							echo '<td scope="col">'	.$dato['cargo']. '</td>';
							echo '<td scope="col">'	.$dato['departamento']. '</td>';
							echo '<td scope="col" id="sueldo">'	.$dato['sueldo']. ' $</td>';
							echo '</tr>';
							}
							
						 ?>
			</tbody>

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