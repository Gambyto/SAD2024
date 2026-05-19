<?php
/**
 * Prestamos_Controller.php  — v2
 * Controlador AJAX para los 4 indicadores de préstamos.
 *
 * ACCIONES:
 *   tasa_uso             → Slide 0  (+ sin_prestamo_lista)
 *   promedio_prestamos   → Slide 1  (+ por_trabajador con totales pagado/pendiente)
 *   tasa_reembolso       → Slide 2  (+ top_trabajadores ranking)
 *   frecuencia_renovacion→ Slide 3  (+ detalle_mes para tabla mensual)
 *
 * Ubicación sugerida: PHP/CTR/Prestamos_Controller.php
 */
include_once '../CLASS/user_Original.php';

if (!isset($_GET['action'])) exit;

header('Content-Type: application/json');

$db = $Nomina->connect_db();

try {
    $action = $_GET['action'];

    /* ══════════════════════════════════════════════════
     *  1. TASA DE USO
     *  Nuevo: sin_prestamo_lista — empleados activos sin préstamo activo
     * ══════════════════════════════════════════════════ */
    if ($action === 'tasa_uso') {

        // Empleados activos
        $todosEmp = $Empleado->View();
        $totalEmp = count($todosEmp);

        // Cédulas con préstamo activo
        $r = $db->query("SELECT COUNT(DISTINCT cedula_FK) AS cnt FROM prestamos WHERE estado = 1");
        $conPrestamoCnt = (int)$r->fetch_assoc()['cnt'];
        $sinPrestamo    = $totalEmp - $conPrestamoCnt;
        $promedio       = $totalEmp > 0 ? round(($conPrestamoCnt / $totalEmp) * 100, 2) : 0;

        $rCed = $db->query("SELECT DISTINCT cedula_FK FROM prestamos WHERE estado = 1");
        $cedsConPrestamo = [];
        while ($row = $rCed->fetch_assoc()) $cedsConPrestamo[$row['cedula_FK']] = true;

        // Por departamento
        $deptos = [];
        foreach ($todosEmp as $e) {
            $dep = $e['departamento'] ?? 'Sin depto.';
            if (!isset($deptos[$dep])) $deptos[$dep] = ['total' => 0, 'con_prestamo' => 0];
            $deptos[$dep]['total']++;
            if (isset($cedsConPrestamo[$e['cedula']])) $deptos[$dep]['con_prestamo']++;
        }
        $deptoData = [];
        foreach ($deptos as $nombre => $vals) {
            $pct = $vals['total'] > 0 ? round(($vals['con_prestamo'] / $vals['total']) * 100, 1) : 0;
            $deptoData[] = [
                'departamento' => $nombre,
                'porcentaje'   => $pct,
                'total'        => $vals['total'],
                'con_prestamo' => $vals['con_prestamo'],
            ];
        }
        usort($deptoData, fn($a, $b) => $b['porcentaje'] <=> $a['porcentaje']);

        // Top 5 deuda
        $activos = $Nomina->Prestamos_View_report();
        usort($activos, fn($a, $b) => $b['monto_desc'] <=> $a['monto_desc']);

        // ★ Lista de trabajadores SIN préstamo activo
        $sinPrestamoLista = [];
        foreach ($todosEmp as $e) {
            if (!isset($cedsConPrestamo[$e['cedula']])) {
                $sinPrestamoLista[] = [
                    'nombre'       => $e['nombre'],
                    'apellido'     => $e['apellido'],
                    'cedula'       => $e['cedula'],
                    'departamento' => $e['departamento'] ?? '—',
                    'cargo'        => $e['cargo'] ?? '—',
                ];
            }
        }
        usort($sinPrestamoLista, fn($a, $b) => strcmp($a['nombre'], $b['nombre']));

        echo json_encode([
            'promedio'            => $promedio,
            'total_empleados'     => $totalEmp,
            'con_prestamo'        => $conPrestamoCnt,
            'sin_prestamo'        => $sinPrestamo,
            'por_departamento'    => $deptoData,
            'top5_deuda'          => array_slice($activos, 0, 5),
            'sin_prestamo_lista'  => $sinPrestamoLista,
        ]);
        exit;
    }

    /* ══════════════════════════════════════════════════
     *  2. PROMEDIO DE PRÉSTAMOS
     *  Nuevo: por_trabajador — agrupado por cédula con totales pagado/pendiente
     *         Los filtros (pagados/pendientes/todos) se aplican en el JS.
     * ══════════════════════════════════════════════════ */
    if ($action === 'promedio_prestamos') {

        // Historial mensual
        $rHist = $db->query("
            SELECT
                YEAR(fecha)                      AS anio,
                MONTH(fecha)                     AS mes,
                ROUND(AVG(monto), 2)             AS promedio_mensual,
                ROUND(AVG(monto) / 4.33, 2)      AS promedio_semana
            FROM prestamos
            WHERE estado = 1
            GROUP BY YEAR(fecha), MONTH(fecha)
            ORDER BY anio DESC, mes DESC
        ");

        $meses_n = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        $rawHist = [];
        while ($row = $rHist->fetch_assoc()) $rawHist[] = $row;

        $historial = [];
        foreach ($rawHist as $fila) {
            $historial[] = [
                'mes'     => $meses_n[(int)$fila['mes']] . ' ' . $fila['anio'],
                'mensual' => (float)$fila['promedio_mensual'],
                'semanal' => (float)$fila['promedio_semana'],
            ];
        }
        $historial   = array_reverse($historial);
        $montos      = array_column($historial, 'mensual');
        $actual_mens = isset($rawHist[0]) ? (float)$rawHist[0]['promedio_mensual'] : 0;
        $actual_sem  = isset($rawHist[0]) ? (float)$rawHist[0]['promedio_semana']  : 0;

        // ★ Agrupado por trabajador (todos los préstamos activos, estado=1)
        $rTrab = $db->query("
            SELECT
                e.cedula,
                e.nombre,
                e.apellido,
                e.departamento,
                e.cargo,
                COUNT(p.id_prestamos)                  AS cantidad,
                SUM(p.monto)                           AS monto_total,
                SUM(p.monto - p.monto_desc)            AS pagado_total,
                SUM(p.monto_desc)                      AS pendiente
            FROM prestamos p
            INNER JOIN empleados e ON p.cedula_FK = e.cedula
            WHERE p.estado = 1
            GROUP BY e.cedula, e.nombre, e.apellido, e.departamento, e.cargo
            ORDER BY pendiente DESC
        ");
        $porTrabajador = [];
        while ($row = $rTrab->fetch_assoc()) {
            $porTrabajador[] = [
                'cedula'       => $row['cedula'],
                'nombre'       => $row['nombre'],
                'apellido'     => $row['apellido'],
                'departamento' => $row['departamento'],
                'cargo'        => $row['cargo'],
                'cantidad'     => (int)$row['cantidad'],
                'monto_total'  => (float)$row['monto_total'],
                'pagado_total' => (float)$row['pagado_total'],
                'pendiente'    => (float)$row['pendiente'],
            ];
        }

        echo json_encode([
            'historial'         => $historial,
            'max'               => !empty($montos) ? max($montos) : 0,
            'promedio'          => !empty($montos) ? round(array_sum($montos) / count($montos), 2) : 0,
            'actual_mensual'    => $actual_mens,
            'actual_semanal'    => $actual_sem,
            'por_trabajador'    => $porTrabajador,
        ]);
        exit;
    }

    /* ══════════════════════════════════════════════════
     *  3. TASA DE REEMBOLSO
     *  Nuevo: top_trabajadores — ranking por cantidad de préstamos adquiridos
     * ══════════════════════════════════════════════════ */
    if ($action === 'tasa_reembolso') {

        // Balance anual
        $rBal = $db->query("
            SELECT
                YEAR(fecha)                 AS anio,
                SUM(monto)                  AS total_prestado,
                SUM(monto - monto_desc)     AS total_reembolsado
            FROM prestamos
            WHERE estado = 1
            GROUP BY YEAR(fecha)
            ORDER BY anio DESC
        ");
        $anual    = [];
        $balance0 = null;
        while ($row = $rBal->fetch_assoc()) {
            if ($balance0 === null) $balance0 = $row;
            $pct = $row['total_prestado'] > 0
                ? round(($row['total_reembolsado'] / $row['total_prestado']) * 100, 2)
                : 0;
            $anual[] = [
                'anio'              => $row['anio'],
                'total_prestado'    => (float)$row['total_prestado'],
                'total_reembolsado' => (float)$row['total_reembolsado'],
                'pendiente'         => round((float)$row['total_prestado'] - (float)$row['total_reembolsado'], 2),
                'porcentaje'        => $pct,
            ];
        }

        $global = ($balance0 && $balance0['total_prestado'] > 0)
            ? round(($balance0['total_reembolsado'] / $balance0['total_prestado']) * 100, 2)
            : 0;

        // Vencidos y detalle
        $vencidos = $Nomina->Prestamos_Vencidos();
        $todos    = $Nomina->Prestamos_View_report();

        $detalle = [];
        foreach ($todos as $p) {
            $progreso = $p['monto'] > 0
                ? round((1 - $p['monto_desc'] / $p['monto']) * 100, 1)
                : 100;
            $detalle[] = [
                'nombre'    => $p['nombre'] . ' ' . $p['apellido'],
                'cedula'    => $p['cedula'],
                'monto'     => (float)$p['monto'],
                'pendiente' => (float)$p['monto_desc'],
                'pagado'    => round((float)$p['monto'] - (float)$p['monto_desc'], 2),
                'progreso'  => $progreso,
                'vencido'   => ($p['date_limit'] && $p['date_limit'] < date('Y-m-d') && $p['monto_desc'] > 0) ? 1 : 0,
            ];
        }

        // ★ Ranking: trabajadores con más préstamos (total histórico estado=1)
        $rTop = $db->query("
            SELECT
                e.cedula,
                e.nombre,
                e.apellido,
                e.departamento,
                COUNT(p.id_prestamos)       AS cantidad,
                SUM(p.monto)                AS monto_total,
                SUM(p.monto_desc)           AS deuda_pendiente
            FROM prestamos p
            INNER JOIN empleados e ON p.cedula_FK = e.cedula
            WHERE p.estado = 1
            GROUP BY e.cedula, e.nombre, e.apellido, e.departamento
            ORDER BY cantidad DESC, monto_total DESC
            LIMIT 10
        ");
        $topTrabajadores = [];
        while ($row = $rTop->fetch_assoc()) {
            $topTrabajadores[] = [
                'cedula'          => $row['cedula'],
                'nombre'          => $row['nombre'],
                'apellido'        => $row['apellido'],
                'departamento'    => $row['departamento'],
                'cantidad'        => (int)$row['cantidad'],
                'monto_total'     => (float)$row['monto_total'],
                'deuda_pendiente' => (float)$row['deuda_pendiente'],
            ];
        }

        echo json_encode([
            'global'           => $global,
            'por_anio'         => $anual,
            'vencidos_cnt'     => (int)($vencidos['cantidad']     ?? 0),
            'vencidos_monto'   => (float)($vencidos['monto_total'] ?? 0),
            'detalle'          => array_slice($detalle, 0, 25),
            'top_trabajadores' => $topTrabajadores,
        ]);
        exit;
    }

    /* ══════════════════════════════════════════════════
     *  4. FRECUENCIA DE RENOVACIÓN
     *  Nuevo: detalle_mes — todos los préstamos activos con fecha para
     *         filtrar por mes en el JS y mostrar tabla + resumen mensual.
     * ══════════════════════════════════════════════════ */
    if ($action === 'frecuencia_renovacion') {

        // Total
        $rTotal = $db->query("SELECT COUNT(*) AS total FROM prestamos WHERE estado = 1");
        $prestamos_realizados = (int)$rTotal->fetch_assoc()['total'];
        $frecuency = round(($prestamos_realizados * 0.033) * 100, 2);

        // Tendencia mensual (últimos 12 meses)
        $meses_n = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        $todos   = $Nomina->Prestamos_View_report();
        $porMes  = [];
        foreach ($todos as $p) {
            $key = date('Y-m', strtotime($p['fecha']));
            $porMes[$key] = ($porMes[$key] ?? 0) + 1;
        }
        ksort($porMes);
        $tendencia = [];
        foreach ($porMes as $mes => $cnt) {
            list($y, $m) = explode('-', $mes);
            $tendencia[] = ['mes' => $meses_n[(int)$m] . ' ' . $y, 'cantidad' => $cnt];
        }
        $tendencia = array_reverse(array_slice(array_reverse($tendencia), 0, 12));

        // Renovaciones
        $cedulaCount  = array_count_values(array_column($todos, 'cedula'));
        $renovaciones = array_filter($cedulaCount, fn($c) => $c > 1);

        // ★ Detalle mes — incluye todos los campos necesarios para la tabla mensual
        $detalleMes = [];
        foreach ($todos as $p) {
            $detalleMes[] = [
                'id'        => $p['id_prestamos'],
                'cedula'    => $p['cedula'],
                'nombre'    => $p['nombre'],
                'apellido'  => $p['apellido'],
                'monto'     => (float)$p['monto'],
                'monto_desc'=> (float)$p['monto_desc'],
                'descuento' => (float)$p['descuento'],
                'cuotas'    => $p['cuotas'],
                'concepto'  => $p['concepto'],
                'fecha'     => $p['fecha'],
                'date_limit'=> $p['date_limit'],
            ];
        }
        // Ordenar por fecha descendente
        usort($detalleMes, fn($a, $b) => strcmp($b['fecha'], $a['fecha']));

        echo json_encode([
            'frecuency'          => $frecuency,
            'prestamos_totales'  => $prestamos_realizados,
            'renovaciones'       => array_sum($renovaciones) - count($renovaciones),
            'empleados_multi'    => count($renovaciones),
            'tendencia_mensual'  => $tendencia,
            'detalle_mes'        => $detalleMes,
        ]);
        exit;
    }

    http_response_code(400);
    echo json_encode(['error' => 'Acción no reconocida']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>