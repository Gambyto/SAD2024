<?php
// Número de elementos por página
$elementosPorPagina = 7;

// Obtener el número total de elementos
$datos = $Empleado->View();
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
    function confirmar(cedula)
    {
        if(confirm("¿Estas seguro que quieres eliminarlo?"))
            {
                $.ajax({
                    url: '../PHP/CTR/Delete_Empleado_CTR.php',
                    type: 'POST',
                    data: {id: cedula},
                    success: function(response){
                        alert(response);
                        location.reload();
                    },
                    error: function(){
                        alert('Error al eliminar el empleado, intente de nuevo');
                    }
                });
                return false;
            }
                return false;
    }
    </script> 

<div class="table__information">
		<h4> Empleados Registrados 
					
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
    <table class="table">
        <thead class="table-primary">
            <tr>
                <th scope="col"> Cédula </th>
                <th scope="col"> Nombre </th>
                <th scope="col"> Teléfono </th>
                <?php if ($_SESSION['type'] != 'Gerencia'){?>
                <th scope="col"> Departamento </th>
                <?php } ?>
                <th scope="col"> Cargo </th>
                <th scope="col"> Ingreso </th>
                <th scope="col"> Sueldo $ </th>
                <?php if ($_SESSION['type'] == 'Gerencia'){?>
                <th scope="col"> Opciones </th>
                <?php } ?>
            </tr>
        </thead>

        <tbody>
            <?php 
            foreach ($datosPagina as $dato) {
                echo '<tr>';
                echo '<th scope="col">' . $dato['cedula'] . '</th>';
                echo '<th scope="col">' . $dato['nombre'] . ' ' .$dato['apellido'].'</th>';
                echo '<th scope="col">' . $dato['tlf'] . '</th>';
                if ($_SESSION['type'] != 'Gerencia'){
                echo '<th scope="col">' . $dato['departamento'] . '</th>';
                }
                echo '<th scope="col">' . $dato['cargo'] . '</th>';
                echo '<th scope="col">' . $dato['f_ingreso'] . '</th>';
                echo '<th scope="col">' . $dato['sueldo'] . ' $</th>';
                if ($_SESSION['type'] == 'Gerencia') {
                    echo '<th scope="col">';
                    echo '<a name="btn1" class="btn btn-outline-info" onclick="cargarEmpleado(\'' . $dato['cedula'] . '\')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>
                    </a>
                    <a name="btn2" class="btn btn-outline-danger" onclick="return confirmar(\'' . $dato['cedula'] . '\')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                        </svg>
                    </a>';
                    echo '</th>';
                }
                echo '</tr>';
            }


    include_once 'Components/Modals/Modal-Update-Empleado.php';
            ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="../JS/Get-Empleado.js"></script>

        </tbody>
    </table>
</div>