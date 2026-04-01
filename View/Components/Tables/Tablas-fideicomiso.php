<!--
  ============================================================
  Tablas-fideicomiso.php  —  Tabla con paginación JS + actualización
  en tiempo real.  Solo muestra registros del mes en curso
  (o del mes filtrado por el selector de mes).
  ============================================================
-->

<div class="table__information">

    <!-- ── Barra de filtro ── -->
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:.5rem;">
        <div class="empleados__content mb-0">
            <label class="me-1">Buscar por mes:</label>
            <div class="input-group input-group-sm">
                <input type="month" class="form-control" id="fideFiltroMes"
                       value="<?php echo date('Y-m'); ?>">
                <button class="btn btn-outline-info" id="fideBtnBuscar" type="button">Buscar</button>
                <button class="btn btn-outline-secondary" id="fideBtnHoy" type="button" title="Ver mes actual">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                         viewBox="0 0 16 16">
                        <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1
                                 a.5.5 0 0 1-.5-.5v-1z"/>
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11
                                 a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1
                                 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Paginación -->
        <nav aria-label="Paginación Fideicomiso">
            <ul class="pagination pagination-sm mb-0" id="fidePaginacion"></ul>
        </nav>
    </div>

    <!-- Tabla -->
    <table class="table mt-2" id="fideTabla">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Cédula</th>
                <!-- <th>Fecha de ingreso</th> -->
                <th>Sueldo</th>
                <th>Tasa de utilidad</th>
                <th>Tasa de bono vacacional</th>
                <th>Alícuota utilidad</th>
                <th>Alícuota bono vacacional</th>
                <th>Sueldo integral</th>
                <th>Salario diario integral</th>
                <th>Días antigüedad</th>
                <th>Días acumulados adicional</th>
                <th>Total días</th>
                <th>Fideicomiso</th>
                <th>Monto anticipo</th>
                <th width="120">Fecha</th>
            </tr>
        </thead>
        <tbody id="fideTbody">
            <!-- Se llena dinámicamente -->
        </tbody>
    </table>

    <!-- Info de página -->
    <div class="text-muted small" id="fideInfo"></div>

</div>


<script>
/* ================================================================
   Lógica de tabla Fideicomiso — paginación del lado del cliente
   ================================================================ */
(function ($) {
    'use strict';

    var POR_PAGINA    = 4;
    var paginaActual  = 1;
    var todosLosDatos = [];
    var mesFiltro     = '<?php echo date("Y-m"); ?>';

    /* ── Función pública: permite refrescar la tabla desde modales ── */
    window.refrescarTablaFideicomiso = function () {
        cargarDatos(mesFiltro);
    };

    /* ── Botón Buscar ── */
    $('#fideBtnBuscar').on('click', function () {
        var mes = $('#fideFiltroMes').val();
        if (!mes) return;
        mesFiltro    = mes;
        paginaActual = 1;
        cargarDatos(mesFiltro);
    });

    /* ── Botón "mes actual" ── */
    $('#fideBtnHoy').on('click', function () {
        mesFiltro = '<?php echo date("Y-m"); ?>';
        $('#fideFiltroMes').val(mesFiltro);
        paginaActual = 1;
        cargarDatos(mesFiltro);
    });

    /* ── Cargar datos vía AJAX ── */
    function cargarDatos(mes) {
        $.ajax({
            url: '../PHP/CTR/Search_General.php',
            type: 'POST',
            data: { op: 10, mes: mes },
            success: function (raw) {
                var data;
                try { data = typeof raw === 'string' ? JSON.parse(raw) : raw; } catch (e) { data = []; }
                todosLosDatos = Array.isArray(data) ? data : [];
                renderPagina(paginaActual);
            },
            error: function () {
                $('#fideTbody').html(
                    '<tr><td colspan="17" class="text-center text-danger">Error al cargar los datos.</td></tr>'
                );
            }
        });
    }

    /* ── Renderizar una página ── */
    function renderPagina(pagina) {
        paginaActual  = pagina;
        var total     = todosLosDatos.length;
        var totalPags = Math.max(1, Math.ceil(total / POR_PAGINA));
        if (paginaActual > totalPags) paginaActual = totalPags;

        var inicio = (paginaActual - 1) * POR_PAGINA;
        var slice  = todosLosDatos.slice(inicio, inicio + POR_PAGINA);

        var html = '';
        if (!slice.length) {
            html = '<tr><td colspan="17" class="text-center text-muted py-3">' +
                   'No hay registros para el período seleccionado.</td></tr>';
        } else {
            $.each(slice, function (i, d) {
                html +=
                    '<tr>' +
                    '<th height = "55">' + (d.nombre            || '') + '</th>' +
                    '<th>' + (d.apellido          || '') + '</th>' +
                    '<th>' + (d.cedula            || '') + '</th>' +
                    /* '<th>' + (d.f_ingreso         || '') + '</th>' + */
                    '<th width = "120">' + parseFloat(d.sueldo           || 0).toFixed(2) + ' $</th>' +
                    '<th>' + parseFloat(d.tasa_utilidad    || 0).toFixed(2) + ' $</th>' +
                    '<th>' + parseFloat(d.t_bonovacacional || 0).toFixed(2) + ' $</th>' +
                    '<th>' + parseFloat(d.a_utilidad       || 0).toFixed(2) + ' $</th>' +
                    '<th>' + parseFloat(d.a_bonovacional   || 0).toFixed(2) + ' $</th>' +
                    '<th>' + parseFloat(d.sueldo_integral  || 0).toFixed(2) + ' $</th>' +
                    '<th>' + parseFloat(d.sueldod_integral || 0).toFixed(2) + ' $</th>' +
                    '<th>' + (d.dias_antiguedad   || 0)   + '</th>' +
                    '<th>' + (d.dias_acumulados   || 0)   + '</th>' +
                    '<th>' + (d.total_dias        || 0)   + '</th>' +
                    '<th>' + parseFloat(d.monto   || 0).toFixed(2) + ' $</th>' +
                    '<th>' + parseFloat(d.anticipo|| 0).toFixed(2) + ' $</th>' +
                    '<th width="120">' + (d.fecha             || '') + '</th>' +
                    '</tr>';
            });
        }

        /* Un solo reemplazo — el ojo no llega a ver el vacío */
        $('#fideTbody').html(html);

        /* Info */
        var desde = total ? inicio + 1 : 0;
        var hasta = Math.min(inicio + POR_PAGINA, total);
        $('#fideInfo').text('Mostrando ' + desde + '–' + hasta + ' de ' + total + ' registros.');

        /* Paginación */
        renderPaginacion(totalPags);
    }

    /* ── Renderizar botones de paginación ── */
    function renderPaginacion(totalPags) {
        var $ul = $('#fidePaginacion').empty();

        /* Anterior */
        $ul.append(
            '<li class="page-item ' + (paginaActual === 1 ? 'disabled' : '') + '">' +
            '<a class="page-link" href="#" data-pag="' + (paginaActual - 1) + '">‹</a></li>'
        );

        /* Páginas */
        for (var p = 1; p <= totalPags; p++) {
            $ul.append(
                '<li class="page-item ' + (p === paginaActual ? 'active' : '') + '">' +
                '<a class="page-link" href="#" data-pag="' + p + '">' + p + '</a></li>'
            );
        }

        /* Siguiente */
        $ul.append(
            '<li class="page-item ' + (paginaActual === totalPags ? 'disabled' : '') + '">' +
            '<a class="page-link" href="#" data-pag="' + (paginaActual + 1) + '">›</a></li>'
        );

        /* Click en links de paginación */
        $ul.find('a.page-link').on('click', function (e) {
            e.preventDefault();
            var pag = parseInt($(this).data('pag'));
            var totalPagsActual = Math.max(1, Math.ceil(todosLosDatos.length / POR_PAGINA));
            if (pag >= 1 && pag <= totalPagsActual) {
                renderPagina(pag);
            }
        });
    }

    /* ── Carga inicial (mes actual) ── */
    cargarDatos(mesFiltro);

})(jQuery);
</script>