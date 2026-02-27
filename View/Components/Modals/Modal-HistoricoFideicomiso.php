<!-- ================================================================
     Modal: Histórico de Fideicomisos (agrupado por mes)
     Ubicación: View/Components/Modals/Modal-Historico-Fideicomiso.php
     ================================================================ -->

<div class="modal fade" id="historicoFideicomisoModal" tabindex="-1"
     aria-labelledby="historicoFideicomisoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="historicoFideicomisoLabel">
                    Histórico de Fideicomisos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">

                <!-- Paginación -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <nav aria-label="Navegación fideicomisos">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item">
                                <a class="page-link" id="anteriorFide" href="#" tabindex="-1">← Anterior</a>
                            </li>
                            <li class="page-item disabled">
                                <span class="page-link" id="paginaFide">Pág. 1</span>
                            </li>
                            <li class="page-item">
                                <a class="page-link" id="siguienteFide" href="#">Siguiente →</a>
                            </li>
                        </ul>
                    </nav>
                    <small class="text-muted" id="infoTotalFide"></small>
                </div>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark" style="text-align:center;">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Mes</th>
                                <th scope="col">N° empleados</th>
                                <th scope="col">Total monto ($)</th>
                                <th scope="col">Total anticipo ($)</th>
                                <th scope="col">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tablaHistoricoFide" style="text-align:center;">
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

<script>
(function () {
    var paginaActualFide = 1;
    var totalPaginasFide = 0;

    function cargarHistoricoFide(pagina) {
        $.ajax({
            url: 'Components/Tables/Tablas-Historico-Fideicomiso.php',
            type: 'GET',
            dataType: 'json',
            data: { pagina: pagina },
            success: function (datos) {
                $('#tablaHistoricoFide').html(datos.datos);
                totalPaginasFide = datos.totalPaginas;
                paginaActualFide = pagina;
                $('#paginaFide').text('Pág. ' + pagina + ' / ' + totalPaginasFide);
                $('#infoTotalFide').text(datos.totalRegistros + ' mes(es) registrado(s)');
                actualizarNavFide();
            },
            error: function (xhr) {
                console.error('Error fideicomiso histórico:', xhr.responseText);
                $('#tablaHistoricoFide').html('<tr><td colspan="6" class="text-danger">Error al cargar los datos.</td></tr>');
            }
        });
    }

    function actualizarNavFide() {
        $('#anteriorFide').parent().toggleClass('disabled', paginaActualFide <= 1);
        $('#siguienteFide').parent().toggleClass('disabled', paginaActualFide >= totalPaginasFide);
    }

    $('#anteriorFide').on('click', function (e) {
        e.preventDefault();
        if (paginaActualFide > 1) cargarHistoricoFide(paginaActualFide - 1);
    });

    $('#siguienteFide').on('click', function (e) {
        e.preventDefault();
        if (paginaActualFide < totalPaginasFide) cargarHistoricoFide(paginaActualFide + 1);
    });

    $('#historicoFideicomisoModal').on('show.bs.modal', function () {
        paginaActualFide = 1;
        cargarHistoricoFide(1);
    });

    cargarHistoricoFide(1);
})();
</script>