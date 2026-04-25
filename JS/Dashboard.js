/**
 * dashboard.js
 * ─────────────────────────────────────────────────────────────────
 *  1. Drag & drop de cápsulas con SortableJS  (orden persistido en localStorage)
 *  2. Exportación PDF individual por cápsula  (jsPDF + html2canvas)
 *  3. Reporte PDF general con selector de cápsulas
 * ─────────────────────────────────────────────────────────────────
 *
 *  Dependencias (incluir en Dashboard.php ANTES de este script):
 *
 *  <!-- SortableJS -->
 *  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
 *  <!-- jsPDF + html2canvas -->
 *  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
 *  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
 */

/* ══════════════════════════════════════════════════════════════════
   1.  DRAG & DROP
   ══════════════════════════════════════════════════════════════════ */

const STORAGE_KEY_PREFIX = 'dashboard_order_';

/**
 * Inicializa SortableJS en un contenedor dado y restaura/guarda el orden.
 * @param {string} containerId   ID del div contenedor (k1, k2, k3)
 */
function initSortableZone(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    // Restaurar orden guardado
    restoreOrder(containerId);

    Sortable.create(container, {
        group: 'dashboard',          // permite mover cápsulas entre zonas
        animation: 200,
        handle: '.drag-handle',      // solo se arrastra desde el ícono
        ghostClass: 'kpi-ghost',
        chosenClass: 'kpi-chosen',
        dragClass: 'kpi-drag',
        onEnd: function () {
            saveOrder(containerId);
        }
    });
}

function saveOrder(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    const ids = [...container.children].map(el => el.id).filter(Boolean);
    localStorage.setItem(STORAGE_KEY_PREFIX + containerId, JSON.stringify(ids));
}

function restoreOrder(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const saved = localStorage.getItem(STORAGE_KEY_PREFIX + containerId);
    if (!saved) return;

    try {
        const ids = JSON.parse(saved);
        ids.forEach(id => {
            const el = document.getElementById(id);
            if (el && el.parentElement === container) {
                container.appendChild(el);   // reordena moviéndolos al final en orden
            }
        });
    } catch (e) {
        console.warn('dashboard.js: no se pudo restaurar el orden de ' + containerId, e);
    }
}

/** Resetea el orden de TODAS las zonas a su estado original */
function resetDashboardOrder() {
    ['k1-zone', 'k2-zone', 'k3-zone'].forEach(id => {
        localStorage.removeItem(STORAGE_KEY_PREFIX + id);
    });
    location.reload();
}

/* ══════════════════════════════════════════════════════════════════
   2.  EXPORT PDF — CÁPSULA INDIVIDUAL
   ══════════════════════════════════════════════════════════════════ */

/**
 * Exporta el contenido de una cápsula específica como PDF.
 * @param {string} capsuleId   ID del div de la cápsula o del modal-body
 * @param {string} titulo      Título del reporte
 */
async function exportarCapsulaPDF(capsuleId, titulo) {
    const { jsPDF } = window.jspdf;

    // Si es un modal, capturar el modal-body; si no, la cápsula directamente
    let elemento = document.getElementById(capsuleId + '-modal-body')
                || document.getElementById(capsuleId);

    if (!elemento) {
        alert('No se encontró el elemento para exportar.');
        return;
    }

    // Mostrar overlay de carga
    mostrarOverloadPDF(true);

    try {
        const canvas = await html2canvas(elemento, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff',
            logging: false
        });

        const imgData   = canvas.toDataURL('image/png');
        const pdf       = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
        const pageW     = pdf.internal.pageSize.getWidth();
        const pageH     = pdf.internal.pageSize.getHeight();
        const margin    = 10;
        const imgW      = pageW - margin * 2;
        const imgH      = (canvas.height * imgW) / canvas.width;

        // Encabezado
        pdf.setFontSize(14);
        pdf.setFont('helvetica', 'bold');
        pdf.text('Reporte: ' + titulo, margin, margin + 5);

        pdf.setFontSize(9);
        pdf.setFont('helvetica', 'normal');
        pdf.text('Generado: ' + new Date().toLocaleString('es-VE'), pageW - margin, margin + 5, { align: 'right' });

        pdf.line(margin, margin + 8, pageW - margin, margin + 8);

        // Imagen del contenido
        const startY = margin + 12;
        if (imgH + startY <= pageH - margin) {
            pdf.addImage(imgData, 'PNG', margin, startY, imgW, imgH);
        } else {
            // Contenido largo → paginar
            let yPos   = startY;
            let srcY   = 0;
            const ratio = canvas.width / imgW;

            while (srcY < canvas.height) {
                const sliceH    = Math.min((pageH - yPos - margin) * ratio, canvas.height - srcY);
                const sliceCanvas = document.createElement('canvas');
                sliceCanvas.width  = canvas.width;
                sliceCanvas.height = sliceH;
                sliceCanvas.getContext('2d').drawImage(canvas, 0, -srcY);
                pdf.addImage(sliceCanvas.toDataURL('image/png'), 'PNG', margin, yPos, imgW, sliceH / ratio);
                srcY += sliceH;
                if (srcY < canvas.height) {
                    pdf.addPage();
                    yPos = margin;
                }
            }
        }

        pdf.save('Reporte_' + titulo + '_' + fechaArchivo() + '.pdf');

    } catch (err) {
        console.error('Error al generar PDF:', err);
        alert('Error al generar el PDF. Revisa la consola para más detalles.');
    } finally {
        mostrarOverloadPDF(false);
    }
}

/* ══════════════════════════════════════════════════════════════════
   3.  REPORTE PDF GENERAL (selector de cápsulas)
   ══════════════════════════════════════════════════════════════════ */

/**
 * Abre el modal de selección de cápsulas para el reporte general.
 * Requiere el modal #reporteGeneralModal en Dashboard.php.
 */
function abrirModalReporteGeneral() {
    const modal = document.getElementById('reporteGeneralModal');
    if (!modal) { console.warn('Modal #reporteGeneralModal no encontrado'); return; }

    // Marcar todas por defecto
    modal.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = true);

    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    } else {
        $(modal).modal('show');   // Bootstrap 4
    }
}

/**
 * Genera el PDF con las cápsulas seleccionadas en el modal.
 */
async function generarReporteGeneral() {
    const modal       = document.getElementById('reporteGeneralModal');
    const checkboxes  = [...modal.querySelectorAll('input[type=checkbox]:checked')];
    const capsuleIds  = checkboxes.map(cb => cb.value);

    if (capsuleIds.length === 0) {
        alert('Selecciona al menos una cápsula para exportar.');
        return;
    }

    // Compatible con Bootstrap 4 (jQuery) y Bootstrap 5
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal && bootstrap.Modal.getInstance) {
        bootstrap.Modal.getInstance(modal).hide();
    } else {
        $(modal).modal('hide');   // Bootstrap 4
    }
    mostrarOverloadPDF(true);

    const { jsPDF } = window.jspdf;
    const pdf    = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
    const pageW  = pdf.internal.pageSize.getWidth();
    const pageH  = pdf.internal.pageSize.getHeight();
    const margin = 10;

    // Portada
    pdf.setFontSize(20);
    pdf.setFont('helvetica', 'bold');
    pdf.text('Reporte de Dashboard', pageW / 2, pageH / 2 - 10, { align: 'center' });
    pdf.setFontSize(11);
    pdf.setFont('helvetica', 'normal');
    pdf.text('Fecha: ' + new Date().toLocaleString('es-VE'), pageW / 2, pageH / 2 + 2, { align: 'center' });

    try {
        for (let i = 0; i < capsuleIds.length; i++) {
            const id      = capsuleIds[i];
            const label   = checkboxes[i].dataset.label || id;
            const elem    = document.getElementById(id + '-capsule')
                         || document.getElementById(id);

            if (!elem) continue;

            pdf.addPage();

            // Encabezado de sección
            pdf.setFontSize(13);
            pdf.setFont('helvetica', 'bold');
            pdf.text(label, margin, margin + 5);
            pdf.setFontSize(9);
            pdf.setFont('helvetica', 'normal');
            pdf.text('Sección ' + (i + 1) + ' de ' + capsuleIds.length, pageW - margin, margin + 5, { align: 'right' });
            pdf.line(margin, margin + 8, pageW - margin, margin + 8);

            const canvas  = await html2canvas(elem, { scale: 2, useCORS: true, backgroundColor: '#ffffff', logging: false });
            const imgData = canvas.toDataURL('image/png');
            const imgW    = pageW - margin * 2;
            const imgH    = (canvas.height * imgW) / canvas.width;
            const startY  = margin + 12;

            if (imgH + startY <= pageH - margin) {
                pdf.addImage(imgData, 'PNG', margin, startY, imgW, imgH);
            } else {
                pdf.addImage(imgData, 'PNG', margin, startY, imgW, pageH - startY - margin);
            }
        }

        pdf.save('Reporte_Dashboard_' + fechaArchivo() + '.pdf');

    } catch (err) {
        console.error('Error generando reporte general:', err);
        alert('Error al generar el reporte. Revisa la consola.');
    } finally {
        mostrarOverloadPDF(false);
    }
}

/* ══════════════════════════════════════════════════════════════════
   4.  HELPERS
   ══════════════════════════════════════════════════════════════════ */

function fechaArchivo() {
    const d = new Date();
    return d.getFullYear() + '-' +
           String(d.getMonth() + 1).padStart(2, '0') + '-' +
           String(d.getDate()).padStart(2, '0');
}

function mostrarOverloadPDF(show) {
    let overlay = document.getElementById('pdf-loading-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'pdf-loading-overlay';
        overlay.innerHTML = `
            <div style="
                position:fixed; inset:0; background:rgba(0,0,0,.5);
                display:flex; flex-direction:column;
                align-items:center; justify-content:center;
                z-index:99999; color:#fff; font-size:1.1rem; gap:1rem;
            ">
                <div class="spinner-border text-light" role="status"></div>
                <span>Generando PDF…</span>
            </div>
        `;
        document.body.appendChild(overlay);
    }
    overlay.style.display = show ? 'block' : 'none';
}

/* ══════════════════════════════════════════════════════════════════
   5.  INICIALIZACIÓN
   ══════════════════════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', function () {
    initSortableZone('k1-zone');
    initSortableZone('k2-zone');
    initSortableZone('k3-zone');
});