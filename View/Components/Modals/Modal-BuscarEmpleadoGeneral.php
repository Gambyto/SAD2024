<!--
  ============================================================
  Modal-BuscarEmpleadoGeneral.php  —  Buscador reutilizable
  ============================================================
  OPERACIONES DISPONIBLES (op en Search_General.php):
    op=2  → Empleados sin pago esta semana      (Sueldos)
    op=6  → Todos los empleados activos         (ISLR, Vacaciones, Fideicomiso)
    op=7  → Empleados sin préstamo activo       (Préstamos)
    op=8  → Empleados sin usuario registrado    (Usuarios)
  ============================================================
-->

<?php
/* ── Defaults ── */
$modalBuscarConfig = array_merge([
    'op'       => 6,
    'modalId'  => 'modalBuscarEmpGeneral',
    'filtroId' => 'filtroBuscarEmpGeneral',
    'listaId'  => 'listaBuscarEmpGeneral',
    'loaderId' => 'loaderBuscarEmpGeneral',
    'vacioId'  => 'vacioBuscarEmpGeneral',
    'titulo'   => 'Buscar empleado',
    'onSelect' => 'seleccionarEmpleadoGeneral',
    'badgeInfo'=> '',
], $modalBuscarConfig ?? []);

$mId  = $modalBuscarConfig['modalId'];
$fId  = $modalBuscarConfig['filtroId'];
$lId  = $modalBuscarConfig['listaId'];
$ldId = $modalBuscarConfig['loaderId'];
$vId  = $modalBuscarConfig['vacioId'];
$op   = (int) $modalBuscarConfig['op'];
$titulo    = htmlspecialchars($modalBuscarConfig['titulo']);
$onSelect  = $modalBuscarConfig['onSelect'];
$badgeInfo = htmlspecialchars($modalBuscarConfig['badgeInfo']);
?>

<!-- ── BOTÓN DISPARADOR ── -->
<button class="btn btn-outline-secondary" type="button"
        data-bs-toggle="modal" data-bs-target="#<?= $mId ?>"
        title="<?= $titulo ?>">
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
         fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
        <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1z"/>
        <path d="M13.5 5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
        <path fill-rule="evenodd"
              d="M13.5 5H10a.5.5 0 0 0 0 1h3.5a.5.5 0 0 0 0-1zm0 2H10a.5.5 0 0 0 0 1h3.5a.5.5 0 0 0 0-1z"/>
    </svg>
</button>

<!-- ── MODAL ── -->
<div class="modal fade" id="<?= $mId ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="min-height:540px; max-height:540px;">

            <div class="modal-header">
                <h5 class="modal-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                         fill="currentColor" class="bi bi-people-fill me-2" viewBox="0 0 16 16">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        <path fill-rule="evenodd"
                              d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72
                              A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                        <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                    </svg>
                    <?= $titulo ?>
                </h5>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-0"
                 style="display:flex; flex-direction:column; overflow:hidden;">

                <!-- Input fijo -->
                <div class="px-3 pt-3 pb-2 flex-shrink-0">
                    <input type="text" id="<?= $fId ?>"
                           class="form-control form-control-sm"
                           placeholder="Filtrar por nombre o cédula..."
                           autocomplete="off" maxlength="30">
                </div>

                <!-- Badge informativo opcional -->
                <?php if ($badgeInfo): ?>
                <div class="px-3 pb-2 flex-shrink-0">
                    <span class="badge bg-info text-dark"><?= $badgeInfo ?></span>
                </div>
                <?php endif; ?>

                <!-- Loader -->
                <div id="<?= $ldId ?>" class="text-center py-4 flex-shrink-0 d-none">
                    <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                    <span class="ms-2 text-muted small">Cargando empleados...</span>
                </div>

                <!-- Vacío -->
                <div id="<?= $vId ?>" class="text-center py-4 d-none flex-shrink-0">
                    <p class="text-muted small mb-0">No hay empleados disponibles.</p>
                </div>

                <!-- Lista scrolleable -->
                <ul class="list-group list-group-flush" id="<?= $lId ?>"
                    style="overflow-y:auto; flex:1;"></ul>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary"
                        data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>

<!-- ── JS del modal ── -->
<script>
(function () {
    /* Configuración inyectada desde PHP */
    var CFG = {
        modalId  : '<?= $mId ?>',
        filtroId : '<?= $fId ?>',
        listaId  : '<?= $lId ?>',
        loaderId : '<?= $ldId ?>',
        vacioId  : '<?= $vId ?>',
        op       : <?= $op ?>,
        onSelect : '<?= $onSelect ?>'
    };

    $(document).ready(function () {

        /* Cargar al abrir */
        $('#' + CFG.modalId).on('show.bs.modal', function () {
            cargar();
        });

        /* Filtro con delegación */
        $('body').on('input', '#' + CFG.filtroId, function () {
            var termino = $(this).val().toLowerCase().trim();
            $('#' + CFG.listaId + ' li').each(function () {
                var texto = $(this).text().toLowerCase();
                if (termino === '' || texto.indexOf(termino) > -1) {
                    $(this).attr('style', 'display: flex !important; cursor:pointer;');
                } else {
                    $(this).attr('style', 'display: none !important');
                }
            });
        });

        function cargar() {
            var $lista  = $('#' + CFG.listaId).empty();
            var $loader = $('#' + CFG.loaderId).removeClass('d-none');
            var $vacio  = $('#' + CFG.vacioId).addClass('d-none');
            $('#' + CFG.filtroId).val('');

            $.ajax({
                url: '../PHP/CTR/Search_General.php',
                type: 'POST',
                data: { op: CFG.op },
                success: function (raw) {
                    $loader.addClass('d-none');
                    var empleados;
                    try { empleados = JSON.parse(raw); } catch (e) { empleados = []; }

                    if (!Array.isArray(empleados) || !empleados.length) {
                        $vacio.removeClass('d-none');
                        return;
                    }

                    empleados.forEach(function (emp) {
                        var $item = $(
                            '<li class="list-group-item list-group-item-action d-flex ' +
                            'justify-content-between align-items-center py-2 px-3"' +
                            ' style="cursor:pointer;">' +
                            '<div>' +
                            '<span class="fw-semibold small">' +
                                emp.nombre + ' ' + emp.apellido +
                            '</span><br>' +
                            '<span class="text-muted" style="font-size:.78rem;">' +
                                (emp.cargo || '') +
                            '</span>' +
                            '</div>' +
                            '<span class="badge bg-secondary rounded-pill" style="font-size:.75rem;">' +
                                emp.cedula +
                            '</span>' +
                            '</li>'
                        );

                        $item.on('click', function () {
                            /* Llamar la función global configurada en onSelect */
                            if (typeof window[CFG.onSelect] === 'function') {
                                window[CFG.onSelect](emp.cedula);
                            }
                            $('#' + CFG.modalId).modal('hide');
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

    }); // document.ready
})();
</script>