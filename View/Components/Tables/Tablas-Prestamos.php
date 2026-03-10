<?php
// Número de elementos por página
$elementosPorPagina = 5;

// Obtener el número total de elementos
$datos = $Nomina->Prestamos_View();
$totalElementos = count($datos);

// Calcular el número total de páginas
$totalPaginas = ceil($totalElementos / $elementosPorPagina);

// Obtener la página actual
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

// Calcular el índice de inicio para la consulta
$inicio = ($paginaActual - 1) * $elementosPorPagina; 

// Obtener los datos para la página actual
$datosPagina = array_slice($datos, $inicio, $elementosPorPagina);
?>

    <script>
    function confirmar(id)
    {
        if(confirm("¿Estas seguro de eliminar este prestamo?"))
            {
                $.ajax({
                    url: '../PHP/CTR/Delete_Prestamo_CTR.php',
                    type: 'POST',
                    data: {id: id},
                    success: function(response){
                        alert(response);
                        location.reload();
                    },
                    error: function(){
                        alert('Error al eliminar el prestamo, intente de nuevo');
                    }
                });
                return false;
            }
                return false;
    }
    </script> 

<div class="table__information">
		<h4> Prestamos activos

				<!-- Navegación de Páginas -->
			<nav aria-label="Page navigation">
			<ul class="pagination">
				<li class="page-item <?php if ($paginaActual == 1) echo 'disabled'; ?>">
					<a class="page-link" href="?pagina=<?php echo max(1, $paginaActual - 1); ?>" tabindex="-1">Anterior</a>
				</li>
				<li class="page-item <?php if ($paginaActual == $totalPaginas) echo 'disabled'; ?>">
					<a class="page-link" href="?pagina=<?php echo min($totalPaginas, $paginaActual + 1); ?>">Siguiente</a>
				</li>
			</ul>
			</nav>
		</h4>
    <table class="table" id="tablaPrestamos">
        <thead class="table-primary" style="text-align: center;">
            <tr>
                <th scope="col"> Cédula </th>
                <th scope="col"> Nombre </th>
                <th scope="col"> Monto </th>
                <th scope="col"> N° cuotas </th> 
                <th scope="col"> Deuda </th> 
                <th scope="col"> Fecha de solicitud </th>
                <th scope="col"> Fecha de límite </th>
                <?php if ($_SESSION['type'] == 'Gerencia' ) { ?>
                <th scope="col"> Opciones </th>
                <?php } ?>
            </tr>
        </thead>

        <tbody style="text-align: center;" id="cuerpoTabla">
            <?php 
            foreach ($datosPagina as $dato) {
                $clase = (strtotime($dato['date_limit']) < strtotime(date('Y-m-d'))) ? 'mora' : '';
                echo '<tr class="' . $clase . '">';
                echo '<th scope="col">' . $dato['cedula'] . '</th>';
                echo '<th scope="col">' . $dato['nombre'] .' '. $dato['apellido']  . '</th>';
                echo '<th scope="col" style="text-align: rigth;">' . $dato['monto'] . ' $</th>';
                echo '<th scope="col">' . $dato['cuotas'] . '</th>';
                echo '<th scope="col">' . $dato['monto_desc'] . ' $</th>';
                echo '<th scope="col">' . $dato['fecha'] . '</th>';
                echo '<th scope="col">' . $dato['date_limit'] . '</th>';
                if ($_SESSION['type'] == 'Gerencia') {
                echo '<th>
                        <a name="btn2" class="btn btn-outline-danger" onclick="return confirmar(\'' . $dato['id_prestamos'] . '\')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                            </svg>
                        </a>
                    </th>';
                }
                echo '</tr>';
            }
            ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="../JS/Get-Empleado.js"></script>

        </tbody>
    </table>
</div>