<?php
/**
 * Constancia-trabajo.php
 * Genera una constancia de trabajo en PDF para un empleado.
 *
 * Parámetros GET requeridos:
 *   ?cedula=XXXXXXXX          — Cédula del empleado
 *
 * Parámetros GET opcionales:
 *   &destinatario=BANESCO PANAMÁ  — A quién va dirigida (default: "A QUIEN CORRESPONDA")
 *   &tipo=accionista              — Si se pasa "accionista", usa redacción especial de accionista.
 *                                    Por defecto genera la constancia de empleado regular.
 *   &ciudad=Cumaná                — Ciudad de emisión (default: "Cumaná")
 *
 * Ejemplo:
 *   Constancia-trabajo.php?cedula=12345678&destinatario=BANCO+MERCANTIL
 *   Constancia-trabajo.php?cedula=12345678&tipo=accionista&destinatario=BANESCO+PANAM%C3%81
 */

session_start();

// if (!isset($_SESSION['user'])) {
//     header('Location: index.php');
//     exit;
// }

include '../../PHP/CLASS/conexion_Original.php';
include '../../PHP/CLASS/user_Original.php';

// ── Parámetros ────────────────────────────────────────────────────────────────
$cedula       = isset($_GET['cedula'])       ? trim($_GET['cedula'])       : null;
$destinatario = isset($_GET['destinatario']) ? trim($_GET['destinatario']) : 'A QUIEN CORRESPONDA';
$tipo         = isset($_GET['tipo'])         ? strtolower(trim($_GET['tipo'])) : 'empleado';
$ciudad       = isset($_GET['ciudad'])       ? trim($_GET['ciudad'])       : 'Cumaná';

if (!$cedula) {
    die('Cédula no especificada.');
}

// ── Datos del empleado ────────────────────────────────────────────────────────
// get_DNI devuelve: nombre, apellido, cedula, f_ingreso, cargo, sueldo, ...
$emp = $Empleado->get_DNI($cedula);

if (!$emp) {
    die('No se encontró empleado con la cédula indicada.');
}

$nombreCompleto = strtoupper($emp['nombre'] . ' ' . $emp['apellido']);
$cedulaFmt      = 'V- ' . number_format((int)$emp['cedula'], 0, '.', '.');
$cargo          = $emp['cargo']     ?? 'empleado';
$fIngreso       = $emp['f_ingreso'] ?? '';
$sueldo         = $emp['sueldo']    ?? '';

// Formatear fecha de ingreso dd/mm/YYYY
if ($fIngreso) {
    $fIngresoFmt = date('d/m/Y', strtotime($fIngreso));
} else {
    $fIngresoFmt = '—';
}

// Fecha de expedición en palabras: "a los X días del mes de XXXX de YYYY"
$diaHoy  = (int) date('d');
$mesHoy  = (int) date('m');
$anioHoy = date('Y');
$mesesNombres = [
    1=>'Enero', 2=>'Febrero', 3=>'Marzo',     4=>'Abril',
    5=>'Mayo',  6=>'Junio',   7=>'Julio',      8=>'Agosto',
    9=>'Septiembre', 10=>'Octubre', 11=>'Noviembre', 12=>'Diciembre'
];
$fechaExpedicion = "a los {$diaHoy} días del mes de {$mesesNombres[$mesHoy]} de {$anioHoy}";

// ── Cuerpo del documento según tipo ──────────────────────────────────────────
if ($tipo === 'accionista') {
    /*
     * Variante: accionista/director
     * "...quien es accionista mayoritario de la empresa DISORIENT, C.A,
     *  presta servicios desde el XX/XX/XXXX, ocupando el cargo de ...,
     *  percibiendo una remuneración mensual de $ X.XXX"
     */
    $cuerpo = "Por medio de la presente se hace constar que el ciudadano "
        . "<strong>{$nombreCompleto}</strong>, titular de la Cédula de Identidad "
        . "<strong>{$cedulaFmt}</strong>, quien es accionista mayoritario de la empresa "
        . "<strong>DISORIENT, C.A</strong>, presta servicios desde el "
        . "<strong>{$fIngresoFmt}</strong>, ocupando el cargo de "
        . "<strong>{$cargo}</strong>, percibiendo una remuneración mensual de "
        . "<strong>$ " . number_format((float)$sueldo, 2, '.', ',') . "</strong>.";
} else {
    /*
     * Variante estándar: empleado regular
     * "...quien es empleada de la empresa DISORIENT, C.A y presta servicios
     *  desde el XX/XX/XXXX, ocupando el cargo de ...,
     *  percibiendo una remuneración mensual de XXX $ equivalente a Bs. X.XXX,XX."
     */

    // Género gramatical según sexo si está disponible, sino genérico
    $genero = isset($emp['sexo']) && strtoupper($emp['sexo']) === 'M' ? 'empleada' : 'empleado';

    $cuerpo = "Por medio de la presente se hace constar que el ciudadano "
        . "<strong>{$nombreCompleto}</strong>, titular de la Cédula de Identidad "
        . "<strong>{$cedulaFmt}</strong>, quien es {$genero} de la empresa "
        . "<strong>DISORIENT, C.A</strong> y presta servicios desde el "
        . "<strong>{$fIngresoFmt}</strong>, ocupando el cargo de "
        . "<strong>{$cargo}</strong>, percibiendo una remuneración mensual de "
        . "<strong>$ " . number_format((float)$sueldo, 2, '.', ',') . "</strong>.";
}

$nombreArchivo = 'Constancia_' . preg_replace('/\W/', '_', $emp['apellido']) . '_' . date('Ymd') . '.pdf';

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Trabajo — <?php echo htmlspecialchars($nombreCompleto); ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            padding: 20px 30px;
        }

        /* ── Encabezado: tabla con borde, fiel al .doc original ── */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .header-table td {
            border: none;
            padding: 10px 12px;
            vertical-align: middle;
        }
        .td-logo {
            width: 90px;
            text-align: center;
            border-right: 1px solid #000;
        }
        .logo { width: 75px; height: 75px; }
        .td-empresa { text-align: center; }
        .empresa-nombre { font-size: 13px; font-weight: bold; }
        .empresa-rubro  { font-size: 11px; font-weight: bold; }
        .empresa-rif    { font-size: 11px; font-weight: bold; }
        .empresa-datos  { font-size: 10px; line-height: 1.7; margin-top: 2px; }
        .td-fecha {
            width: 110px;
            text-align: right;
            vertical-align: top;
            border-left: 1px solid #000;
            font-size: 10px;
            padding-top: 10px;
        }

        /* ── Destinatario ── */
        .destinatario-block { margin-bottom: 6px; }
        .destinatario-block p { font-size: 12px; font-weight: bold; line-height: 1.8; }

        /* ── Título CONSTANCIA DE TRABAJO ── */
        .doc-title-wrap { text-align: center; margin: 24px 0 30px 0; }
        .doc-title { font-size: 13px; font-weight: bold; letter-spacing: 1px; text-decoration: underline; }

        /* ── Cuerpo del texto ── */
        .body-text { font-size: 12px; line-height: 2; text-align: justify; margin-bottom: 30px; }

        /* ── Fecha de expedición ── */
        .expedicion { font-size: 12px; line-height: 2; margin-bottom: 70px; }

        /* ── Firma ── */
        .firma-block { margin-top: 10px; margin-left: 30%;}
        .firma-linea { width: 260px; border-top: 1px solid #000; margin-bottom: 4px; }
        .firma-nombre  { font-size: 11px; font-weight: bold; margin-left: 8%}
        .firma-cargo   { font-size: 11px; margin-left: 19%;}
        .firma-empresa { font-size: 11px; font-weight: bold; margin-left: 18%;}

        /* ── Footer ── */
        .footer {
            margin-top: 50px;
            border-top: 1px solid #e5e7eb;
            padding-top: 6px;
            font-size: 9px;
            color: #9ca3af;
            text-align: right;
        }
    </style>
</head>
<body>

    <!-- ══ ENCABEZADO (tabla con borde, igual al .doc original) ══ -->
    <table class="header-table">
        <tr>
            <td class="td-logo">
                <img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/PIUT_V1/IMG/Logo_Comple_Black.png"
                     class="logo">
            </td>
            <td class="td-empresa">
                <div class="empresa-nombre">DISORIENT, C.A.</div>
                <div class="empresa-rubro">MAYOR DE FERRETERIA</div>
                <div class="empresa-rif">J-080199936</div>
                <div class="empresa-datos">
                    Av. Cancamure No. 69 &ndash; Edif. Disorient &ndash; Planta Baja<br>
                    Cumaná &ndash; Estado Sucre<br>
                    Teléfonos: (0293) 4313167 &ndash; 4320374 &ndash; 4321643 &nbsp; Fax: 4315813<br>
                    e-mail: disorientca@hotmail.com &nbsp; disorient1986@gmail.com
                </div>
            </td>
            <td class="td-fecha">
                Fecha: <?php echo date('d-m-Y'); ?>
            </td>
        </tr>
    </table>

    <!-- ══ DESTINATARIO ══ -->
    <div class="destinatario-block">
        <p><?php echo htmlspecialchars(strtoupper($destinatario)); ?></p>
        <p>SU DESPACHO.</p>
    </div>

    <!-- ══ TÍTULO ══ -->
    <div class="doc-title-wrap">
        <span class="doc-title">CONSTANCIA DE TRABAJO</span>
    </div>

    <!-- ══ CUERPO ══ -->
    <p class="body-text">
        <?php echo $cuerpo; ?>
    </p>

    <!-- ══ FECHA DE EXPEDICIÓN ══ -->
    <p class="expedicion">
        Constancia que se expide a solicitud de partes interesadas en la ciudad de
        <?php echo htmlspecialchars($ciudad); ?> <?php echo $fechaExpedicion; ?>.
    </p>

    <!-- ══ FIRMA ══ -->
    <div class="firma-block">
        <div class="firma-linea"></div>
        <div class="firma-nombre">María Auxiliadora Barrios De Ramos</div>
        <div class="firma-cargo">Administradora</div>
        <div class="firma-empresa">DISORIENT, C.A.</div>
    </div>

    <!-- ══ FOOTER ══ -->
    <div class="footer">
        Generado el <?php echo date('d/m/Y'); ?> a las <?php echo date('H:i'); ?>
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
$dompdf->setPaper('A4', 'portrait');   // carta vertical, es un documento formal
$dompdf->render();
$dompdf->stream($nombreArchivo, ['Attachment' => false]);
