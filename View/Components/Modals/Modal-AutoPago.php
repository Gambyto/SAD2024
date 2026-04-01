<!--
  ============================================================
  MODAL "PAGAR TODO" — Nómina automática
  Construido con Bootstrap (igual que Modal-Usuario.php)
  ============================================================
  INTEGRACIÓN:
  1. Pega este bloque en tu vista de nómina.
  2. Copia los 2 CTR a PHP/CTR/
  3. Ajusta las rutas si tu estructura es diferente.
  ============================================================
-->

<!-- BOTON DISPARADOR -->
<a type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalPagarTodo" onclick="openModal()">
  Pagar Todo
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0V4zm0 3h16v5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V7zm3 2a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1H3zm4 0a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1H7z"/>
  </svg>
</a>

<!-- ══════════════════════════════════════
     MODAL PRINCIPAL
════════════════════════════════════════ -->
<div class="modal fade" id="modalPagarTodo" tabindex="-1" aria-labelledby="modalPagarTodoLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <div>
          <h5 class="modal-title" id="modalPagarTodoLabel">Pago Automático de Nómina</h5>
          <small class="text-muted">Semana actual · <span id="pgt-fecha"></span></small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-0">

        <!-- Toolbar -->
        <div class="d-flex align-items-center justify-content-between px-3 py-2 bg-light border-bottom flex-wrap gap-2">
          <div class="form-check mb-0">
            <input class="form-check-input" type="checkbox" id="chkTodos">
            <label class="form-check-label fw-semibold" for="chkTodos">Seleccionar todos</label>
          </div>
          <div class="d-flex gap-3 text-muted small">
            <span>Seleccionados: <strong id="cntSel" class="text-dark">0</strong></span>
            <span>Total a pagar: <strong id="totalSel" class="text-success">$0.00</strong></span>
          </div>
        </div>

        <!-- Spinner de carga -->
        <div id="pgt-loading" class="text-center py-5">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="text-muted mt-2 small">Cargando empleados...</p>
        </div>

        <!-- Tabla -->
        <div id="pgt-tabla-wrap" class="table-responsive" style="display:none; max-height:420px; overflow-y:auto;">
          <table class="table table-hover table-sm mb-0">
            <thead class="table-light sticky-top">
              <tr>
                <th style="width:40px"></th>
                <!-- data-col = índice de columna para ordenar -->
                <th class="pgt-sortable" data-col="nombre" style="cursor:pointer; user-select:none;">
                  Empleado <span class="pgt-sort-icon">↕</span>
                </th>
                <th class="pgt-sortable" data-col="cargo" style="cursor:pointer; user-select:none;">
                  Cargo <span class="pgt-sort-icon">↕</span>
                </th>
                <th class="pgt-sortable" data-col="sueldoSem" style="cursor:pointer; user-select:none;">
                  Sueldo/sem <span class="pgt-sort-icon">↕</span>
                </th>
                <th class="pgt-sortable" data-col="descTotal" style="cursor:pointer; user-select:none;">
                  Descuentos <span class="pgt-sort-icon">↕</span>
                </th>
                <th class="pgt-sortable" data-col="neto" style="cursor:pointer; user-select:none;">
                  Neto <span class="pgt-sort-icon">↕</span>
                </th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody id="pgt-tbody"></tbody>
          </table>
        </div>

      </div>

      <div class="modal-footer d-flex align-items-center justify-content-between">
        <small class="text-muted fst-italic" style="max-width:65%;">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="currentColor"
          >
            <path d="M12 2c5.523 0 10 4.477 10 10a10 10 0 0 1 -19.995 .324l-.005 -.324l.004 -.28c.148 -5.393 4.566 -9.72 9.996 -9.72zm0 9h-1l-.117 .007a1 1 0 0 0 0 1.986l.117 .007v3l.007 .117a1 1 0 0 0 .876 .876l.117 .007h1l.117 -.007a1 1 0 0 0 .876 -.876l.007 -.117l-.007 -.117a1 1 0 0 0 -.764 -.857l-.112 -.02l-.117 -.006v-3l-.007 -.117a1 1 0 0 0 -.876 -.876l-.117 -.007zm.01 -3l-.127 .007a1 1 0 0 0 0 1.986l.117 .007l.127 -.007a1 1 0 0 0 0 -1.986l-.117 -.007z" />
          </svg>
          Los <strong>vendedores</strong> no están incluidos en este listado y deben ser registrados de forma manual.
        </small>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="pgt-registrar" disabled
            data-bs-toggle="modal" data-bs-target="#modalConfirmarPago">
            Registrar pagos <span id="pgt-cnt-badge"></span>
          </button>
        </div>
      </div>

    </div>
  </div>
</div>


<!-- ══════════════════════════════════════
     MODAL CONFIRMACIÓN
════════════════════════════════════════ -->
<div class="modal fade" id="modalConfirmarPago" tabindex="-1" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content pgt-alert-modal">

      <button type="button" class="btn-close pgt-alert-close" data-bs-dismiss="modal" aria-label="Close"></button>

      <div class="modal-body text-center pgt-alert-body">
        <div class="pgt-alert-icon pgt-icon-warn">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0
              2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898
              0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
          </svg>
        </div>
        <h5 class="pgt-alert-title">¿Confirmar pago masivo?</h5>
        <p id="pgt-confirm-resumen" class="pgt-alert-text"></p>
        <p class="pgt-alert-warn">
          Esta acción registrará los pagos en la base de datos y no se puede deshacer.
        </p>
      </div>

      <div class="modal-footer border-0 justify-content-center gap-2 pb-4">
        <button type="button" class="btn btn-light px-4" id="pgt-volver">Volver</button>
        <button type="button" class="btn btn-danger px-4" id="pgt-aceptar">
          <span id="pgt-aceptar-txt">Confirmar</span>
          <span id="pgt-spinner-confirm" class="spinner-border spinner-border-sm ms-1" style="display:none"></span>
        </button>
      </div>

    </div>
  </div>
</div>


<!-- ══════════════════════════════════════
     MODAL RESULTADO
════════════════════════════════════════ -->
<div class="modal fade" id="modalResultadoPago" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content pgt-alert-modal">

      <button type="button" class="btn-close pgt-alert-close" data-bs-dismiss="modal" aria-label="Close"></button>

      <div class="modal-body text-center pgt-alert-body">
        <div id="pgt-res-icon" class="pgt-alert-icon"></div>
        <h5 id="pgt-res-title" class="pgt-alert-title"></h5>
        <p  id="pgt-res-body"  class="pgt-alert-text"></p>
        <ul id="pgt-res-errores" class="pgt-error-list"
            style="display:none;"></ul>
      </div>

      <div class="modal-footer border-0 justify-content-center pb-4">
        <button type="button" class="btn btn-primary px-5" id="pgt-finalizar" data-bs-dismiss="modal">Aceptar</button>
      </div>

    </div>
  </div>
</div>


<!-- ══════════════════════════════════════
     ESTILOS
════════════════════════════════════════ -->
<style>
  /* ── Ordenamiento ── */
  .pgt-sortable:hover { background-color: #e9ecef; }
  .pgt-sortable.asc  .pgt-sort-icon::after { content: ' ▲'; }
  .pgt-sortable.desc .pgt-sort-icon::after { content: ' ▼'; }
  .pgt-sortable .pgt-sort-icon { font-size: .7rem; color: #6c757d; }

  /* ── Fila con descuento excesivo ── */
  tr.pgt-error-desc { opacity: .6; }
  tr.pgt-error-desc td { text-decoration: line-through; }

  /* ── Modales de alerta ── */
  .pgt-alert-modal {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 24px 60px rgba(0,0,0,.18);
  }
  .pgt-alert-close {
    position: absolute; top: 1rem; right: 1rem; z-index: 1;
  }
  .pgt-alert-body {
    padding: 2.5rem 2rem 1rem;
  }
  .pgt-alert-icon {
    display: flex;
    justify-content: center;
    margin-bottom: 1rem;
  }
  .pgt-alert-icon svg {
    filter: drop-shadow(0 4px 8px rgba(0,0,0,.12));
  }
  .pgt-icon-warn  { color: #f59e0b; }
  .pgt-icon-ok    { color: #16a34a; }
  .pgt-icon-error { color: #dc2626; }

  .pgt-alert-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: .5rem;
  }
  .pgt-alert-text {
    color: #64748b;
    font-size: .9rem;
    margin-bottom: .5rem;
    line-height: 1.5;
  }
  .pgt-alert-warn {
    color: #dc2626;
    font-size: .8rem;
    margin-bottom: 0;
  }
  .pgt-error-list {
    list-style: none;
    padding: 0;
    margin: .5rem auto 0;
    max-width: 340px;
    max-height: 130px;
    overflow-y: auto;
    text-align: left;
    font-size: .8rem;
    color: #dc2626;
  }
  .pgt-error-list li {
    padding: .3rem 0;
    border-bottom: 1px solid #fee2e2;
  }
</style>


<!-- ══════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════ -->
<script>
(function ($) {
  'use strict';

  var URL_LISTAR = '../PHP/CTR/Get_Empleados_Nomina.php';
  var URL_PAGAR  = '../PHP/CTR/PagarTodo_CTR.php';

  /* Cargos que NO entran en pago automático */
  var CARGOS_EXCLUIDOS = ['vendedor', 'vendedora', 'vendor'];

  var empleadosData = [];   // datos originales filtrados (sin vendedores)
  var seleccionados  = {};
  var sortCol        = null;
  var sortDir        = 'asc';

  /* ── Utilidades ── */
  function fmt(v) { return '$' + parseFloat(v).toFixed(2); }

  function fechaHoy() {
    return new Date().toLocaleDateString('es-VE', {
      weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
  }

  function esVendedor(emp) {
    var cargo = (emp.cargo || '').toLowerCase().trim();
    return CARGOS_EXCLUIDOS.some(function (ex) { return cargo.indexOf(ex) !== -1; });
  }

  function descuentoSuperaSueldo(emp) {
    var desc = parseFloat(emp.descPrestamo) + parseFloat(emp.descConsumo);
    return desc > parseFloat(emp.sueldoSem);
  }

  /* ── Ordenar ── */
  function ordenarDatos(data, col, dir) {
    return data.slice().sort(function (a, b) {
      var va, vb;
      if (col === 'nombre') {
        va = (a.nombre + ' ' + a.apellido).toLowerCase();
        vb = (b.nombre + ' ' + b.apellido).toLowerCase();
      } else if (col === 'descTotal') {
        va = parseFloat(a.descPrestamo) + parseFloat(a.descConsumo);
        vb = parseFloat(b.descPrestamo) + parseFloat(b.descConsumo);
      } else {
        va = isNaN(a[col]) ? (a[col] || '').toLowerCase() : parseFloat(a[col]);
        vb = isNaN(b[col]) ? (b[col] || '').toLowerCase() : parseFloat(b[col]);
      }
      if (va < vb) return dir === 'asc' ? -1 :  1;
      if (va > vb) return dir === 'asc' ?  1 : -1;
      return 0;
    });
  }

  /* ── Al abrir el modal ── */
  $('#modalPagarTodo').on('show.bs.modal', function () {
    $('#pgt-fecha').text(fechaHoy());
    cargarEmpleados();
  });

  /* ── Cargar empleados ── */
  function cargarEmpleados() {
    $('#pgt-loading').show();
    $('#pgt-tabla-wrap').hide();
    seleccionados = {};
    sortCol = null;
    sortDir = 'asc';
    $('.pgt-sortable').removeClass('asc desc');
    actualizarResumen();

    $.ajax({
      url: URL_LISTAR,
      type: 'GET',
      dataType: 'json',
      success: function (data) {
        /* Filtrar vendedores aquí en el front (doble seguridad) */
        empleadosData = data.filter(function (e) { return !esVendedor(e); });
        renderTabla(empleadosData);
      },
      error: function () {
        $('#pgt-loading').html('<p class="text-danger">Error al cargar empleados.</p>');
      }
    });
  }

  /* ── Renderizar tabla ── */
  function renderTabla(data) {
    var $tbody = $('#pgt-tbody').empty();
    $('#pgt-loading').hide();

    if (!data.length) {
      $tbody.append(
        '<tr><td colspan="7" class="text-center text-muted py-4">' +
        'No hay empleados disponibles esta semana</td></tr>'
      );
      $('#pgt-tabla-wrap').show();
      return;
    }

    $.each(data, function (i, emp) {
      var isPagado    = emp.yaPagado;
      var descTotal   = parseFloat(emp.descPrestamo) + parseFloat(emp.descConsumo);
      var descExcede  = descuentoSuperaSueldo(emp);  /* ← VALIDACIÓN */
      var bloqueado   = isPagado || descExcede;

      var $tr = $('<tr>');
      if (isPagado)   $tr.addClass('text-muted');
      if (descExcede) $tr.addClass('pgt-error-desc');

      /* Badge de estado */
      var badge;
      if (isPagado) {
        badge = '<span class="badge bg-warning text-dark">Ya pagado</span>';
      } else if (descExcede) {
        badge = '<span class="badge bg-danger" title="Los descuentos superan el sueldo semanal">' +
                '<i class="bi bi-exclamation-circle me-1"></i>Descuento excede sueldo</span>';
      } else {
        badge = '<span class="badge bg-primary">Pendiente</span>';
      }

      $tr.html(
        '<td><input type="checkbox" class="form-check-input chk-emp"' +
          ' data-cedula="' + emp.cedula + '"' +
          (bloqueado ? ' disabled' : '') + '></td>' +

        '<td>' +
          '<div class="fw-semibold">' + emp.nombre + ' ' + emp.apellido + '</div>' +
          '<div class="text-muted small">C.I. ' + emp.cedula + '</div>' +
        '</td>' +

        '<td>' + (emp.cargo || '--') + '</td>' +
        '<td>' + fmt(emp.sueldoSem) + '</td>' +

        '<td class="' + (descExcede ? 'text-danger fw-bold' : 'text-danger') + '">' +
          (descTotal > 0 ? '-' + fmt(descTotal) : '--') +
          (descExcede ? ' <i class="bi bi-exclamation-triangle-fill"></i>' : '') +
        '</td>' +

        '<td class="fw-bold text-success">' + fmt(emp.neto) + '</td>' +
        '<td>' + badge + '</td>'
      );

      /* Evento checkbox */
      $tr.find('.chk-emp').on('change', function () {
        if ($(this).is(':checked')) {
          seleccionados[emp.cedula] = emp;
          $tr.addClass('table-primary');
        } else {
          delete seleccionados[emp.cedula];
          $tr.removeClass('table-primary');
        }
        actualizarResumen();
      });

      $tbody.append($tr);
    });

    $('#pgt-tabla-wrap').show();
  }

  /* ── Ordenar al hacer click en <th> ── */
  $(document).on('click', '.pgt-sortable', function () {
    var col = $(this).data('col');

    if (sortCol === col) {
      sortDir = sortDir === 'asc' ? 'desc' : 'asc';
    } else {
      sortCol = col;
      sortDir = 'asc';
    }

    /* Actualizar iconos */
    $('.pgt-sortable').removeClass('asc desc');
    $(this).addClass(sortDir);

    /* Preservar selección y re-renderizar */
    var ordenado = ordenarDatos(empleadosData, sortCol, sortDir);
    renderTabla(ordenado);

    /* Restaurar checkboxes según seleccionados */
    $('.chk-emp:not(:disabled)').each(function () {
      var cedula = $(this).data('cedula');
      if (seleccionados[cedula]) {
        $(this).prop('checked', true);
        $(this).closest('tr').addClass('table-primary');
      }
    });
  });

  /* ── Actualizar resumen ── */
  function actualizarResumen() {
    var lista = Object.values(seleccionados);
    var n     = lista.length;
    var total = lista.reduce(function (s, e) { return s + parseFloat(e.neto); }, 0);

    $('#cntSel').text(n);
    $('#totalSel').text(fmt(total));
    $('#pgt-registrar').prop('disabled', n === 0);
    $('#pgt-cnt-badge').text(n ? '(' + n + ')' : '');

    /* Disponibles = no pagados Y sin descuento excesivo */
    var disponibles = empleadosData.filter(function (e) {
      return !e.yaPagado && !descuentoSuperaSueldo(e);
    });
    var chk = document.getElementById('chkTodos');
    chk.indeterminate = n > 0 && n < disponibles.length;
    chk.checked       = disponibles.length > 0 && n === disponibles.length;
  }

  /* ── Seleccionar todos (solo los que se pueden pagar) ── */
  $('#chkTodos').on('change', function () {
    var disponibles = empleadosData.filter(function (e) {
      return !e.yaPagado && !descuentoSuperaSueldo(e);
    });
    if ($(this).is(':checked')) {
      disponibles.forEach(function (e) { seleccionados[e.cedula] = e; });
    } else {
      seleccionados = {};
    }
    $('.chk-emp:not(:disabled)').each(function () {
      var cedula = $(this).data('cedula');
      $(this).prop('checked', !!seleccionados[cedula]);
      $(this).closest('tr').toggleClass('table-primary', !!seleccionados[cedula]);
    });
    actualizarResumen();
  });

  /* ── Al abrir confirmación ── */
  $('#modalConfirmarPago').on('show.bs.modal', function () {
    var lista  = Object.values(seleccionados);
    var total  = lista.reduce(function (s, e) { return s + parseFloat(e.neto); }, 0);
    $('#pgt-confirm-resumen').text(
      'Se registrarán ' + lista.length + ' pago(s) por un total de ' + fmt(total) + '.'
    );
  });

  /* ── Volver desde confirmación ── */
  $('#pgt-volver').on('click', function () {
    $('#modalConfirmarPago').modal('hide');
    $('#modalPagarTodo').modal('show');
  });

  /* ── Ejecutar pago ── */
  $('#pgt-aceptar').on('click', function () {
    var lista = Object.values(seleccionados);
    $('#pgt-aceptar').prop('disabled', true);
    $('#pgt-aceptar-txt').hide();
    $('#pgt-spinner-confirm').show();

    $.ajax({
      url: URL_PAGAR,
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ empleados: lista }),
      dataType: 'json',
      success: function (res) {
        $('#modalConfirmarPago').modal('hide');
        mostrarResultado(res);

        window.refrescarTablaNomina();
      },
      error: function () {
        $('#modalConfirmarPago').modal('hide');
        mostrarResultadoError('No se pudo conectar con el servidor.');
      },
      complete: function () {
        $('#pgt-aceptar').prop('disabled', false);
        $('#pgt-aceptar-txt').show();
        $('#pgt-spinner-confirm').hide();
      }
    });
  });

  /* ── SVG helpers ── */
  var SVG_OK    = '<svg xmlns="http://www.w3.org/2000/svg" class="pgt-icon-ok" width="56" height="56" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
  var SVG_ERROR = '<svg xmlns="http://www.w3.org/2000/svg" class="pgt-icon-error" width="56" height="56" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';

  /* ── Mostrar resultado ── */
  function mostrarResultado(res) {
    if (res.exitosos > 0) {
      $('#pgt-res-icon').html(SVG_OK);
      $('#pgt-res-title').text('Pagos registrados');
    } else {
      $('#pgt-res-icon').html(SVG_ERROR);
      $('#pgt-res-title').text('No se procesaron pagos');
    }

    $('#pgt-res-body').text(
      res.exitosos + ' pago(s) exitoso(s), ' + res.fallidos + ' omitido(s) o con error.'
    );

    var problemas = (res.resultados || []).filter(function (r) { return r.status !== 'ok'; });
    if (problemas.length) {
      var $ul = $('#pgt-res-errores').empty().show();
      $.each(problemas, function (i, p) {
        $ul.append('<li>· C.I. ' + p.cedula + ' — ' + p.msg + '</li>');
      });
    } else {
      $('#pgt-res-errores').hide();
    }

    $('#modalResultadoPago').modal('show');
  }

  function mostrarResultadoError(msg) {
    $('#pgt-res-icon').html(SVG_ERROR);
    $('#pgt-res-title').text('Error de conexión');
    $('#pgt-res-body').text(msg);
    $('#pgt-res-errores').hide();
    $('#modalResultadoPago').modal('show');
  }

})(jQuery);
</script>