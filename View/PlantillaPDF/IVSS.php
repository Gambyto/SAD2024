<?php 
session_start(); 

	if (!isset($_SESSION['user'])) {
		header('Location:index.php');}

include '../CLASS/conexion.php';
include '../CLASS/user.php'; 

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
			
			.row {
				display: flex;
				justify-content: space-between;
			}

			table#tablados, th, td { border: 1px solid black;

				text-align: center;

			}
			td{
				padding: 5px
			}
			td {text-align: left;
				font-size: 13px;}

			

			

			

			.left-image {
	    float: left;
	    margin-right: 10px; 
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
		<table style="width: 100%;">
			<tr style="border:none;">
				<th id="dir">
					<img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/PIUT_V1/IMG/Logo_Comple_Black.png" style="height: 82px; margin-left: 4px; width: 80px;" class="left-image">
						<p class="disorient">DISORIENT, C.A.</p>
						<br>
						<br>
					<div class="encabezado">
						<p style="text-align: left;">J-080199936</p>
						<p style="text-align: center;">REGISTRO PATRONAL DE ASEGURADOS IVSS</p>
						<p style="text-align: right; padding-right: 75px;">Fecha:</p>
						<p style="text-align: right;"><?=date('d-m-Y')?></p>
					</div>

		</table>

		<table style="width: 100%; margin-top: -0.30%;">

			<tr style="background-color: lightGray;">

			<th rowspan="2"; style="height: 50px;">Nombres</th>
			<th rowspan="2"; style="height: 50px;">Apellidos</th>
			<th rowspan="2"; style="height: 50px;">Cedula</th>			
			<th rowspan="2"; style="height: 50px;">Fechas de ingreso</th>
			<th colspan="3" style="width: 200px; height: 50px;">Sueldos y salarios</th>
			<th rowspan="2"; style="height: 50px;">Cotización semanal trabajador</th>
			<th rowspan="2"; style="height: 50px;">Aporte semanal empleador</th>
			<th rowspan="2"; style="height: 50px;">Totales aportes</th>
			<th rowspan="2"; style="height: 50px;">Cotización semanal trabajador R.P.E</th>
			<th rowspan="2"; style="height: 50px;">Cotización semanal empleador R.P.E</th>
			<th rowspan="2"; style="height: 50px;">Totales aportes R.P.E</th>




			</tr>

			<tr style="background-color: lightGray;">
				<td style="height: -50%;">Diario</td>				
				<td style="height: -50%;">Semanal</td>
				<td style="height: -50%;">Mensual</td>								
			</tr>
			
	
			<tbody>
				<?php 
				$datos = $Nomina->IVSS_View();
				foreach ($datos as $dato) {
					echo '<tr>';
					echo '<td scope="col">'	.$dato['nombre']. '</td>';
					echo '<td scope="col">'	.$dato['apellido']. '</td>';
					echo '<td scope="col">'	.$dato['cedula']. '</td>';
					echo '<td scope="col">'	.$dato['f_ingreso']. '</td>';
					echo '<td scope="col">'	.$dato['sueldoD']. '</td>';
					echo '<td scope="col">'	.$dato['sueldosem']. '</td>';
					echo '<td scope="col">'	.$dato['sueldo']. '</td>';
					echo '<td scope="col">'	.$dato['aporte_tbj']. '</td>';
					echo '<td scope="col">'	.$dato['aporte_emp']. '</td>';
					echo '<td scope="col">'	.$dato['t_aporte']. '</td>';
					echo '<td scope="col">'	.$dato['aporte_tbj_rpe']. '</td>';
					echo '<td scope="col">'	.$dato['aporte_emp_rpe']. '</td>';
					echo '<td scope="col">'	.$dato['t_aporte_rpe']. '</td>';
					echo '</tr>';
					}
								
					?>
			</tbo
		


	
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