<?php
/**
 * Prestamos_Controller.php
 * Controlador AJAX para los 4 indicadores de préstamos.
 * Ubicación sugerida: PHP/CTR/Prestamos_Controller.php
 *
 * CORRECCIONES APLICADAS:
 * - PromedioPrestamos()        → tenía WHERE 1, sin filtro de estado. Reemplazado por query directa.
 * - Total_Prestamos()          → usaba vista_total_prestamos (sin garantía de filtro). Reemplazado.
 * - Balance_Prestamos()        → usaba vista_balance_prestamos (sin garantía de filtro). Reemplazado.
 * - View_Promedio_Prestamos()  → usaba vista_promedio_prestamos (sin garantía de filtro). Reemplazado.
 * - Prestamos_View_report()    ✅ ya filtra estado=1 — se mantiene.
 * - Prestamos_View_Modal()     ✅ ya filtra estado=1 — se mantiene.
 * - Prestamos_Vencidos()       ✅ ya filtra estado=1 — se mantiene.
 * - Empleado->View()           ✅ ya filtra estado='1' — se mantiene.
 */
include_once '../CLASS/user_Original.php';

if (!isset($_GET['action'])) exit;

header('Content-Type: application/json');

$db = $Nomina->connect_db();

try {
    $action = $_GET['action'];

    /* ══════════════════════════════════════════════════
     *  1. TASA DE USO
     * ══════════════════════════════════════════════════ */
    if ($action === 'tasa_uso') {

        // Empleados activos (Empleado->View() filtra estado='1')
        $todosEmp = $Empleado->View();
        $totalEmp = count($todosEmp);

        // Empleados DISTINTOS con al menos un préstamo activo (estado=1)
        // CORRECCIÓN: PromedioPrestamos() tenía "WHERE 1" sin filtrar estado
        $r = $db->query("SELECT COUNT(DISTINCT cedula_FK) AS cnt FROM prestamos WHERE estado = 1");
        $conPrestamoCnt = (int)$r->fetch_assoc()['cnt'];
        $sinPrestamo    = $totalEmp - $conPrestamoCnt;
        $promedio       = $totalEmp > 0 ? round(($conPrestamoCnt / $totalEmp) * 100, 2) : 0;

        // Cédulas con préstamo activo para cruzar con departamentos
        $rCed = $db->query("SELECT DISTINCT cedula_FK FROM prestamos WHERE estado = 1");
        $cedsConPrestamo = [];
        while ($row = $rCed->fetch_assoc()) $cedsConPrestamo[$row['cedula_FK']] = true;

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

        // Top 5 deuda — Prestamos_View_report() ya filtra estado=1
        $activos = $Nomina->Prestamos_View_report();
        usort($activos, fn($a, $b) => $b['monto_desc'] <=> $a['monto_desc']);

        echo json_encode([
            'promedio'         => $promedio,
            'total_empleados'  => $totalEmp,
            'con_prestamo'     => $conPrestamoCnt,
            'sin_prestamo'     => $sinPrestamo,
            'por_departamento' => $deptoData,
            'top5_deuda'       => array_slice($activos, 0, 5),
        ]);
        exit;
    }

    /* ══════════════════════════════════════════════════
     *  2. PROMEDIO DE PRÉSTAMOS
     *  CORRECCIÓN: View_Promedio_Prestamos() usa vista_promedio_prestamos
     *  cuyo filtro de estado es desconocido. Query directa con estado=1.
     * ══════════════════════════════════════════════════ */
    if ($action === 'promedio_prestamos') {

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
        $historial    = array_reverse($historial);
        $montos       = array_column($historial, 'mensual');
        $actual_mens  = isset($rawHist[0]) ? (float)$rawHist[0]['promedio_mensual'] : 0;
        $actual_sem   = isset($rawHist[0]) ? (float)$rawHist[0]['promedio_semana']  : 0;

        // Tabla de detalle — Prestamos_View_Modal() ya filtra estado=1
        $activos = $Nomina->Prestamos_View_Modal();

        echo json_encode([
            'historial'         => $historial,
            'max'               => !empty($montos) ? max($montos) : 0,
            'promedio'          => !empty($montos) ? round(array_sum($montos) / count($montos), 2) : 0,
            'actual_mensual'    => $actual_mens,
            'actual_semanal'    => $actual_sem,
            'prestamos_activos' => array_slice($activos, 0, 20),
        ]);
        exit;
    }

    /* ══════════════════════════════════════════════════
     *  3. TASA DE REEMBOLSO
     *  CORRECCIÓN: Balance_Prestamos() usa vista_balance_prestamos
     *  cuyo filtro de estado es desconocido. Query directa con estado=1.
     *  Pagado = monto - monto_desc (monto_desc es la deuda pendiente restante).
     * ══════════════════════════════════════════════════ */
    if ($action === 'tasa_reembolso') {

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

        // Vencidos — ya filtra estado=1
        $vencidos = $Nomina->Prestamos_Vencidos();

        // Detalle — ya filtra estado=1
        $todos   = $Nomina->Prestamos_View_report();
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

        echo json_encode([
            'global'         => $global,
            'por_anio'       => $anual,
            'vencidos_cnt'   => (int)($vencidos['cantidad']    ?? 0),
            'vencidos_monto' => (float)($vencidos['monto_total'] ?? 0),
            'detalle'        => array_slice($detalle, 0, 25),
        ]);
        exit;
    }

    /* ══════════════════════════════════════════════════
     *  4. FRECUENCIA DE RENOVACIÓN
     *  CORRECCIÓN: Total_Prestamos() usaba vista_total_prestamos
     *  sin garantía de filtro. COUNT directo con estado=1.
     * ══════════════════════════════════════════════════ */
    if ($action === 'frecuencia_renovacion') {

        // COUNT directo — solo estado=1
        $rTotal = $db->query("SELECT COUNT(*) AS total FROM prestamos WHERE estado = 1");
        $prestamos_realizados = (int)$rTotal->fetch_assoc()['total'];
        $frecuency = round(($prestamos_realizados * 0.033) * 100, 2);

        // Tendencia mensual — Prestamos_View_report() ya filtra estado=1
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

        // Renovaciones: empleados con >1 préstamo activo (estado=1)
        $cedulaCount = array_count_values(array_column($todos, 'cedula'));
        $renovaciones = array_filter($cedulaCount, fn($c) => $c > 1);

        echo json_encode([
            'frecuency'         => $frecuency,
            'prestamos_totales' => $prestamos_realizados,
            'renovaciones'      => array_sum($renovaciones) - count($renovaciones),
            'empleados_multi'   => count($renovaciones),
            'tendencia_mensual' => $tendencia,
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