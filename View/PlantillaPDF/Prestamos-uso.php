<?php
/**
 * Prestamos-uso.php  — PDF Tasa de Uso de Préstamos
 * Secciones (orientación landscape, A4):
 *   1. KPIs generales
 *   2. Resumen + detalle de préstamos del mes
 *   3. Uso por departamento  |  Top 5 mayor deuda  (en la misma fila, layout tabla)
 *   4. Trabajadores sin préstamos activos
 *
 * Ubicación: View/PlantillaPDF/Prestamos-uso.php
 */
session_start();
if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }

include '../../PHP/CLASS/conexion_Original.php';
include '../../PHP/CLASS/user_Original.php';

$db = $Nomina->connect_db();

/* ── Empleados activos ──────────────────────────────── */
$todosEmp = $Empleado->View();
$totalEmp = count($todosEmp);

/* ── Cédulas con préstamo activo ────────────────────── */
$r = $db->query("SELECT DISTINCT cedula_FK FROM prestamos WHERE estado = 1");
$cedsConPrestamo = [];
while ($row = $r->fetch_assoc()) $cedsConPrestamo[$row['cedula_FK']] = true;
$conPrestamoCnt = count($cedsConPrestamo);
$sinPrestamo    = $totalEmp - $conPrestamoCnt;
$tasaUso        = $totalEmp > 0 ? round(($conPrestamoCnt / $totalEmp) * 100, 2) : 0;
$estadoLabel    = $tasaUso <= 40 ? 'BAJO' : ($tasaUso <= 60 ? 'MODERADO' : 'ALTO');
$estadoColor    = $tasaUso <= 40 ? '#166534' : ($tasaUso <= 60 ? '#854d0e' : '#991b1b');
$estadoBg       = $tasaUso <= 40 ? '#dcfce7' : ($tasaUso <= 60 ? '#fef9c3' : '#fee2e2');

/* ── Préstamos del mes actual ───────────────────────── */
$mesActual  = date('Y-m');
$mesIdx     = (int)date('n') - 1;
$mesesNom   = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
               'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
$mesNombre  = $mesesNom[$mesIdx];
$anioActual = date('Y');

$mesEsc  = $db->real_escape_string($mesActual);
$rMes    = $db->query("
    SELECT p.id_prestamos, e.nombre, e.apellido, e.cedula,
           e.departamento, e.cargo,
           p.monto, p.descuento, p.cuotas, p.concepto, p.fecha, p.date_limit
    FROM prestamos p
    INNER JOIN empleados e ON p.cedula_FK = e.cedula
    WHERE p.estado = 1
      AND DATE_FORMAT(p.fecha,'%Y-%m') = '$mesEsc'
    ORDER BY p.fecha DESC
");
$prestamosMes  = [];
$montoTotalMes = 0;
while ($row = $rMes->fetch_assoc()) {
    $prestamosMes[] = $row;
    $montoTotalMes += (float)$row['monto'];
}
$cantMes = count($prestamosMes);
$promMes = $cantMes > 0 ? round($montoTotalMes / $cantMes, 2) : 0;

/* ── Por departamento ───────────────────────────────── */
$deptos = [];
foreach ($todosEmp as $e) {
    $dep = trim($e['departamento'] ?? '') ?: 'Sin depto.';
    if (!isset($deptos[$dep])) $deptos[$dep] = ['total' => 0, 'con_prestamo' => 0];
    $deptos[$dep]['total']++;
    if (isset($cedsConPrestamo[$e['cedula']])) $deptos[$dep]['con_prestamo']++;
}
uasort($deptos, fn($a, $b) => $b['con_prestamo'] <=> $a['con_prestamo']);

/* ── Top 5 mayor deuda ──────────────────────────────── */
$activos = $Nomina->Prestamos_View_report();
usort($activos, fn($a, $b) => (float)$b['monto_desc'] <=> (float)$a['monto_desc']);
$top5 = array_slice($activos, 0, 5);

/* ── Sin préstamo ───────────────────────────────────── */
$sinLista = [];
foreach ($todosEmp as $e) {
    if (!isset($cedsConPrestamo[$e['cedula']])) $sinLista[] = $e;
}
usort($sinLista, fn($a, $b) => strcmp($a['nombre'], $b['nombre']));

/* ── Helper ─────────────────────────────────────────── */
function fmtN($n, $d = 2) { return number_format((float)$n, $d, ',', '.'); }
function fmtF($f)          { return $f ? date('d/m/Y', strtotime($f)) : '—'; }

/* ─────────────────────────────────────────────────────
   HTML
──────────────────────────────────────────────────── */
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte — Tasa de Uso de Préstamos</title>
<style>
*   { box-sizing:border-box; margin:0; padding:0; }
body{ font-family:Arial,sans-serif; font-size:10px; color:#1a1a1a; }

/* ── encabezado ── */
.hdr          { border-bottom:3px solid #0f2027; padding-bottom:7px; margin-bottom:12px; }
.hdr table    { width:100%; border-collapse:collapse; }
.hdr td       { border:none; vertical-align:middle; }
.logo         { width:60px; height:60px; }
.co-name      { font-size:13px; font-weight:bold; color:#0f2027; }
.co-sub       { font-size:10px; color:#4b5563; margin-top:2px; }
.hdr-right    { text-align:right; font-size:9px; color:#6b7280; }

/* ── título sección ── */
.stitle       { font-size:10.5px; font-weight:bold; color:#0f2027;
                border-left:4px solid #3b82f6; padding-left:6px;
                margin:11px 0 6px 0; }

/* ── KPI strip ── */
.kpi-wrap     { width:100%; border-collapse:separate; border-spacing:4px; margin-bottom:2px; }
.kpi-cell     { background:#f8fafc; border:1px solid #e2e8f0; border-radius:5px;
                padding:8px 5px; text-align:center; vertical-align:middle; }
.kpi-lbl      { font-size:8px; color:#6b7280; display:block; text-transform:uppercase;
                letter-spacing:.3px; margin-bottom:2px; }
.kpi-val      { font-size:14px; font-weight:bold; color:#0f2027; }
.kpi-sub      { font-size:8px; color:#9ca3af; display:block; margin-top:1px; }

/* ── tablas de datos ── */
.dt           { width:100%; border-collapse:collapse; font-size:9px; }
.dt thead tr  { background:#0f2027; color:#fff; }
.dt th        { padding:4px 4px; font-size:8.5px; text-align:left; }
.dt th.r      { text-align:right; }
.dt th.c      { text-align:center; }
.dt td        { padding:4px 4px; border-bottom:1px solid #e5e7eb; color:#374151; }
.dt td.r      { text-align:right; }
.dt td.c      { text-align:center; }
.dt tbody tr:nth-child(even) td { background:#f9fafb; }
.dt tfoot td  { background:#f1f5f9; font-weight:bold; font-size:9px;
                border-top:2px solid #0f2027; padding:4px; }

/* ── badge inline ── */
.bdg          { display:inline-block; border-radius:3px; padding:2px 5px;
                font-size:8px; font-weight:bold; }

/* ── footer ── */
.footer       { margin-top:16px; border-top:1px solid #e5e7eb; padding-top:6px;
                font-size:8.5px; color:#9ca3af; text-align:right; }
</style>
</head>
<body>

<!-- ENCABEZADO -->
<div class="hdr">
    <table>
        <tr>
            <td style="width:68px;">
                <img src="http://<?= $_SERVER['HTTP_HOST'] ?>/PIUT_V1/IMG/Logo_Comple_Black.png" class="logo">
            </td>
            <td>
                <span class="co-name">DISORIENT, C.A.</span>
                <div class="co-sub">Reporte de Tasa de Uso de Préstamos</div>
            </td>
            <td class="hdr-right">
                Emisión: <strong><?= date('d/m/Y H:i') ?></strong><br>
                Período: <strong><?= $mesNombre . ' ' . $anioActual ?></strong>
            </td>
        </tr>
    </table>
</div>

<!-- KPIs GENERALES -->
<div class="stitle">Indicadores Generales</div>
<table class="kpi-wrap">
    <tr>
        <td class="kpi-cell">
            <span class="kpi-lbl">Tasa de Uso</span>
            <span class="kpi-val"><?= $tasaUso ?>%</span>
            <span class="kpi-sub"><?= $conPrestamoCnt ?> de <?= $totalEmp ?> emp.</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Con Préstamo Activo</span>
            <span class="kpi-val"><?= $conPrestamoCnt ?></span>
            <span class="kpi-sub">empleados</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Sin Préstamo</span>
            <span class="kpi-val"><?= $sinPrestamo ?></span>
            <span class="kpi-sub">empleados activos</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Total Empleados</span>
            <span class="kpi-val"><?= $totalEmp ?></span>
            <span class="kpi-sub">activos en nómina</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Estado</span>
            <span class="kpi-val">
                <span class="bdg" style="background:<?= $estadoBg ?>; color:<?= $estadoColor ?>;">
                    <?= $estadoLabel ?>
                </span>
            </span>
        </td>
    </tr>
</table>

<!-- RESUMEN MES -->
<div class="stitle" style="border-left-color:#10b981;">
    Préstamos Otorgados — <?= $mesNombre . ' ' . $anioActual ?>
</div>
<table class="kpi-wrap">
    <tr>
        <td class="kpi-cell">
            <span class="kpi-lbl">Cantidad otorgada</span>
            <span class="kpi-val"><?= $cantMes ?></span>
            <span class="kpi-sub">en el mes</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Monto total del mes</span>
            <span class="kpi-val"><?= fmtN($montoTotalMes) ?> $</span>
            <span class="kpi-sub">suma</span>
        </td>
        <td class="kpi-cell">
            <span class="kpi-lbl">Promedio por préstamo</span>
            <span class="kpi-val"><?= fmtN($promMes) ?> $</span>
            <span class="kpi-sub">este mes</span>
        </td>
    </tr>
</table>

<!-- DETALLE MES -->
<?php if (!empty($prestamosMes)): ?>
<div class="stitle" style="margin-top:9px; border-left-color:#10b981;">
    Detalle de Préstamos — <?= $mesNombre . ' ' . $anioActual ?>
</div>
<table class="dt">
    <thead>
        <tr>
            <th>Empleado</th>
            <th>Cédula</th>
            <th>Departamento</th>
            <th>Cargo</th>
            <th class="r">Monto $</th>
            <th class="r">Cuota Sem. $</th>
            <th class="c">Cuotas</th>
            <th>Concepto</th>
            <th class="c">Fecha</th>
            <th class="c">Vence</th>
        </tr>
    </thead>
    <tbody>
    <?php $totMes = 0; foreach ($prestamosMes as $p): $totMes += (float)$p['monto']; ?>
        <tr>
            <td><?= htmlspecialchars($p['nombre'].' '.$p['apellido']) ?></td>
            <td><?= $p['cedula'] ?></td>
            <td><?= htmlspecialchars($p['departamento'] ?? '—') ?></td>
            <td><?= htmlspecialchars($p['cargo'] ?? '—') ?></td>
            <td class="r" style="font-weight:bold;"><?= fmtN($p['monto']) ?></td>
            <td class="r"><?= fmtN($p['descuento']) ?></td>
            <td class="c"><?= $p['cuotas'] ?></td>
            <td><?= htmlspecialchars($p['concepto'] ?? '—') ?></td>
            <td class="c"><?= fmtF($p['fecha']) ?></td>
            <td class="c"><?= fmtF($p['date_limit']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" style="text-align:right; padding-right:5px;">TOTAL MES</td>
            <td class="r" style="color:#0f2027;"><?= fmtN($totMes) ?></td>
            <td colspan="5"></td>
        </tr>
    </tfoot>
</table>
<?php else: ?>
<p style="color:#9ca3af; text-align:center; padding:12px; font-style:italic; font-size:9px;">
    No se registraron préstamos en <?= $mesNombre . ' ' . $anioActual ?>.
</p>
<?php endif; ?>

<!-- USO POR DEPTO + TOP 5  — misma fila, sin floats -->
<table style="width:100%; border-collapse:collapse; margin-top:10px;">
    <tr style="vertical-align:top;">

        <!-- columna izquierda: Departamentos -->
        <td style="width:50%; padding-right:8px;">
            <div class="stitle" style="border-left-color:#6366f1; margin-top:0;">Uso por Departamento</div>
            <table class="dt">
                <thead>
                    <tr>
                        <th>Departamento</th>
                        <th class="c">Total Emp.</th>
                        <th class="c">Con Prést.</th>
                        <th class="c">% Uso</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($deptos as $depNom => $dv):
                    $pct      = $dv['total'] > 0 ? round(($dv['con_prestamo'] / $dv['total']) * 100, 1) : 0;
                    $barClr   = $pct > 60 ? '#ef4444' : ($pct > 40 ? '#f59e0b' : '#22c55e');
                ?>
                    <tr>
                        <td><?= htmlspecialchars($depNom) ?></td>
                        <td class="c"><?= $dv['total'] ?></td>
                        <td class="c"><?= $dv['con_prestamo'] ?></td>
                        <td class="c" style="color:<?= $barClr ?>; font-weight:bold;"><?= $pct ?>%</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </td>

        <!-- separador visual -->
        <td style="width:1px; background:#e2e8f0; padding:0;"></td>

        <!-- columna derecha: Top 5 -->
        <td style="width:50%; padding-left:8px;">
            <div class="stitle" style="border-left-color:#ef4444; margin-top:0;">Top 5 — Mayor Deuda Pendiente</div>
            <table class="dt">
                <thead>
                    <tr>
                        <th class="c">#</th>
                        <th>Empleado</th>
                        <th>Cédula</th>
                        <th class="r">Monto Orig. $</th>
                        <th class="r">Pendiente $</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($top5 as $ii => $p): ?>
                    <tr <?= $ii===0 ? 'style="background:#fffbeb;"' : '' ?>>
                        <td class="c" style="font-weight:bold;">
                            <?= $ii===0?'1°':($ii===1?'2°':($ii===2?'3°':($ii+1).'.')) ?>
                        </td>
                        <td style="font-weight:<?= $ii===0?'bold':'normal' ?>;">
                            <?= htmlspecialchars($p['nombre'].' '.$p['apellido']) ?>
                        </td>
                        <td><?= $p['cedula'] ?></td>
                        <td class="r"><?= fmtN($p['monto']) ?></td>
                        <td class="r" style="color:#dc2626; font-weight:bold;"><?= fmtN($p['monto_desc']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </td>
    </tr>
</table>

<!-- TRABAJADORES SIN PRÉSTAMO -->
<div class="stitle" style="margin-top:12px; border-left-color:#dc2626;">
    Trabajadores Sin Préstamos Activos
    <span class="bdg" style="background:#fee2e2; color:#991b1b; margin-left:5px;
                              font-size:8.5px;"><?= count($sinLista) ?> trabajadores</span>
</div>
<?php if (!empty($sinLista)): ?>
<table class="dt">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Cédula</th>
            <th>Departamento</th>
            <th>Cargo</th>
            <th class="c">F. Ingreso</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($sinLista as $e): ?>
        <tr>
            <td><?= htmlspecialchars($e['nombre']) ?></td>
            <td><?= htmlspecialchars($e['apellido']) ?></td>
            <td><?= $e['cedula'] ?></td>
            <td><?= htmlspecialchars($e['departamento'] ?? '—') ?></td>
            <td><?= htmlspecialchars($e['cargo'] ?? '—') ?></td>
            <td class="c"><?= isset($e['f_ingreso']) ? fmtF($e['f_ingreso']) : '—' ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style="text-align:right; padding-right:5px;">TOTAL SIN PRÉSTAMO:</td>
            <td colspan="4"><strong><?= count($sinLista) ?> de <?= $totalEmp ?> empleados activos</strong></td>
        </tr>
    </tfoot>
</table>
<?php else: ?>
<p style="color:#16a34a; padding:10px; text-align:center; font-style:italic; font-size:9px;">
    Todos los empleados activos tienen al menos un préstamo activo.
</p>
<?php endif; ?>

<!-- FOOTER -->
<div class="footer">
    Generado el <?= date('d/m/Y') ?> a las <?= date('H:i') ?>
    &nbsp;|&nbsp; DISORIENT, C.A. &nbsp;|&nbsp; Sistema de Nómina
</div>

</body>
</html>
<?php
$html = ob_get_clean();

require_once '../../PHP/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$dompdf  = new Dompdf();
$options = $dompdf->getOptions();
$options->set(['isRemoteEnabled' => true]);
$dompdf->setOptions($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('Prestamos-uso-' . date('Y-m') . '.pdf', ['Attachment' => false]);
