<!-- Modal para actualizar empleado -->
<div class="modal fade" id="solicitudes" tabindex="-1" aria-labelledby="empleadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="empleadoModalLabel">Solicitudes de préstamos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario de búsqueda -->
                <div class="modal-form">

                    <form action="">
                        <div class="empleados__content modal-search">
                            <label for="search"> Buscar: </label>
                            <div class="input-group mb-3">
                                <input type="text" id="search-s" name="search" class="form-control" aria-label="Buscar por cédula" placeholder="Buscar por cédula">
                                
                            </div>
                        </div>
                    </form>
                    
                    <!-- Navegación de Páginas -->
                    <div class="empleados__content">
                        <nav class="Page" aria-label="Page navigation">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" id="anterior-s" href="#" tabindex="-1">Anterior</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" id="siguiente-s" href="#">Siguiente</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    
                </div>
                <!-- Tabla de datos -->
                <table class="table">
                    <thead class="table-primary" style="text-align: center;">
                        <tr>
                            <th scope="col"> Cédula </th>
                            <th scope="col"> Nombre </th>
                            <th scope="col"> Monto </th>
                            <th scope="col"> Cuotas </th>
                            <th scope="col"> N° cuotas </th>
                            <th scope="col" colspan="2"> Descripción </th>
                            <th scope="col"> Fecha de solicitud </th>
                            <th scope="col"> Estado </th>
                            <th scope="col"> Opciones </th>
                        </tr>
                    </thead>
                    <tbody id="tablas-solicitudes" style="text-align: center;">
                        <!-- Los datos se cargarán aquí dinámicamente -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <!-- Pie de modal (opcional) -->
            </div>
        </div>
    </div>
</div>

<!-- Scripts necesarios -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- Script de paginación y búsqueda -->
<script>

var paginaActual = 1;
var totalPaginas = 0; // Inicializar totalPaginas
var busqueda = ""; // Inicializar búsqueda

function cambiarPagina(pagina) {
    $.ajax({
        url: 'Components/Tables/Tablas-Solicitud.php',
        type: 'GET',
        data: { pagina: pagina, busqueda: busqueda },
        success: function(response) {
    try {
        var datos = JSON.parse(response);
        $('#tablas-solicitudes').html(datos.datos); // Actualizar la tabla
        totalPaginas = datos.totalPaginas; // Actualizar el total de páginas
        paginaActual = pagina; // Actualizar la página actual
        actualizarBotones(); // Actualizar el estado de los botones
    } catch (e) {
        console.log("Error al parsear la respuesta del servidor:", e);
        console.log("Respuesta del servidor:", response);
    }
},
        error: function() {
            alert('Error al cargar los datos. Intente nuevamente.');
        }
    });
}

function actualizarBotones() {
    if (paginaActual == 1) {
        $('#anterior-s').addClass('disabled'); // Deshabilitar "Anterior" si estamos en la primera página
    } else {
        $('#anterior-s').removeClass('disabled'); // Habilitar "Anterior" si no estamos en la primera página
    }
    
    if (paginaActual == totalPaginas) {
        $('#siguiente-s').addClass('disabled'); // Deshabilitar "Siguiente" si estamos en la última página
    } else {
        $('#siguiente-s').removeClass('disabled'); // Habilitar "Siguiente" si no estamos en la última página
    }
}

$('#anterior-s').click(function(e) {
    e.preventDefault(); // Evitar el comportamiento por defecto del enlace
    if (paginaActual > 1) {
        cambiarPagina(paginaActual - 1); // Cambiar a la página anterior
    }
});

$('#siguiente-s').click(function(e) {
    e.preventDefault(); // Evitar el comportamiento por defecto del enlace
    if (paginaActual < totalPaginas) {
        cambiarPagina(paginaActual + 1); // Cambiar a la página siguiente
    }
});

// Función para manejar la búsqueda
$('#search-s').on('input', function() {
    busqueda = $(this).val(); // Obtener el valor de búsqueda
    cambiarPagina(1); // Reiniciar a la primera página
});

// Cargar los datos inicialmente
cambiarPagina(1);

    function confirmarA(id)
    {  
        if(confirm("¿Quieres aprobar esté prestamo?"))
            {
                $.ajax({
                    url: '../PHP/CTR/Solicitudes_CTR.php',
                    type: 'POST',
                    data: {id: id,
                        accion: 'Aprovado'
                    },
                    success: function(response){
                        if (data.html) {
                        $('#alerts').html(data.html);
                        } else {
                        alert(data.message);
                        }
                    },
                    error: function(){
                        alert('Error al aprobar esta solicitud, intente de nuevo');
                    }
                });
                return false;
            }
                return false;
    }

    function confirmarD(id)
    {  
        if(confirm("Denegar solicitud ¿Está seguro?"))
            {
                $.ajax({
                    url: '../PHP/CTR/Solicitudes_CTR.php',
                    type: 'POST',
                    data: {id: id,
                        accion: 'Denegado'},
                    success: function(response){
                        if (data.html) {
                        $('#alerts').html(data.html);
                    } else {
                        alert(data.message);
                    }
                    },
                    error: function(){
                        alert('Error, intente de nuevo');
                    }
                });
                return false;
            }
                return false;
    }

</script>