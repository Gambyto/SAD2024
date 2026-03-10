<!-- ============================================================
     Modal: Buscar empleado sin pago
     Ubicación sugerida: Components/Modals/Modal-BuscarEmpleado.php
     ============================================================ -->

<div class="modal fade" id="modalBuscarEmpleado" tabindex="-1" aria-labelledby="modalBuscarEmpleadoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalBuscarEmpleadoLabel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                        class="bi bi-people-fill me-2" viewBox="0 0 16 16">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        <path fill-rule="evenodd"
                            d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                        <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                    </svg>
                    Empleados pendientes de pago
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-0">

                <!-- Buscador dentro del modal -->
                <div class="px-3 pt-3 pb-2">
                    <input type="text" id="filtroEmpleadoModal" class="form-control form-control-sm"
                        placeholder="Filtrar por nombre o cédula..." autocomplete="off">
                </div>

                <!-- Estado de carga -->
                <div id="modalEmpleadoLoader" class="text-center py-4 d-none">
                    <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                    <span class="ms-2 text-muted small">Cargando empleados...</span>
                </div>

                <!-- Mensaje vacío -->
                <div id="modalEmpleadoVacio" class="text-center py-4 d-none">
                    <p class="text-muted small mb-0">No hay empleados pendientes de pago.</p>
                </div>

                <!-- Lista de empleados -->
                <ul class="list-group list-group-flush" id="listaEmpleadosPendientes">
                    <!-- Se rellena dinámicamente -->
                </ul>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>


<script>
/* ---------------------------------------------------------------
   Lógica del modal de empleados pendientes de pago
--------------------------------------------------------------- */

// Cargar la lista al abrir el modal
$('#modalBuscarEmpleado').on('show.bs.modal', function () {
    cargarEmpleadosPendientes();
});

// Filtro en tiempo real
$('#filtroEmpleadoModal').on('input', function () {
    const termino = $(this).val().toLowerCase().trim();
    $('#listaEmpleadosPendientes li').each(function () {
        const texto = $(this).text().toLowerCase();
        $(this).toggle(texto.includes(termino));
    });
});

function cargarEmpleadosPendientes() {
    const $lista  = $('#listaEmpleadosPendientes');
    const $loader = $('#modalEmpleadoLoader');
    const $vacio  = $('#modalEmpleadoVacio');

    $lista.empty();
    $vacio.addClass('d-none');
    $loader.removeClass('d-none');
    $('#filtroEmpleadoModal').val('');

    $.ajax({
        url: '../PHP/CTR/Search_General.php',
        type: 'POST',
        data: { op: 2 },
        success: function (response) {
            $loader.addClass('d-none');

            let empleados;
            try { empleados = JSON.parse(response); } catch (e) { empleados = []; }

            if (!Array.isArray(empleados) || empleados.length === 0) {
                $vacio.removeClass('d-none');
                return;
            }

            empleados.forEach(function (emp) {
                const nombre = emp.nombre + ' ' + emp.apellido;
                const cedula = emp.cedula;
                const cargo  = emp.cargo ?? '';

                const $item = $(`
                    <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3"
                        style="cursor:pointer;"
                        data-cedula="${cedula}">
                        <div>
                            <span class="fw-semibold small">${nombre}</span><br>
                            <span class="text-muted" style="font-size:.78rem;">${cargo}</span>
                        </div>
                        <span class="badge bg-secondary rounded-pill" style="font-size:.75rem;">${cedula}</span>
                    </li>
                `);

                $item.on('click', function () {
                    seleccionarEmpleado(cedula);
                });

                $lista.append($item);
            });
        },
        error: function () {
            $loader.addClass('d-none');
            $vacio.removeClass('d-none');
        }
    });
}

function limpiarFormulario() {
    $('#cedula').val('');
    $('#cedula1').val('');
    $('#nombre').val('');
    $('#apellido').val('');
    $('#cargo').val('');
    $('#sueldoM').val('');
    $('#sueldoS').val('');
    $('#prestamo').val('0');
    $('#id_prestamo').val('');
    $('#consumo').val('0');
    $('#id_consumo').val('');
    $('#deduc').val('0');
    $('#bono').val('');
    $('#bono1').val('');
    $('#comision').val('');
    $('#comision1').val('');
    $('#asig').val('0');
    $('#Netodiv').val('');
    $('#netoPagar').text('$ 0.00');
    $('#netoPagarBs').text('Bs 0.00');
    $('#alerts').html('');
}

function seleccionarEmpleado(cedula) {
    // Registrar el callback ANTES de cerrar, usando .one() para que
    // se ejecute una sola vez cuando el modal termine la animación
    // de cierre. Esto evita el error aria-hidden y el TypeError del reset.
    $('#modalBuscarEmpleado').one('hidden.bs.modal', function () {
        limpiarFormulario();
        $('#cedula').val(cedula);
        buscarEmpleado(cedula);
    });

    $('#modalBuscarEmpleado').modal('hide');
}
</script>