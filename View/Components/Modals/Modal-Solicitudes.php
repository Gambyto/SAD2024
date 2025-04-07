<!-- Modal para actualizar empleado -->
<div class="modal fade" id="solicitudesPrestamos" tabindex="-1" aria-labelledby="solicitudesPrestamosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="solicitudesPrestamosModalLabel">Solicitudes de préstamos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario de búsqueda -->
                <div class="modal-form">

                    <form action="">
                        <div class="empleados__content modal-search">
                            <label for="buscarSolicitudes"> Buscar: </label>
                            <div class="input-group mb-3">
                                <input type="text" id="buscarSolicitudes" name="buscarSolicitudes" class="form-control" aria-label="Buscar por cédula" placeholder="Buscar por cédula">
                                
                            </div>
                        </div>
                    </form>
                    
                    <!-- Navegación de Páginas -->
                    <div class="empleados__content">
                        <nav class="Page" aria-label="Page navigation">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" id="anteriorSolicitudes" href="#" tabindex="-1">Anterior</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" id="siguienteSolicitudes" href="#">Siguiente</a>
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
                    <tbody id="tablaSolicitudes" style="text-align: center;">
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

var paginaActualSolicitudes = 1;
var totalPaginasSolicitudes = 0; // Inicializar totalPaginas
var buscarSolicitudes = ""; // Inicializar búsqueda

function cargarSolicitudes(pagina) {
    $.ajax({
        url: 'Components/Tables/Tablas-Solicitud.php',
        type: 'GET',
        data: { pagina: pagina, busqueda: buscarSolicitudes },
        success: function(response) {
    try {
        var datos = JSON.parse(response);
        $('#tablaSolicitudes').html(datos.datos); // Actualizar la tabla
        totalPaginasSolicitudes = datos.totalPaginas; // Actualizar el total de páginas
        paginaActualSolicitudes = pagina; // Actualizar la página actual
        actualizarBotonesSolicitudes(); // Actualizar el estado de los botones
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

function actualizarBotonesSolicitudes() {
    if (paginaActualSolicitudes == 1) {
        $('#anteriorSolicitudes').addClass('disabled'); // Deshabilitar "Anterior" si estamos en la primera página
    } else {
        $('#anteriorSolicitudes').removeClass('disabled'); // Habilitar "Anterior" si no estamos en la primera página
    }
    
    if (paginaActualSolicitudes == totalPaginasSolicitudes) {
        $('#siguienteSolicitudes').addClass('disabled'); // Deshabilitar "Siguiente" si estamos en la última página
    } else {
        $('#siguienteSolicitudes').removeClass('disabled'); // Habilitar "Siguiente" si no estamos en la última página
    }
}

$('#anteriorSolicitudes').click(function(e) {
    e.preventDefault(); // Evitar el comportamiento por defecto del enlace
    if (paginaActualSolicitudes > 1) {
        cargarSolicitudes(paginaActualSolicitudes - 1); // Cambiar a la página anterior
    }
});

$('#siguienteSolicitudes').click(function(e) {
    e.preventDefault(); // Evitar el comportamiento por defecto del enlace
    if (paginaActualSolicitudes < totalPaginasSolicitudes) {
        cargarSolicitudes(paginaActualSolicitudes + 1); // Cambiar a la página siguiente
    }
});

// Función para manejar la búsqueda
$('#buscarSolicitudes').on('input', function() {
    buscarSolicitudes = $(this).val(); // Obtener el valor de búsqueda
    cargarSolicitudes(1); // Reiniciar a la primera página
});

// Cargar los datos inicialmente
cargarSolicitudes(1);

function aprobarSolicitud(id) {
  if (confirm("¿Quieres aprobar esta solicitud?")) {
    $.ajax({
      url: '../PHP/CTR/Solicitudes_CTR.php',
      type: 'POST',
      data: { id: id, accion: 'Aprovado' },
      success: function(response) {
        if (!response.message) {
          // Eliminar la fila correspondiente de la tabla
          $('#registro-' + id).remove();
          $('#alerts').html(response.html);
        } else {
          alert('Error: Algo salió mal');
        }
      },
      error: function() {
        alert('Error al aprobar esta solicitud, intente de nuevo');
      }
    });
    return false;
  }
  return false;
}

function denegarSolicitud(id) {
  if (confirm("Denegar solicitud ¿Está seguro?")) {
    $.ajax({
      url: '../PHP/CTR/Solicitudes_CTR.php',
      type: 'POST',
      data: { id: id, accion: 'Denegado' },
      success: function(response) {
        if (!response.message) {
          // Eliminar la fila correspondiente de la tabla
          $('#registro-' + id).remove();
          $('#alerts').html(response.html);
        } else {
          alert('Error: Algo salió mal');
        }
      },
      error: function() {
        alert('Error, intente de nuevo');
      }
    });
    return false;
  }
  return false;
}
</script>