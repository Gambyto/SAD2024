<!-- Modal Histórico de Nóminas -->
<div class="modal fade" id="historicoNominaModal" tabindex="-1" aria-labelledby="historicoNominaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="historicoNominaModalLabel">
                    Histórico de Nóminas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <!-- Paginación -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <nav aria-label="Navegación de nóminas">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item">
                                <a class="page-link" id="anteriorNomina" href="#" tabindex="-1">← Anterior</a>
                            </li>
                            <li class="page-item disabled">
                                <span class="page-link" id="paginaNomina">Pág. 1</span>
                            </li>
                            <li class="page-item">
                                <a class="page-link" id="siguienteNomina" href="#">Siguiente →</a>
                            </li>
                        </ul>
                    </nav>
                    <small class="text-muted" id="infoTotalNominas"></small>
                </div>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark" style="text-align: center;">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Fecha de nómina</th>
                                <th scope="col">N° empleados</th>
                                <th scope="col">Total neto ($)</th>
                                <th scope="col">Total neto (Bs)</th>
                                <th scope="col">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tablaHistoricoNomina" style="text-align: center;">
                            <tr>
                                <td colspan="6" class="text-muted py-4">Cargando...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>

<!-- Script del modal -->
<script>
(function () {
    var paginaActualNomina   = 1;
    var totalPaginasNomina   = 0;

    function cargarHistoricoNomina(pagina) {
    $.ajax({
        url: 'Components/Tables/Tablas-Historico-Nomina.php',
        type: 'GET',
        dataType: 'json', // <-- agregar esto, jQuery parsea el JSON automáticamente
        data: { pagina: pagina },
        success: function (datos) {
            // Ya no necesitas JSON.parse, jQuery lo hace solo con dataType:'json'
            $('#tablaHistoricoNomina').html(datos.datos);
            totalPaginasNomina = datos.totalPaginas;
            paginaActualNomina = pagina;
            $('#paginaNomina').text('Pág. ' + pagina + ' / ' + totalPaginasNomina);
            $('#infoTotalNominas').text(datos.totalRegistros + ' nómina(s) registrada(s)');
            actualizarNavNomina();
        },
        error: function (xhr) {
            console.error('Respuesta del servidor:', xhr.responseText);
            $('#tablaHistoricoNomina').html('<tr><td colspan="6" class="text-danger">Error al cargar los datos.</td></tr>');
        }
    });
}

    function actualizarNavNomina() {
        if (paginaActualNomina <= 1) {
            $('#anteriorNomina').parent().addClass('disabled');
        } else {
            $('#anteriorNomina').parent().removeClass('disabled');
        }
        if (paginaActualNomina >= totalPaginasNomina) {
            $('#siguienteNomina').parent().addClass('disabled');
        } else {
            $('#siguienteNomina').parent().removeClass('disabled');
        }
    }

    $('#anteriorNomina').on('click', function (e) {
        e.preventDefault();
        if (paginaActualNomina > 1) cargarHistoricoNomina(paginaActualNomina - 1);
    });

    $('#siguienteNomina').on('click', function (e) {
        e.preventDefault();
        if (paginaActualNomina < totalPaginasNomina) cargarHistoricoNomina(paginaActualNomina + 1);
    });

    // Cargar al abrir el modal
    $('#historicoNominaModal').on('show.bs.modal', function () {
        paginaActualNomina = 1;
        cargarHistoricoNomina(1);
    });
})();
</script>