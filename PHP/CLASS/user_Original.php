<?php 
include_once "conexion_Original.php";

class UserE extends connect
{
	public $user;
	public $pass;
	public $name;
	
		public function Existencia($user)
	{
		$query="SELECT * FROM usuario WHERE username='$user' AND estado = 1";
		$result= $this->connect_db()->query($query);

		$numRows = $result->num_rows;
		if ($numRows == 1) 
		{
			return true;
		}
			return false;
	}

		public function verificar($user, $pass)
	{
		$query="SELECT * FROM usuario WHERE username='$user' AND clave='$pass' AND estado = 1";
		$result= $this->connect_db()->query($query);

		$numRows = $result->num_rows;
		if ($numRows == 1) 
		{
			return true;
		}
			return false;
	}

		public function ReturnDataUser($user, $pass)
	{
		$query="SELECT empleados.nombre, empleados.apellido, empleados.cedula, 
				username, clave, type
				FROM usuario 
				JOIN empleados ON usuario.cedula = empleados.cedula 
		WHERE username='$user' AND clave='$pass' AND usuario.estado = 1";
		$result= $this->connect_db()->query($query);

		$data = array();
		while ($row = mysqli_fetch_assoc($result)) {
	  	$data[] = $row;
		}
		return $data;
	}


	public function Insert_User($user, $pass, $name,$type){
		$sql ="INSERT INTO usuario (`username`, `clave`, `cedula`, `type`, `estado`) 
			   VALUES ('$user',
			   		   '$pass',
					   '$name',
					   '$type',
					   '1')";
		if ($result= $this->connect_db()->query($sql)){
				return true;
			}else{
				return false;
			}
	}

	public function Update_User($user, $pass, $name,$type){
		$sql ="UPDATE usuario SET 
				username='$user', 
				clave='$pass', 
				cedula='$name', 
				type='$type' WHERE cedula='$name'";
		if ($result= $this->connect_db()->query($sql)){
			return true;
			}else{
				return false;
			}
	}

	public function validate_DNI($cedula)
	{
		$query="SELECT * FROM usuario WHERE cedula='$cedula'";
  		$result= $this->connect_db()->query($query);
 			
 			if ($result->num_rows > 0) 
 			{
				return true;
      		}else{
				return false;
			}
	}

	public function  View()
	{
		$query="SELECT empleados.nombre, empleados.apellido, empleados.cedula, username, clave, type
				FROM usuario 
				JOIN empleados ON usuario.cedula = empleados.cedula 
				WHERE usuario.estado = '1'";
  		$result= $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function  Get_User($dni)
	{
		$query="SELECT empleados.nombre, empleados.apellido, 
		empleados.cedula, empleados.f_ingreso, username, clave, type
				FROM usuario 
				JOIN empleados ON usuario.cedula = empleados.cedula 
				WHERE usuario.cedula = '$dni' AND usuario.estado = '1'";
  		$result= $this->connect_db()->query($query);

		  if ($result->num_rows > 0) 
		  {
			   $data = $result->fetch_assoc();
			   return $data;
		   }
	}

	public function  Invalid_View()
	{
		$query="SELECT empleados.nombre, empleados.apellido, empleados.cedula, username, clave, type
				FROM usuario 
				JOIN empleados ON usuario.cedula = empleados.cedula 
				WHERE usuario.estado = '0'";
  		$result= $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function  Revalidate_User($dni)
	{
		$sql = "UPDATE usuario SET estado=1 WHERE cedula='$dni'";
	
		if ($result= $this->connect_db()->query($sql)){
				return true;
			}else{
				return false;
			}
	}


	public function  Delete_User($dni)
	{
		$sql = "UPDATE usuario SET estado=0 WHERE cedula='$dni'";
	
		if ($result= $this->connect_db()->query($sql)){
				return true;
			}else{
				return false;
			}
	}


}

		/* Clase para empleados */
class Empleado extends connect
{
	
	public function  View()
	{
		$query="SELECT * FROM empleados WHERE estado = '1'";
  		$result= $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function Discapacidad()
{
    $query = "SELECT discapacidad, COUNT(*) as cantidad FROM empleados WHERE estado = '1' GROUP BY discapacidad";
    $result = $this->connect_db()->query($query);

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

	public function  PromedioPrestamos()
	{
		$query="SELECT COUNT(DISTINCT(cedula_FK)) AS empleados_prestamos 
				FROM `prestamos` WHERE 1";
  		$result= $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
		$data[] = $row;
  		}

		$empleados_prestamos = $data[0]['empleados_prestamos'];
		$empleados = $this->View();
		$empleados_activos = count($empleados);
		$promedio = ($empleados_prestamos/$empleados_activos)*100;

  		return $promedio;
	}

	public function get_DNI($cedula)
	{
		$query="SELECT * FROM empleados WHERE cedula='$cedula'";
  		$result= $this->connect_db()->query($query);
 			
 			if ($result->num_rows > 0) 
 			{
      			$data = $result->fetch_assoc();
      			return $data;
      		}
	}
	 
	public function validate_DNI($cedula)
	{
		$query="SELECT * FROM empleados WHERE cedula='$cedula'";
  		$result= $this->connect_db()->query($query);
 			
 			if ($result->num_rows > 0) 
 			{
				return true;
      		}else{
				return false;
			}
	}

	public function Create_Empleado($cedula,$nombre,$apellido,$direccion,$correo,$sexo,
	$tlf,$second_tlf,$departamento,$cargo,$F_ingreso,$sueldo,$edad,$discapacidad, $afeccion)
	{
		$query="INSERT INTO empleados(`cedula`, `nombre`, `apellido`, `direccion`, `correo`,`sexo`, `edad`, `tlf`, `second_tlf`, `departamento`, `cargo`, `f_ingreso`,`afeccion`, `discapacidad`, `sueldo`, `estado`) 
				VALUES ('$cedula','$nombre','$apellido','$direccion','$correo','$sexo','$edad','$tlf','$second_tlf','$departamento','$cargo','$F_ingreso','$afeccion','$discapacidad','$sueldo','1')";

		if ($result= $this->connect_db()->query($query)){
			return true;
		}else {
			return false;
		}

	}

	public function Update_Empleado($cedula,$nombre,$apellido,$direccion,$correo,$sexo,$tlf,
									$second_tlf,$departamento,$cargo,$F_ingreso,$sueldo,$edad,$discapacidad, $afeccion)
	{
		$query="UPDATE `empleados` SET `cedula`='$cedula',`nombre`='$nombre',`apellido`='$apellido',`direccion`='$direccion',
				`correo`='$correo',`sexo`='$sexo',`edad`='$edad',`tlf`='$tlf',`second_tlf`='$second_tlf',
				`departamento`='$departamento',`cargo`='$cargo',`f_ingreso`='$F_ingreso',`sueldo`='$sueldo', 
				`afeccion` = '$afeccion', `discapacidad` = '$discapacidad',`estado`='1' 
				
				WHERE cedula = '$cedula'";
  		if ($result= $this->connect_db()->query($query)){
  				return true;
  			}else {
  				return false;
  			}
	}

	public function Eliminate($cedula)
	{
		$query = "UPDATE empleados SET estado=0 WHERE cedula='$cedula';
		              UPDATE nomina SET estado=0 WHERE cedula_FK='$cedula';
		              UPDATE vacaciones_y_utilidades SET estado=0 WHERE cedula_FK='$cedula';
		              UPDATE prestamos SET estado=0 WHERE cedula_FK='$cedula';
		              UPDATE cuentas_por_pagar SET estado=0 WHERE cedula_FK='$cedula'";
		              
		if ($this->connect_db()->multi_query($query)) {
        do {
            if ($result = $this->connect_db()->store_result()) {
                
                $result->free();
            }
        } while ($this->connect_db()->next_result());
        return true;
    } else {
        return false;
    }
	}

	function calcularPromedioEdad() {
		$empleados = $this->View(); // Obtener todos los empleados
		$sumaEdad = 0;
		$numeroEmpleados = 0;
	
		foreach ($empleados as $empleado) {
			$fechaNacimiento = $empleado['edad']; // Obtener la fecha de nacimiento del empleado
			$edad = $this->calcularEdad($fechaNacimiento); // Calcular la edad del empleado
			$sumaEdad += $edad; // Sumar la edad del empleado a la suma total
			$numeroEmpleados++; // Incrementar el número de empleados
		}
	
		$promedioEdad = $sumaEdad / $numeroEmpleados; // Calcular el promedio de edad
	
		return $promedioEdad;
	}
	
	function calcularEdad($fechaNacimiento) {
		$fechaActual = date('Y-m-d'); // Obtener la fecha actual
		$edad = date_diff(date_create($fechaNacimiento), date_create($fechaActual))->y; // Calcular la edad del empleado
	
		return $edad;
	}
	
	function obtenerGenero() {
		$empleados = $this->View(); // Obtener todos los empleados
		$hombres = 0;
		$mujeres = 0;
	
		foreach ($empleados as $empleado) {
			if ($empleado['sexo'] == 'H') {
				$hombres++; // Incrementar el número de hombres
			} elseif ($empleado['sexo'] == 'M') {
				$mujeres++; // Incrementar el número de mujeres
			}
		}
	
		return array($hombres, $mujeres);
	}
 
}

class Nomina extends connect
{
	function calcularEdad($fechaNacimiento) {
		$fechaHoy = new DateTime();
		$fechaNacimientoDate = DateTime::createFromFormat("Y-m-d", $fechaNacimiento);
		$edad = $fechaHoy->diff($fechaNacimientoDate);
		return $edad->y;
	  }

	function validarCampos($sueldo, $fechaNacimiento, $fechaIngreso) {
	// Validar sueldo
	if ($sueldo < 130 || $sueldo > 2000) {
		return "El sueldo debe estar entre $130 y $2000";
	}

	// Validar edad
	$edad = $this->calcularEdad($fechaNacimiento);
	if ($edad < 18) {
		return "La edad debe ser mayor a 18 años";
	}elseif ($edad > 100) {
		return "La edad no puede ser mayor a 100 años";
	}

	// Validar fecha de ingreso
	if ($fechaIngreso <= $fechaNacimiento) {
		return("La fecha de ingreso debe ser mayor a la fecha de nacimiento");
	}

	$fechaNacimientoD =  DateTime::createFromFormat("Y-m-d", $fechaNacimiento);
	$fechaIngresoD =  DateTime::createFromFormat("Y-m-d", $fechaIngreso);
	$date18 = $fechaNacimientoD->modify('+18 years');
	//var_dump($fechaIngresoD);
	//var_dump($fechaNacimientoD);
	//var_dump($date18);
	if ($fechaIngresoD < $date18) {
		return "La fecha de ingreso debe ser mayor a 18 años después de la fecha de nacimiento";
	}
	

	// Validar fecha de ingreso
	$fechaHoy = new DateTime();
	$fechaIngresoDate = DateTime::createFromFormat("Y-m-d", $fechaIngreso);
	if ($fechaIngresoDate > $fechaHoy) {
		return "La fecha de ingreso no puede ser mayor a la fecha de hoy";
	}

	
		//Validar que la fecha de ingreso no supere los 30 días anteriores a la fecha de hoy
		//$fecha30DiasAtras = clone $fechaHoy;
		//$fecha30DiasAtras->modify("-30 days");
		//if ($fechaIngresoDate < $fecha30DiasAtras) {
		//	return "La fecha de ingreso no puede ser mayor a 30 días anteriores a la fecha de hoy";
		//}

		//Validar que la fecha de ingreso no supere los 30 días anteriores a la fecha de hoy
		$fecha30DiasAtras = clone $fechaHoy;
		$fecha30DiasAtras->modify("-30 years");
		if ($fechaIngresoDate < $fecha30DiasAtras) {
			return "La fecha de ingreso no puede ser mayor a 30 años";
		}

	return false; // Si no hay errores, devuelve null
	}

	public function obtenerPagosNomina($mes, $anio) {
		// Obtener las 4 semanas del mes y año seleccionados
		$pagosSemanales = array();
		for ($semana = 1; $semana <= 4; $semana++) {
			// Obtener los pagos de la semana actual
			$query = "SELECT SUM(nomina.neto) AS total_pago
					  FROM nomina
					  WHERE nomina.estado = 1
					  AND MONTH(nomina.fecha) = $mes
					  AND YEAR(nomina.fecha) = $anio
					  AND FLOOR((DAY(nomina.fecha) - 1) / 7) + 1 = $semana";
			$result = $this->connect_db()->query($query);
			$row = mysqli_fetch_assoc($result);
			// Guardar el total de la semana en el arreglo
			$pagosSemanales[] = $row['total_pago'] ?? 0; // Si no hay pagos, se asume 0
		}
		return $pagosSemanales;
	}

public function View_Nomina_Historial()
{
    $query = "SELECT 
                MIN(nomina.fecha)                                       AS fecha_inicio,
                MAX(nomina.fecha)                                       AS fecha_fin,
                YEAR(nomina.fecha)                                      AS anio,
                WEEK(nomina.fecha, 1)                                   AS semana,
                COUNT(*)                                                AS total_empleados,
                SUM(nomina.neto)                                        AS total_neto_usd,
                SUM(TRUNCATE(nomina.neto * tasa_dolar.tasa_del_dia, 2)) AS total_neto_bs
              FROM nomina
              JOIN tasa_dolar ON nomina.tasaBCV_FK = tasa_dolar.id_tasa
              WHERE nomina.estado = 1
              GROUP BY YEAR(nomina.fecha), WEEK(nomina.fecha, 1)
              ORDER BY anio DESC, semana DESC";

    $result = $this->connect_db()->query($query);

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

public function Vendedores_Nomina() {
		$query = "SELECT * FROM vista_vendedores 
				  WHERE mes = MONTH(CURDATE())
				  ORDER BY anio DESC";
		
		$result = $this->connect_db()->query($query);
		$data = array();
		while($row = mysqli_fetch_assoc($result)) {
			$data[] = $row;
			}
		return $data;
	}

	public function Search_Nomina_Fecha($fecha) {
    $query = "SELECT id_nomina, empleados.nombre, empleados.apellido, empleados.cedula, 
                empleados.sueldo, nomina.sueldosem, prestamos.descuento AS desc2,
                cuentas_por_pagar.descuento AS desc1, 
                (nomina.bonificaciones + nomina.comisiones) AS asignaciones, 
                nomina.neto, TRUNCATE(nomina.neto * tasa_dolar.tasa_del_dia, 2) AS netobs, 
                tasa_dolar.tasa_del_dia AS TasaBCV, nomina.fecha, nomina.estado
                
            FROM nomina
            JOIN empleados ON nomina.cedula_FK = empleados.cedula
            JOIN tasa_dolar ON nomina.tasaBCV_FK = tasa_dolar.id_tasa
            LEFT JOIN cuentas_por_pagar ON nomina.cuentasp = cuentas_por_pagar.id_cuentasp
            LEFT JOIN prestamos ON nomina.prestamos = prestamos.id_prestamos

            WHERE nomina.estado = 1
            AND nomina.fecha = '$fecha'";

    $result = $this->connect_db()->query($query);

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

public function Search_Nomina_Semana($semana, $anio)
{
    $query = "SELECT 
                id_nomina,
                empleados.nombre, empleados.apellido, empleados.cedula,
                empleados.sueldo, nomina.sueldosem,
                COALESCE(
                    (SELECT SUM(cpp2.aporte) 
                     FROM cuentas_por_pagar2 cpp2 
                     WHERE cpp2.id_prestamo = nomina.prestamos 
                     AND cpp2.fecha = nomina.fecha
                     AND cpp2.estado = 1),
                    0
                ) AS desc2,
                cuentas_por_pagar.descuento AS desc1,
                (nomina.bonificaciones + nomina.comisiones) AS asignaciones,
                nomina.neto,
                TRUNCATE(nomina.neto * tasa_dolar.tasa_del_dia, 2) AS netobs,
                tasa_dolar.tasa_del_dia AS TasaBCV, 
                nomina.fecha

            FROM nomina
            JOIN empleados   ON nomina.cedula_FK  = empleados.cedula
            JOIN tasa_dolar  ON nomina.tasaBCV_FK = tasa_dolar.id_tasa
            LEFT JOIN cuentas_por_pagar ON nomina.cuentasp = cuentas_por_pagar.id_cuentasp

            WHERE nomina.estado = 1
            AND WEEK(nomina.fecha, 1) = '$semana'
            AND YEAR(nomina.fecha)    = '$anio'";

    $result = $this->connect_db()->query($query);
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

	public function MAX_Vendedores() {
		$query = "SELECT * FROM `vista_vendedores` WHERE t_comiciones = 
				  (SELECT MAX(t_comiciones) FROM vista_vendedores WHERE mes = MONTH(CURDATE())) AND mes = MONTH(CURDATE()) 
				  ORDER BY anio DESC";
		
		$result = $this->connect_db()->query($query);
		$row = mysqli_fetch_assoc($result);
		return $row;
	}

	public function EmpleadosPagos($mes, $anio) {
		$query = "SELECT cantidad_empleados FROM `indicadorpagos` WHERE anio = $anio AND mes = $mes";
		$result = $this->connect_db()->query($query);
		$row = mysqli_fetch_assoc($result);
		return $row;
	}
	
	public function View_Variacion(){
		$query = "SELECT * FROM vista_variacion_nominia";
  		$result = $this->connect_db()->query($query);
  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function View_Nomina()
{
    $query = "SELECT 
                id_nomina, 
                empleados.nombre, empleados.apellido, empleados.cedula, 
                empleados.sueldo, nomina.sueldosem,
                COALESCE(
                    (SELECT SUM(cpp2.aporte) 
                     FROM cuentas_por_pagar2 cpp2 
                     WHERE cpp2.id_prestamo = nomina.prestamos 
                     AND cpp2.fecha = nomina.fecha
                     AND cpp2.estado = 1), 
                    0
                ) AS desc2,
                cuentas_por_pagar.descuento AS desc1,
                (nomina.bonificaciones + nomina.comisiones) AS asignaciones,
                nomina.neto, 
                TRUNCATE(nomina.neto * tasa_dolar.tasa_del_dia, 2) AS netobs, 
                tasa_dolar.tasa_del_dia AS TasaBCV, 
                nomina.fecha, nomina.estado

            FROM nomina
            JOIN empleados    ON nomina.cedula_FK   = empleados.cedula
            JOIN tasa_dolar   ON nomina.tasaBCV_FK  = tasa_dolar.id_tasa
            LEFT JOIN cuentas_por_pagar ON nomina.cuentasp = cuentas_por_pagar.id_cuentasp

            WHERE nomina.estado = 1
            AND WEEK(nomina.fecha, 1) = WEEK(CURDATE(), 1)
            AND YEAR(nomina.fecha)    = YEAR(CURDATE())";

    $result = $this->connect_db()->query($query);
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}


	public function validarPagoEmpleado($cedula) {
		$query = "SELECT * FROM nomina WHERE cedula_FK = '$cedula' AND estado = 1 AND WEEK(fecha) = WEEK(CURDATE())";
		$result = $this->connect_db()->query($query);
	
		if ($result->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function Search_Nomina($fecha = null, $cedula = null)
{
    $query = "SELECT id_nomina, empleados.nombre, empleados.apellido, empleados.cedula, 
                empleados.sueldo, nomina.sueldosem, prestamos.descuento AS desc2, 
                cuentas_por_pagar.descuento AS desc1, 
                (nomina.bonificaciones + nomina.comisiones) AS asignaciones, 
                nomina.neto, TRUNCATE (nomina.neto * tasa_dolar.tasa_del_dia, 2) AS netobs,
                tasa_dolar.tasa_del_dia AS TasaBCV, nomina.fecha, nomina.estado

        FROM nomina JOIN empleados ON nomina.cedula_FK = empleados.cedula
        JOIN tasa_dolar ON nomina.tasaBCV_FK = tasa_dolar.id_tasa 
        LEFT JOIN cuentas_por_pagar ON nomina.cuentasp = cuentas_por_pagar.id_cuentasp
        LEFT JOIN prestamos ON nomina.prestamos = prestamos.id_prestamos

        WHERE nomina.estado = 1";

    $conditions = array();

    if ($fecha) {
        $conditions[] = "DATE_FORMAT(nomina.fecha, '%Y-%m') = '$fecha'";
    }

    if ($cedula) {
        $conditions[] = "empleados.cedula = '$cedula'";
    }

    if (!empty($conditions)) {
        $query .= " AND " . implode(" AND ", $conditions);
    }

    $query .= " ORDER BY nomina.fecha DESC";

    $result = $this->connect_db()->query($query);

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

	public function GetID_nomina($ID)
{
    $query = "SELECT id_nomina, empleados.nombre, empleados.apellido, empleados.cedula, 
        empleados.sueldo, empleados.f_ingreso, empleados.cargo, nomina.sueldosem, 
        nomina.bonificaciones, nomina.comisiones, nomina.neto, 
        TRUNCATE(nomina.neto * tasa_dolar.tasa_del_dia, 2) AS netobs,
        COALESCE(
            (SELECT SUM(cpp2.aporte) 
             FROM cuentas_por_pagar2 cpp2 
             WHERE cpp2.id_prestamo = nomina.prestamos 
             AND cpp2.fecha = nomina.fecha
             AND cpp2.estado = 1),
            0
        ) AS Ptm,
        COALESCE(cuentas_por_pagar.descuento, 0) AS cpp,
        tasa_dolar.tasa_del_dia AS TasaBCV, nomina.fecha 

        FROM nomina 
        JOIN empleados ON nomina.cedula_FK = empleados.cedula 
        JOIN tasa_dolar ON nomina.tasaBCV_FK = tasa_dolar.id_tasa 
        LEFT JOIN cuentas_por_pagar ON nomina.cuentasp = cuentas_por_pagar.id_cuentasp

        WHERE id_nomina = '$ID'";
        
    $result = $this->connect_db()->query($query);
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        return $data;
    }
}

	public function Create_Nomina($cedula, $cuentasp, $prestamos, $sueldosem, $neto, $bono, $comis)
{
    // Manejo de valores nulos
    $prestamos = $prestamos ??'null';
    $cuentasp = $cuentasp ?? 'null';

    // Consulta SQL
    $query = "INSERT INTO `nomina`(`cedula_FK`, `tasaBCV_FK`, `cuentasp`, `prestamos`, `sueldosem`, `neto`, `bonificaciones`, `comisiones`, `fecha`, `estado`) 
              VALUES (
                  '$cedula',
                  (SELECT `id_tasa` FROM `tasa_dolar` WHERE fecha = STR_TO_DATE(NOW(), '%Y-%m-%d') ORDER BY `id_tasa` DESC LIMIT 1),
                  $cuentasp,
                  $prestamos,
                  $sueldosem,
                  $neto,
                  $bono,
                  $comis,
                  STR_TO_DATE(NOW(), '%Y-%m-%d'),
                  1
              )";

    // Depuración: Imprimir la consulta SQL
    // echo $query; // Descomentar para ver la consulta

    // Ejecutar la consulta
    if ($result = $this->connect_db()->query($query)) {
        return true;
    } else {
        return false;
    }
}


	public function  View_Active_Search_Nomina($cedula)
	{		
		$query="SELECT * FROM empleados WHERE cedula='$cedula' AND estado=1 ";
  		$result= $this->connect_db()->query($query);

 			if ($result->num_rows > 0) 
 			{
      			$data = $result->fetch_assoc();
      			return $data;
      		}
	}

public function View_Empleados_Sin_Pago_Semana()
{
    $query = "SELECT 
                e.cedula,
                e.nombre,
                e.apellido,
                e.cargo
              FROM empleados e
              WHERE e.estado = 1
                AND e.cedula NOT IN (
                    SELECT n.cedula_FK
                    FROM nomina n
                    WHERE n.estado = 1
                      AND WEEK(n.fecha, 1) = WEEK(CURDATE(), 1)
                      AND YEAR(n.fecha)    = YEAR(CURDATE())
                )
              ORDER BY e.apellido ASC, e.nombre ASC";

    $result = $this->connect_db()->query($query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

	// Funciones para el modulo de Vacaciones y utlidades
	public function View_Vacation()
	{
		$query ="SELECT `vacaciones_id`, empleados.cedula, empleados.nombre, empleados.apellido, `ini_vacaciones`, `cedula_FK`
				FROM vacaciones_y_utilidades 
				INNER JOIN empleados ON cedula_FK = cedula";

		$result = $this->connect_db()->query($query);
		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function Vacation_Pay_Indicator()
	{
		$query ="SELECT YEAR(ini_vacaciones) AS anio, 
						SUM(monto) AS monto
					FROM `vacaciones_y_utilidades`
					GROUP BY
					YEAR(ini_vacaciones) 
					ORDER BY YEAR(ini_vacaciones) DESC";

		$result = $this->connect_db()->query($query);
		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function GetID_Vacation($ID)
	{
		$query ="SELECT `vacaciones_id`, empleados.cedula, empleados.nombre, empleados.apellido, empleados.f_ingreso, empleados.sueldo, `dia_correspondido`,`utilidades`, `t_servicio`, `ini_vacaciones`, `fin_vacaciones`, `ini_laboral`,
		 `dia_descanso`, `dia_feriado`, `dia_otorgado`, `sueldo_diario`,
		  `cedula_FK`,tasa_dolar.Tasa_del_dia AS tasa, `TasaBCV_FK`,`monto`, `ince`
				FROM vacaciones_y_utilidades 
				INNER JOIN empleados ON cedula_FK = cedula
				INNER JOIN tasa_dolar ON TasaBCV_FK = id_tasa
				WHERE vacaciones_id = '$ID' ";
			
			$result= $this->connect_db()->query($query);
 			if ($result->num_rows > 0) 
 			{
      			$data = $result->fetch_assoc();
      			return $data;
      		}
	}

	public function Search_Vacation($fecha, $cedula = null)
	{
		$query ="SELECT `vacaciones_id`, empleados.cedula, empleados.nombre, empleados.apellido, `ini_vacaciones`, `cedula_FK`
				FROM vacaciones_y_utilidades 
				INNER JOIN empleados ON cedula_FK = cedula";
				
		if ($fecha) {
        $query .= " WHERE DATE_FORMAT(ini_vacaciones, '%Y-%m') = '$fecha'";
    	}

	    if ($cedula) {
	        if ($fecha) {
	            $query .= " AND";
	        } else {
	            $query .= " WHERE";
	        }
	        $query .= " empleados.cedula = '$cedula'";
	    }

		$result = $this->connect_db()->query($query);
		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function Vacation_Insert($Dvacation,$servicio,$utilidades,$inivacation,$endvacation,$inilaboral,$descanso,$Dferiado,$pendiente,$sueldoD,$cedula,$monto,$ince)
	{
		$query = "INSERT INTO `vacaciones_y_utilidades` (`dia_correspondido`, `utilidades`,`t_servicio`,`ini_vacaciones`, `fin_vacaciones`, `ini_laboral`, `dia_descanso`, `dia_feriado`, `dia_otorgado`, `sueldo_diario`, `cedula_FK`, `TasaBCV_FK`, `monto`, `ince`) 

		VALUES ('$Dvacation', '$utilidades','$servicio' , '$inivacation', '$endvacation', '$inilaboral',$descanso,'$Dferiado','$pendiente', '$sueldoD', '$cedula', (SELECT `id_tasa` FROM `tasa_dolar` WHERE fecha = STR_TO_DATE(NOW(), '%Y-%m-%d') ORDER BY `id_tasa` LIMIT 1), '$monto', '$ince')";

		
		if ($result = $this->connect_db()->query($query)) {
			return true;
		}else {
			return false;
		}
	}

	public function DaysOff($cedula, $fechActual) //función para los dias de vacaciones en función de los años de servicio 
	{
		$dato = $this->View_Active_Search_Nomina($cedula); // llama la función "" para tomar la fecha de ingreso
		
		$fresult = date_diff(date_create($dato['f_ingreso']), date_create($fechActual))->y;
			// hace el calculo para determinar ela cantidad de años de servicio de la persona 
		return $fresult;
	}

	public function Time_Service() //función para los dias de vacaciones 
	{	$empleado = new Empleado();

	    $empleados = $empleado->View(); // Obtener todos los empleados activos
		$sumaTiempoServicio = 0;
		$numeroEmpleados = 0;

		foreach ($empleados as $empleado) {
			$sumaTiempoServicio += $this->DaysOff($empleado['cedula'], date('Y-m-d')); // Calcular el tiempo de servicio de cada empleado
			$numeroEmpleados++;
		}

		$promedioTiempoServicio = $sumaTiempoServicio / $numeroEmpleados; // Calcular el promedio de tiempo de servicio

		return $promedioTiempoServicio;
	}

	public function Time_ServiceAnterior($fecha = null) {//función para los dias de vacaciones{
		$fechaAnterior = $fecha ?? date('Y-m-d', strtotime('-1 year')); // Si no se especifica la fecha, se toma la fecha actual
		$empleado = new Empleado();
	
		$empleados = $empleado->View(); // Obtener todos los empleados activos
		$sumaTiempoServicio = 0;
		$numeroEmpleados = 0;
	
		foreach ($empleados as $empleado) {
			$fechaIngreso = $empleado['f_ingreso']; // Obtener la fecha de ingreso del empleado
			if ($fechaIngreso <= $fechaAnterior) { // Verificar si la fecha de ingreso es menor o igual a la fecha anterior
				$sumaTiempoServicio += $this->DaysOff($empleado['cedula'], $fechaAnterior); // Calcular el tiempo de servicio de cada empleado
				$numeroEmpleados++;
			}
		}
		$promedioTiempoServicio = $sumaTiempoServicio / $numeroEmpleados; // Calcular el promedio de tiempo de servicio
		return $promedioTiempoServicio;
	}

	function ConvertTimeService($valorDecimal) {
		$anios = floor($valorDecimal); // Obtener la parte entera (años)
		$dias = round(($valorDecimal - $anios) * 12); // Obtener la parte decimal (días)
	
		return array($anios, $dias);
	}

	public function Last_DaysOff($dias, $fechActual) {
	   
	    $fecha = new DateTime($fechActual); 
	    
	    $diasSumados = 0; //Este sera el contador de los días 
	    $diasFinSemana = 0; // Contador de los días de fin de semana

	    while ($diasSumados < $dias) {
	        $fecha->add(new DateInterval('P1D'));

	        if ($fecha->format('N') < 6) {
	            $diasSumados++;
	        } else {
	        	$diasFinSemana++;
	        }
	    }

	    return ['fecha' => $fecha->format('Y-m-d'),'diasFinSemana' => $diasFinSemana];
	    // retorna un arreglo con dos variables 
	}

	public function MidDays($fecha) {
	    $newFecha = strtotime('+1 day', strtotime($fecha));
	    $diaSemana = date('N', $newFecha);
	    
	    if ($diaSemana == 6) { // Si es sábado
	        $newFecha = strtotime('+2 day', $newFecha);
	    } elseif ($diaSemana == 7) { // Si es domingo
	        $newFecha = strtotime('+1 day', $newFecha);
	    }
	    
	    return date('Y-m-d', $newFecha);
	}

	// Fin de las funciones de Vaciones y utilidaes 




	// Funciones para el modulo de ISLR
	public function ISLR_View()
	{
		$query = "SELECT empleados.nombre, empleados.apellido, empleados.cedula,empleados.cargo, empleados.f_ingreso, aporte, monto, fecha FROM `islr` INNER JOIN empleados ON cedula_FK = cedula";
  		
  		$result = $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function ISLR_Indicator()
	{
		$query = "SELECT YEAR(fecha) AS anio, MONTH(fecha) AS mes, SUM(monto) AS monto
					FROM `islr` 
					GROUP BY YEAR(fecha), MONTH(fecha)
					ORDER BY fecha DESC";
  		$result = $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function ISLR_Grap()
	{
		$query = "SELECT YEAR(fecha) AS anio, MONTH(fecha) AS mes, SUM(monto) AS monto
				FROM `islr` 
				WHERE YEAR(fecha) = YEAR(CURDATE())
				GROUP BY YEAR(fecha), MONTH(fecha)
				ORDER BY fecha DESC";
  		$result = $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function Search_ISLR($fecha)
	{
		$query = "SELECT empleados.nombre, empleados.apellido, empleados.cedula, empleados.f_ingreso, empleados.cargo, aporte, monto, fecha
			
			FROM islr JOIN empleados ON cedula_FK = cedula
			WHERE DATE_FORMAT(fecha, '%Y-%m') = '$fecha'";
  		
  		$result = $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function Create_ISLR($aporte, $monto, $cedula)
	{
		$query = "INSERT INTO `islr`(`aporte`, `monto`, `cedula_FK`, `tasaBCV_FK`, `fecha`) 
				VALUES ('$aporte', '$monto', '$cedula',(SELECT `id_tasa` FROM `tasa_dolar` WHERE fecha = STR_TO_DATE(NOW(), '%Y-%m-%d') ORDER BY `id_tasa` DESC LIMIT 1), STR_TO_DATE(NOW(), '%Y-%m-%d'))";
		
		if ($result= $this->connect_db()->query($query)){
			return true;
		}else {
			return false;
		}
	}

 	public function Display_ISLR($cedula)
	{
		$query = "SELECT islr.id_islr , islr.monto FROM empleados 
					INNER JOIN islr ON empleados.cedula = islr.cedula_FK 
					WHERE YEAR(islr.fecha) = YEAR(CURDATE()) AND MONTH(islr.fecha) = MONTH(CURDATE()) AND empleados.cedula = '$cedula'
					ORDER BY fecha DESC LIMIT 1";
  		
  		$result = $this->connect_db()->query($query);

 			if ($result->num_rows > 0) 
 			{
      			$data = $result->fetch_assoc();
      			return $data;
      		}
	}
	// Fin de las funciones de ISLR


	// Funciones para el modulo de IVSS
	public function IVSS_View()
	{
		$query = "SELECT empleados.nombre, empleados.apellido, empleados.cedula, empleados.f_ingreso, TRUNCATE (ivss.sueldo / 30, 2) AS sueldoD, TRUNCATE (ivss.sueldo / 4, 2) AS sueldosem, ivss.sueldo, aporte_tbj, aporte_emp, t_aporte, aporte_tbj_rpe, aporte_emp_rpe, t_aporte_rpe, fecha

		FROM ivss INNER JOIN empleados ON cedula_FK = cedula";
  		
  		$result = $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function Search_IVSS($fecha)
	{
		$query = "SELECT empleados.nombre, empleados.apellido, empleados.cedula, empleados.f_ingreso, TRUNCATE (ivss.sueldo / 30, 2) AS sueldoD, TRUNCATE (ivss.sueldo / 4, 2) AS sueldosem, ivss.sueldo, aporte_tbj, aporte_emp, t_aporte, aporte_tbj_rpe, aporte_emp_rpe, t_aporte_rpe, fecha
			
			FROM ivss JOIN empleados ON cedula_FK = cedula
			WHERE DATE_FORMAT(fecha, '%Y-%m') = '$fecha'";
  		
  		$result = $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function Create_IVSS($monto,$CSE,$CST,$T_IVSS,$CST_RPE,$CSE_RPE,$T_IVSS_RPE, $cedula)
	{
		$query = "INSERT INTO `ivss`(`sueldo`, `aporte_tbj`, `aporte_emp`, `t_aporte`, `aporte_tbj_rpe`, `aporte_emp_rpe`, `t_aporte_rpe`, `cedula_fk`, `fecha`) 
				
				VALUES ('$monto', '$CSE','$CST','$T_IVSS','$CST_RPE','$CSE_RPE','$T_IVSS_RPE','$cedula', STR_TO_DATE(NOW(), '%Y-%m-%d'))";
		
		if ($result= $this->connect_db()->query($query)){
			return true;
		}else {
			return false;
		}
	}

 	public function Display_IVSS($cedula)
	{
		$query = "SELECT ivss.id_ivss , ivss.t_aporte FROM empleados 
					INNER JOIN ivss ON empleados.cedula = ivss.cedula_FK 
					WHERE YEAR(ivss.fecha) = YEAR(CURDATE()) AND MONTH(ivss.fecha) = MONTH(CURDATE()) AND empleados.cedula = '$cedula'
					ORDER BY fecha DESC LIMIT 1";
  		
  		$result = $this->connect_db()->query($query);

 			if ($result->num_rows > 0) 
 			{
      			$data = $result->fetch_assoc();
      			return $data;
      		}
	}
	// Fin de las funciones de IVSS


// Función para el modulo de prestamos y cuentas por pagar 
public function Discount_cuentas_por_pagar($id_cuentasp, $aporte)
{
    $query = "UPDATE cuentas_por_pagar 
              SET monto_desc = monto_desc - $aporte 
              WHERE id_cuentasp = $id_cuentasp AND monto_desc > 0";

    if ($result = $this->connect_db()->query($query)) {
        return true;
    } else {
        return false;
    }
}

public function Display_cuentas_por_pagar($cedula)
	{
		$query = "SELECT cuentas_por_pagar.id_cuentasp, cuentas_por_pagar.descuento, cuentas_por_pagar.monto, cuentas_por_pagar.monto_desc FROM empleados 
					INNER JOIN cuentas_por_pagar ON empleados.cedula = cuentas_por_pagar.cedula_FK 
					WHERE monto_desc > 0 AND empleados.cedula = '$cedula'";
  		
  		$result = $this->connect_db()->query($query);

 			if ($result->num_rows > 0) 
 			{
      			$data = $result->fetch_assoc();
      			return $data;
      		}
	}
public function Discount_Prestamos($id_prestamo, $aporte)
{
    $query = "UPDATE prestamos 
              SET monto_desc = monto_desc - $aporte 
              WHERE id_prestamos = $id_prestamo AND monto_desc > 0";

    if ($result = $this->connect_db()->query($query)) {
        return true;
    } else {
        return false;
    }
}

public function GetID_Prestamos($ID)
	{
		$query = "SELECT id_prestamos, empleados.nombre, empleados.apellido, 
		empleados.cedula, empleados.cargo, fecha, monto_desc, descuento, cuotas, concepto

				FROM prestamos INNER JOIN empleados ON cedula_FK = cedula
				WHERE id_prestamos = $ID";
			
			$result= $this->connect_db()->query($query);
 			if ($result->num_rows > 0) 
 			{
      			$data = $result->fetch_assoc();
      			return $data;
      		}
	}

public function Display_Prestamos($cedula)
{
    $query = "SELECT 
                p.id_prestamos,
                p.descuento,
                p.monto,
                p.monto_desc,
                COALESCE(
                    (SELECT cpp2.aporte 
                     FROM cuentas_por_pagar2 cpp2 
                     WHERE cpp2.id_prestamo = p.id_prestamos 
                     AND WEEK(cpp2.fecha, 1) = WEEK(CURDATE(), 1)
                     AND YEAR(cpp2.fecha)    = YEAR(CURDATE())
                     AND cpp2.estado = 1
                     ORDER BY cpp2.id_cp DESC LIMIT 1),
                    p.descuento
                ) AS aporte_semana
              FROM prestamos p
              INNER JOIN empleados e ON e.cedula = p.cedula_FK
              WHERE p.monto_desc > 0 
              AND e.cedula = '$cedula' 
              AND p.estado = 1";

    $result = $this->connect_db()->query($query);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        return $data;
    }
}

	public function Display_Prestamos_tabla($cedula)
	{
		$query = "SELECT empleados.nombre, empleados.apellido,
		prestamos.id_prestamos, prestamos.descuento, prestamos.monto, 
		prestamos.monto_desc, id_prestamos, cedula_FK As cedulaFK,
		fecha, cuotas, concepto, date_limit, prestamos.estado
			FROM empleados 
					INNER JOIN prestamos ON empleados.cedula = prestamos.cedula_FK 
					WHERE monto_desc > 0 AND empleados.cedula = '$cedula' AND prestamos.estado = 1";
		
		$result = $this->connect_db()->query($query);
	
		$data = array();
		if ($result->num_rows > 0) 
		{
			while ($row = $result->fetch_assoc()) 
			{
				$data[] = $row;
			}
		}
		return $data;
	}

public function ValidatePrestamos($cedula)
	{
		$query = "SELECT * FROM prestamos  
				WHERE monto_desc > 0 AND cedula_FK = '$cedula' AND estado = 1";
  		
  		$result = $this->connect_db()->query($query);

 			if ($result->num_rows > 0) {
			 	return true;
			}else {
				return false;
			}
	}

public function Create_Prestamos($descuento, $monto, $cuotas, $concepto, $cedula, $fecha, $limit,$idsolicitud)
	{
		$query = "INSERT INTO `prestamos`(`solicitud_FK`,`descuento`, `monto`,`monto_desc`, 
		`cuotas`, `concepto`, `cedula_FK`, `tasaBCV_FK`, `fecha`, `date_limit`,`estado`) 
				VALUES ('$idsolicitud','$descuento',
				'$monto', 
				'$monto',
				'$cuotas',
				'$concepto', 
				'$cedula',
				(SELECT `id_tasa` FROM `tasa_dolar` WHERE fecha = STR_TO_DATE(NOW(), '%Y-%m-%d')
				 ORDER BY `id_tasa` DESC LIMIT 1),
				'$fecha', 
				'$limit',
				'1')";
		
		if ($result= $this->connect_db()->query($query)){
			return true;
		}else {
			return false;
		}
	}

public function Create_Prestamos_Ori($descuento, $monto, $cuotas, $concepto, $cedula, $fecha, $limit,)
	{
		$query = "INSERT INTO `prestamos`(`descuento`, `monto`,`monto_desc`, 
		`cuotas`, `concepto`, `cedula_FK`, `tasaBCV_FK`, `fecha`, `date_limit`,`estado`) 
				VALUES ('$descuento',
				'$monto', 
				'$monto',
				'$cuotas',
				'$concepto', 
				'$cedula',
				(SELECT `id_tasa` FROM `tasa_dolar` WHERE fecha = STR_TO_DATE(NOW(), '%Y-%m-%d')
				 ORDER BY `id_tasa` DESC LIMIT 1),
				'$fecha', 
				'$limit',
				'1')";
		
		if ($result= $this->connect_db()->query($query)){
			return true;
		}else {
			return false;
		}
	}

	public function Delete_Prestamo($ID)
	{
		$query = "UPDATE prestamos SET estado=0 WHERE id_prestamos='$ID'";
		              
		if ($result= $this->connect_db()->query($query)){
			return true;
		}else {
			return false;
		}
	}

public function Prestamos_View() {
		$query = "SELECT id_prestamos, empleados.nombre, empleados.apellido, empleados.cedula, 
					fecha, monto , monto_desc, descuento, cuotas, concepto, date_limit, prestamos.estado

				FROM prestamos INNER JOIN empleados ON cedula_FK = cedula
				WHERE  monto_desc > 0 AND prestamos.estado = 1";
  		
  		$result = $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function Cuentas_pagadas_recibo() {
		$query = "SELECT id_cp AS id, id_prestamo, prestamos.cedula_FK AS cedula, empleados.nombre, empleados.apellido, empleados.cargo,
cuentas_por_pagar2.deuda, cuentas_por_pagar2.aporte, cuentas_por_pagar2.tpago AS tipo_pago, cuentas_por_pagar2.refe, cuentas_por_pagar2.fecha AS fecha,
prestamos.concepto, prestamos.monto AS monto_prestamo, prestamos.cuotas, prestamos.fecha AS fecha_solicitud,
tasa_dolar.tasa_del_dia AS tasa
				FROM cuentas_por_pagar2 
                INNER JOIN prestamos ON id_prestamo = prestamos.id_prestamos
                INNER JOIN empleados ON prestamos.cedula_FK = empleados.cedula
                INNER JOIN tasa_dolar ON prestamos.tasaBCV_FK = tasa_dolar.id_tasa
                WHERE prestamos.estado = 1 AND empleados.estado = 1 AND cuentas_por_pagar2.estado = 1";
  		
  		$result = $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}
	
	public function Recibo($id) {
    $query = "SELECT id_cp AS id, id_prestamo, prestamos.cedula_FK AS cedula, 
        empleados.nombre, empleados.apellido, empleados.cargo,
        cuentas_por_pagar2.deuda, cuentas_por_pagar2.aporte, 
        cuentas_por_pagar2.tpago AS tipo_pago, cuentas_por_pagar2.refe, 
        cuentas_por_pagar2.fecha AS fecha,
        prestamos.concepto, prestamos.monto AS monto_prestamo, 
        prestamos.cuotas, prestamos.fecha AS fecha_solicitud,
        tasa_dolar.tasa_del_dia AS tasa
        FROM cuentas_por_pagar2 
        INNER JOIN prestamos ON id_prestamo = prestamos.id_prestamos
        INNER JOIN empleados ON prestamos.cedula_FK = empleados.cedula
        INNER JOIN tasa_dolar ON tasa_dolar.fecha = DATE(cuentas_por_pagar2.fecha)
        WHERE  
         empleados.estado = 1 
        AND id_cp = $id
        ORDER BY tasa_dolar.id_tasa DESC
        LIMIT 1";

    $result = $this->connect_db()->query($query);
    $data = mysqli_fetch_assoc($result);
    return $data;
}

public function Prestamos_View_report() {
		$query = "SELECT id_prestamos, empleados.nombre, empleados.apellido, empleados.cedula, 
					fecha, monto , monto_desc, descuento, cuotas, concepto, date_limit, prestamos.estado

				FROM prestamos INNER JOIN empleados ON cedula_FK = cedula
				WHERE prestamos.estado = 1";
  		
  		$result = $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

public function Total_Prestamos() {
		$query = "SELECT * FROM `vista_total_prestamos`";
  		$result = $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

public function Balance_Prestamos() {
		$query = "SELECT 
						anio,
						SUM(monto_total_prestado) AS total_prestado,
						SUM(monto_total_reembolsado) AS total_reembolsado
					FROM 
						vista_balance_prestamos
					GROUP BY 
						anio
					ORDER BY 
						anio DESC";
  		
  		$result = $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}


public function View_Promedio_Prestamos(){
		$query = "SELECT * FROM `vista_promedio_prestamos` ORDER BY año DESC, mes DESC";
  		
  		$result = $this->connect_db()->query($query);
  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function Prestamos_View_Modal()
	{
		$query = "SELECT id_prestamos, empleados.nombre, empleados.apellido, empleados.cedula, 
					fecha, monto , monto_desc, descuento, cuotas, concepto, date_limit, prestamos.estado

				FROM prestamos INNER JOIN empleados ON cedula_FK = cedula
				WHERE prestamos.estado = 1
				ORDER BY id_prestamos DESC";
  		
  		$result = $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function Search_Prestamos($fecha, $cedula = null)
	{
		$query = "SELECT id_prestamos, empleados.nombre, empleados.apellido, empleados.cedula, fecha, monto_desc, descuento, cuotas, concepto
			
			FROM prestamos JOIN empleados ON cedula_FK = cedula";

		if ($fecha) {
        $query .= " WHERE DATE_FORMAT(fecha, '%Y-%m') = '$fecha'";
    	}

	    if ($cedula) {
	        if ($fecha) {
	            $query .= " AND";
	        } else {
	            $query .= " WHERE";
	        }
	        $query .= " empleados.cedula = '$cedula'";
	    }
  		
  		$result = $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

public function Get_Saldo_Prestamos($cedula)
{
    $limite = 2000;

    $query = "SELECT 
                COALESCE(SUM(p.monto_desc), 0) AS total_pendiente
              FROM prestamos p
              WHERE p.cedula_FK = '$cedula'
                AND p.monto_desc > 0
                AND p.estado = 1";

    $result = $this->connect_db()->query($query);
    $row    = $result->fetch_assoc();

    $pendiente = (float) $row['total_pendiente'];
    $saldo     = max(0, $limite - $pendiente);

    return [
        'limite'     => $limite,
        'pendiente'  => $pendiente,
        'saldo'      => $saldo,
        'porcentaje' => round(($pendiente / $limite) * 100, 1),
    ];
}

public function Get_Prestamos_Activos_Trabajador($cedula)
{
    $query = "SELECT 
                p.id_prestamos,
                p.concepto,
                p.monto         AS monto_original,
                p.monto_desc    AS monto_pendiente,
                p.descuento     AS cuota_semanal,
                p.cuotas,
                p.fecha         AS fecha_inicio,
                p.date_limit    AS fecha_limite,
                p.estado,
                ROUND((1 - p.monto_desc / p.monto) * 100, 1) AS progreso
              FROM prestamos p
              WHERE p.cedula_FK = '$cedula'
                AND p.estado = 1
              ORDER BY p.fecha DESC";

    $result = $this->connect_db()->query($query);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

	public function GetID_CPP($ID)
	{
		$query = "SELECT id_cuentasp, empleados.nombre, empleados.apellido, empleados.cedula, empleados.cargo, fecha, monto_desc, descuento, cuotas, concepto

				FROM cuentas_por_pagar INNER JOIN empleados ON cedula_FK = cedula
				WHERE id_cuentasp = $ID";
			
			$result= $this->connect_db()->query($query);
 			if ($result->num_rows > 0) 
 			{
      			$data = $result->fetch_assoc();
      			return $data;
      		}
	}

	public function Create_cuentas_por_pagar($descuento, $monto, $cuotas, $concepto, $cedula, $fecha)
	{
		$query = "INSERT INTO `cuentas_por_pagar`(`descuento`, `monto`,`monto_desc`, `cuotas`, `concepto`, `cedula_FK`, `tasaBCV_FK`, `fecha`) 
				VALUES ('$descuento','$monto', '$monto','$cuotas','$concepto', '$cedula',(SELECT `id_tasa` FROM `tasa_dolar` WHERE fecha = STR_TO_DATE(NOW(), '%Y-%m-%d')order by `id_tasa` LIMIT 1), '$fecha')";
		
		if ($result= $this->connect_db()->query($query)){
			return true;
		}else {
			return false;
		}
	}

public function cuentas_por_pagar_View()
	{
		$query = "SELECT id_cuentasp, empleados.nombre, empleados.apellido, empleados.cedula, fecha, monto_desc, descuento, cuotas, concepto

				FROM cuentas_por_pagar INNER JOIN empleados ON cedula_FK = cedula";
  		
  		$result = $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function Search_cuentas_por_pagar($fecha, $cedula = null)
	{
		$query = "SELECT id_cuentasp, empleados.nombre, empleados.apellido, empleados.cedula, fecha, monto_desc, descuento, cuotas, concepto
			
			FROM cuentas_por_pagar JOIN empleados ON cedula_FK = cedula";
		if ($fecha) {
        $query .= " WHERE DATE_FORMAT(fecha, '%Y-%m') = '$fecha'";
    	}

	    if ($cedula) {
	        if ($fecha) {
	            $query .= " AND";
	        } else {
	            $query .= " WHERE";
	        }
	        $query .= " empleados.cedula = '$cedula'";
	    }
  		
  		$result = $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function Exists_solicitud_prestamos($cedula)
	{
		$query = "SELECT * FROM solicitudes  
				WHERE estado = 'Espera' AND cedula_FK = '$cedula'";
  		
  		$result = $this->connect_db()->query($query);

 			if ($result->num_rows > 0) {
			 	return true;
			}else {
				return false;
			}
	}
	
// Función para el modulo de prestamos y cuentas por pagar Fin


	/*	Funciones correspondientes a la tabla Tasa_dolar*/
	public function verificar()
	{

		$query="SELECT tasa_del_dia FROM tasa_dolar WHERE fecha=STR_TO_DATE(NOW(), '%Y-%m-%d') ORDER BY id_tasa DESC LIMIT 1";
		$result= $this->connect_db()->query($query);

  		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}


	public function TasaDolar($periodo = 'diario', $mes = null, $anio = null) {
        if ($periodo === 'diario') {
            $query = "SELECT DATE(fecha) as fecha, MAX(tasa_del_dia) as tasa_del_dia 
                      FROM tasa_dolar 
                      WHERE 1=1";
            
            // Aplicar filtro de mes y año si están presentes
            if ($mes && $anio) {
                $query .= " AND MONTH(fecha) = $mes AND YEAR(fecha) = $anio";
            }
            
            $query .= " GROUP BY DATE(fecha) 
                        ORDER BY fecha DESC 
                        LIMIT 30"; // Últimos 30 días
        } elseif ($periodo === 'semanal') {
            $query = "SELECT YEARWEEK(fecha) as semana, AVG(tasa_del_dia) as tasa_del_dia 
                      FROM tasa_dolar 
                      GROUP BY YEARWEEK(fecha) 
                      ORDER BY semana DESC 
                      LIMIT 4"; // Últimas 4 semanas
        }
        
        $result = $this->connect_db()->query($query);
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

	public function verificar_exists()
	{
		$query="SELECT tasa_del_dia FROM tasa_dolar WHERE fecha=STR_TO_DATE(NOW(), '%Y-%m-%d') ORDER BY id_tasa DESC LIMIT 1";
		if ($result = $this->connect_db()->query($query)) {
			return true;
		}else{
			return false;
		}	
	}
	
	public function Create_Tasa_Dola($TasaBCV, $tasa2)
	{
		$TasaBCV = number_format($TasaBCV,2);
		$tasa2 = number_format($tasa2,2);

		$query="INSERT INTO tasa_dolar (`tasa_del_dia`,`tasa_eur`,`fecha`) 
				VALUES ('$TasaBCV', '$tasa2', STR_TO_DATE(NOW(), '%Y-%m-%d'))";

		if ($result= $this->connect_db()->query($query)){
			return true;
		}else {
			return false;
		}

	}

	function obtenerTasaDelDia($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Aumentar el tiempo de espera
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'); // User-Agent
		curl_setopt($ch, CURLOPT_ENCODING, ''); // Habilita la compresión
	
		$response = curl_exec($ch);
		
		if ($response === FALSE) {
			die('Error al obtener los datos de la tasa del día: ' . curl_error($ch));
		}
	
		curl_close($ch);
		return $response;
	}

	// Funciones de Fideicomiso 
	public function View_Fideicomiso()
	{
		$query = "SELECT empleados.nombre, empleados.apellido, empleados.cedula, empleados.f_ingreso, empleados.sueldo, `tasa_utilidad`, `t_bonovacacional`, `a_utilidad`, `a_bonovacional`, `sueldo_integral`, `sueldod_integral`, `dias_antiguedad`, `dias_acumulados`, `total_dias`, `monto`,`anticipo`, `fecha` FROM fideicomiso 
		INNER JOIN empleados ON cedula_FK = cedula";

		$result = $this->connect_db()->query($query);

		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function Search_Fide($fecha)
	{
		$query = "SELECT empleados.nombre, empleados.apellido, empleados.cedula, empleados.f_ingreso, empleados.sueldo, `tasa_utilidad`, `t_bonovacacional`, `a_utilidad`, `a_bonovacional`, `sueldo_integral`, `sueldod_integral`, `dias_antiguedad`, `dias_acumulados`, `total_dias`, `monto`,`anticipo`, `fecha` FROM fideicomiso 
			INNER JOIN empleados ON cedula_FK = cedula
			WHERE DATE_FORMAT(fecha, '%Y-%m') = '$fecha'";

  		$result = $this->connect_db()->query($query);

		$data = array();
  		while ($row = mysqli_fetch_assoc($result)) {
    	$data[] = $row;
  		}
  		return $data;
	}

	public function Insert_Fide($cedula,$tservicio, $tutilidad, $tbono, $alicuotaU,$alicuotaB,$sueldoITR,$sueldoITRD,$antiguedad,$acumulados,$totaldias,$anticipo,$monto)
	{
		$query = "INSERT INTO `fideicomiso`(`cedula_FK`, `tasaBCV_FK`, `t_servicio`, `tasa_utilidad`, `t_bonovacacional`, `a_utilidad`, `a_bonovacional`, `sueldo_integral`, `sueldod_integral`, `dias_antiguedad`, `dias_acumulados`, `total_dias`, `anticipo`, `monto`, `fecha`) 
			VALUES ($cedula, 
				(SELECT `id_tasa` FROM `tasa_dolar` WHERE fecha = STR_TO_DATE(NOW(), '%Y-%m-%d')order by `id_tasa` limit 1 ), $tservicio, $tutilidad, $tbono, $alicuotaU,$alicuotaB,$sueldoITR,$sueldoITRD,$antiguedad,$acumulados,$totaldias,$anticipo,$monto,
				STR_TO_DATE(NOW(), '%Y-%m-%d'))";

		if ($result= $this->connect_db()->query($query)){
			return true;
		}else {
			return false;
		}
	}

	// ── Fideicomiso agrupado por mes ────────────────────────────────────
public function View_Fideicomiso_Historial()
{
    $query = "SELECT
                DATE_FORMAT(fecha, '%Y-%m')          AS mes,
                DATE_FORMAT(MIN(fecha), '%d/%m/%Y')  AS fecha_inicio,
                DATE_FORMAT(MAX(fecha), '%d/%m/%Y')  AS fecha_fin,
                COUNT(*)                             AS total_empleados,
                SUM(monto)                           AS total_monto,
                SUM(anticipo)                        AS total_anticipo
              FROM fideicomiso
              GROUP BY DATE_FORMAT(fecha, '%Y-%m')
              ORDER BY mes DESC";

    $result = $this->connect_db()->query($query);
    $data   = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}



	/* Funciones de Eliminar */
	public function Eliminate_Nomina($ID)
	{
		$query="UPDATE nomina SET estado= 0 WHERE id_nomina='$ID'";
  		if ($result= $this->connect_db()->query($query)){
  				return true;
  			}else {
  				return false;
  			}
	}

	public function Eliminate_Vacation($ID)
	{
		$query="UPDATE vacaciones_y_utilidades SET estado= 0 WHERE vacaciones_id='$ID'";
  		if ($result= $this->connect_db()->query($query)){
  				return true;
  			}else {
  				return false;
  			}
	}

	public function Eliminate_Prestamo($ID)
	{
		$query="UPDATE prestamos SET estado= 0 WHERE id_prestamos='$ID'";
  		if ($result= $this->connect_db()->query($query)){
  				return true;
  			}else {
  				return false;
  			}
	}

	public function Eliminate_CPP($ID)
	{
		$query="UPDATE cuentas_por_pagar SET estado= 0 WHERE id_cuentasp='$ID'";
  		if ($result= $this->connect_db()->query($query)){
  				return true;
  			}else {
  				return false;
  			}
		}

		public function MidWeeks($fechaini, $fechaend) {
			$fechaini = new DateTime($fechaini);
			$fechaend = new DateTime($fechaend);
		
			// Verificar que la fecha de inicio sea menor que la fecha de fin
			if ($fechaend > $fechaini) {
				// Calcular el número total de días entre las dos fechas
				$interval = $fechaini->diff($fechaend);
				$totalDays = $interval->days;
		
				// Calcular el número de semanas completas
				$weeks = floor($totalDays / 7);
		
				// Si hay días restantes, no se cuenta como una semana completa
				return $weeks;
			} else {
				return array('error' => 'El límite del plazo es menor a la fecha de solicitud');
			}
		}


		// Funciones de Solicitud de prestamos

		public function Insert_Solicitud($cedula_FK, $monto, $descuento, $cuotas, $concepto, $f_solicitud, $estado)
		{
			$query = "INSERT INTO `solicitudes`(`cedula_FK`, `monto`, `descuento`, `cuotas`, `concepto`, `f_solicitud`, `estado`) 
				VALUES ('$cedula_FK', '$monto', '$descuento', '$cuotas', '$concepto', '$f_solicitud', '$estado')";
	
			if ($result= $this->connect_db()->query($query)){
				return true;
			}else {
				return false;
			}
		}

		public function Solicitudes_pendientes()
		{
			$query = "SELECT empleados.nombre, empleados.apellido, empleados.cedula,
			monto, descuento, cuotas, concepto, id_solicitud, f_solicitud, solicitudes.estado 
			FROM solicitudes 
			INNER JOIN empleados ON solicitudes.cedula_FK = empleados.cedula
			WHERE solicitudes.estado = 'Espera' AND empleados.estado = 1";
	
			$result = $this->connect_db()->query($query);

			$data = array();
			  while ($row = mysqli_fetch_assoc($result)) {
			$data[] = $row;
			  }
			  return $data;
		}

		public function Update_Solicitud($fecha, $id, $estado)
		{	
			$query = "UPDATE solicitudes SET estado = '$estado', f_aprobacion = '$fecha' WHERE id_solicitud = '$id'";
			if ($result= $this->connect_db()->query($query)){
				return true;
			}else {
				return false;
			}
		}

		public function Get_Solicitud($id){
			$query = "SELECT * FROM solicitudes WHERE id_solicitud = '$id'";
			$result = $this->connect_db()->query($query);

			$data = array();
			  while ($row = mysqli_fetch_assoc($result)) {
			$data[] = $row;
			  }
			  return $data;
		}

		function CalcularFechaLimite($fechaIni, $cuotas) {
			// Convertir la fecha inicial a un objeto DateTime
			$fechaIniDate = new DateTime($fechaIni);
		
			// Calcular la fecha límite sumando el número de semanas correspondientes a las cuotas
			$fechaLimiteDate = $fechaIniDate->modify('+' . $cuotas . ' weeks');
		
			// Devolver la fecha límite en formato ISO 8601 (YYYY-MM-DD)
			return $fechaLimiteDate->format('Y-m-d');
		}

		//feunciones para el pago de cuotas

		public function Insert_aporte($id,$deuda,$aporte,$tpago,$refe,$fecha)
		{
			$query = "INSERT INTO `cuentas_por_pagar2`
			(`id_prestamo`, `deuda`, `aporte`, `tpago`, `refe`, `fecha`, `estado`) 
			VALUES ('$id','$deuda','$aporte','$tpago','$refe','$fecha', '1')";
			if ($result= $this->connect_db()->query($query)){
				return true;
			}else {
				return false;
			}
		}

		public function Display_Prestamos_Aporte($cedula)
{
    $query = "SELECT 
                p.id_prestamos,
                p.monto_desc,
                p.descuento,
                COALESCE(
                    (SELECT cpp2.aporte 
                     FROM cuentas_por_pagar2 cpp2 
                     WHERE cpp2.id_prestamo = p.id_prestamos
                     AND WEEK(cpp2.fecha, 1) = WEEK(CURDATE(), 1)
                     AND YEAR(cpp2.fecha)    = YEAR(CURDATE())
                     AND cpp2.estado = 1
                     ORDER BY cpp2.id_cp DESC LIMIT 1),
                    p.descuento
                ) AS aporte_semana
              FROM prestamos p
              WHERE p.cedula_FK = '$cedula' 
              AND p.monto_desc > 0 
              AND p.estado = 1
              LIMIT 1";

    $result = $this->connect_db()->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

		
	}



$User = new UserE();
$Empleado = new Empleado();
$Nomina = new Nomina();