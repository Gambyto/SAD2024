<div class="table__information" >
    <h4> Recibos 
        <!-- Navegación de Páginas -->
        <nav class="Page" aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" id="anterior" href="#" tabindex="-1">Anterior</a>
                </li>
                <li class="page-item">
                    <a class="page-link" id="siguiente" href="#">Siguiente</a>
                </li>
            </ul>
        </nav>
    </h4>
    <table class="table">
        <thead class="table-primary">
            <tr>
                <th scope="col"> Fecha </th>
                <th scope="col"> Concepto </th>
                <th scope="col"> Cédula </th>
                <th scope="col"> Nombre </th>
                <th scope="col"> Opciones </th>
            </tr>
        </thead>       
        <tbody id="tabla-datos" style="text-align: center;">
                <!-- Los datos se cargarán aquí dinámicamente -->
        </tbody>
    </table>
</div>
            

<!-- Scripts necesarios -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- Script de paginación y búsqueda -->
<script>
var paginaActual = 1;
var totalPaginas = 0; // Inicializar totalPaginas 
 // Inicializar parametros de busqueda
var cedula = "";
var concepto = "";
var fecha = "";

function cambiarPagina(pagina, cedula, concepto, fecha) {
    $.ajax({
        url: 'Components/Tables/Tablas-Recibos_CTR.php',
        type: 'GET',
        data: { pagina: pagina, cedula: cedula, concepto: concepto, fecha: fecha },
        success: function(response) {
    try {
        var datos = JSON.parse(response);
        $('#tabla-datos').html(datos.datos); // Actualizar la tabla
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
        $('#anterior').addClass('disabled'); // Deshabilitar "Anterior" si estamos en la primera página
    } else {
        $('#anterior').removeClass('disabled'); // Habilitar "Anterior" si no estamos en la primera página
    }
    
    if (paginaActual == totalPaginas) {
        $('#siguiente').addClass('disabled'); // Deshabilitar "Siguiente" si estamos en la última página
    } else {
        $('#siguiente').removeClass('disabled'); // Habilitar "Siguiente" si no estamos en la última página
    }
}

$('#anterior').click(function(e) {
    e.preventDefault(); // Evitar el comportamiento por defecto del enlace
    if (paginaActual > 1) {
        cambiarPagina(paginaActual - 1, cedula, concepto, fecha); // Cambiar a la página anterior
    }
});

$('#siguiente').click(function(e) {
    e.preventDefault(); // Evitar el comportamiento por defecto del enlace
    if (paginaActual < totalPaginas) {
        cambiarPagina(paginaActual + 1, cedula, concepto, fecha); // Cambiar a la página siguiente
    }
});

// Función para manejar la búsqueda
$('#cedula1').on('input', function() {
    cedula = $(this).val(); // Obtener el valor de búsqueda
    cambiarPagina(1, cedula, concepto, fecha); // Reiniciar a la primera página
});
// Función para manejar la búsqueda
$('#concepto').on('change', function() {
    concepto = $(this).val(); // Obtener el valor de búsqueda
    cambiarPagina(1, cedula, concepto, fecha); // Reiniciar a la primera página
});
// Función para manejar la búsqueda
$('#fecha').on('input', function() {
    fecha = $(this).val(); // Obtener el valor de búsqueda
    cambiarPagina(1, cedula, concepto, fecha); // Reiniciar a la primera página
});

// Cargar los datos inicialmente
cambiarPagina(1);
</script> 